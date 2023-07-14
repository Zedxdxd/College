from flask import Flask, request, jsonify
from flask_jwt_extended import JWTManager, get_jwt, jwt_required, get_jwt_identity
import os
from jwt import decode
from functools import wraps
from datetime import datetime
from web3 import Web3, HTTPProvider, Account
import json

from configuration import Configuration
from modelsStore import db, migrate, Product, Category, ProductCategory, Order, OrderProduct

app = Flask(__name__)
app.config.from_object(Configuration)
db.init_app(app)
migrate.init_app(app, db)
jwt = JWTManager(app)
web3 = Web3(HTTPProvider(f"http://{os.environ['BLOCKCHAIN_IP']}:8545"))


def role_check(role):
    def decorator(function):
        @jwt_required()
        @wraps(function)
        def wrapper(*args, **kwargs):
            token = None
            if "Authorization" in request.headers:
                token = request.headers["Authorization"].split(" ")[1]
            if not token:
                return jsonify(msg="Missing Authorization Header"), 401
            claims = get_jwt()
            if (role in claims["roles"]):
                return function(*args, **kwargs)
            else:
                return jsonify(msg="Missing Authorization Header"), 401

        return wrapper

    return decorator


def read_file(path):
    with open(path, "r") as file:
        return file.read()


@app.route("/search", methods=["GET"])
@role_check("customer")
def search():
    if "name" in request.args:
        product_name = request.args["name"]
    else:
        product_name = ""

    if "category" in request.args:
        category_name = request.args["category"]
    else:
        category_name = ""

    results = db.session.query(Product).join(ProductCategory).join(Category).filter(
        Product.name.like(f"%{product_name}%"), Category.name.like(f"%{category_name}%")).all()

    categories = set()
    products = []
    for p in results:
        for cat in p.categories:
            categories.add(cat.name)
        products.append({
            "id": p.id,
            "name": p.name,
            "price": p.price,
            "categories": [category.name for category in p.categories]
        })

    return jsonify(categories=list(categories), products=products), 200


@app.route("/order", methods=["POST"])
@role_check("customer")
def order():
    if "requests" not in request.json:
        return jsonify(message="Field requests is missing."), 400

    requests = request.json["requests"]
    request_number = 0
    for req in requests:
        if "id" not in req:
            return jsonify(message=f"Product id is missing for request number {request_number}."), 400

        if "quantity" not in req:
            return jsonify(message=f"Product quantity is missing for request number {request_number}."), 400

        try:
            if int(req["id"]) <= 0:
                return jsonify(message=f"Invalid product id for request number {request_number}."), 400
        except ValueError:
            return jsonify(message=f"Invalid product id for request number {request_number}."), 400

        try:
            if int(req["quantity"]) <= 0:
                return jsonify(message=f"Invalid product quantity for request number {request_number}."), 400
        except ValueError:
            return jsonify(message=f"Invalid product quantity for request number {request_number}."), 400

        product = Product.query.filter(Product.id == req["id"]).first()
        if not product:
            return jsonify(message=f"Invalid product for request number {request_number}."), 400

        request_number += 1

    if "address" not in request.json or len(request.json["address"]) == 0:
        return jsonify(message="Field address is missing."), 400

    if not web3.is_address(request.json["address"]):
        return jsonify(message="Invalid address."), 400

    address = web3.to_checksum_address(request.json["address"])

    bytecode = read_file("./solidity/output/OrderContract.bin")
    abi = read_file("./solidity/output/OrderContract.abi")

    contract = web3.eth.contract(bytecode=bytecode, abi=abi)
    transaction_hash = contract.constructor(address, web3.eth.accounts[0]).transact({
        "from": web3.eth.accounts[0],
    })

    contract_receipt = web3.eth.wait_for_transaction_receipt(transaction_hash)

    order = Order(
        user_email=get_jwt_identity(),
        status="CREATED",
        total_price=0,
        created_at=datetime.now().replace(microsecond=0).isoformat(),
        contract_address=contract_receipt.contractAddress
    )

    db.session.add(order)
    db.session.flush()

    total_price = 0
    for req in requests:
        product_id = req["id"]
        product_quantity = req["quantity"]
        order_product = OrderProduct(
            order_id=order.id,
            product_id=product_id,
            quantity=product_quantity
        )
        product = Product.query.filter(Product.id == product_id).first()
        total_price += product.price * product_quantity
        db.session.add(order_product)

    order.total_price = total_price
    db.session.add(order)
    db.session.commit()

    return jsonify(id=order.id), 200


@app.route("/status", methods=["GET"])
@role_check("customer")
def status():
    user_email = get_jwt_identity()

    orders = Order.query.filter(Order.user_email == user_email).all()
    result = []
    for order in orders:
        result.append({
            "products": [{
                "categories": [category.name for category in product.categories],
                "name": product.name,
                "price": product.price,
                "quantity": OrderProduct.query.filter(OrderProduct.order_id == order.id, OrderProduct.product_id == product.id).first().quantity
            } for product in order.products],
            "price": order.total_price,
            "status": order.status,
            "timestamp": order.created_at
        })

    return jsonify(orders=result), 200


@app.route("/delivered", methods=["POST"])
@role_check("customer")
def delivered():
    if "id" not in request.json:
        return jsonify(message="Missing order id."), 400

    try:
        order_id = int(request.json["id"])
    except ValueError:
        return jsonify(message="Invalid order id."), 400
    if order_id <= 0:
        return jsonify(message="Invalid order id."), 400

    order = Order.query.filter(Order.id == order_id).first()
    if not order or order.status != "PENDING":
        return jsonify(message="Invalid order id."), 400

    if "keys" not in request.json or len(request.json["keys"]) == 0:
        return jsonify(message="Missing keys."), 400
    if "passphrase" not in request.json or len(request.json["passphrase"]) == 0:
        return jsonify(message="Missing passphrase."), 400

    keys = json.loads(request.json["keys"].replace("\'", "\""))
    customer_address = web3.to_checksum_address(keys["address"])
    try:
        customer_private_key = Account.decrypt(
            keys, request.json["passphrase"]).hex()
    except ValueError:
        return jsonify(message="Invalid credentials."), 400

    abi = read_file("./solidity/output/OrderContract.abi")
    contract = web3.eth.contract(address=order.contract_address, abi=abi)
    if customer_address != contract.functions.getCustomer().call():
        return jsonify(message="Invalid customer account."), 400
    if not contract.functions.getTransfered().call():
        return jsonify(message="Transfer not complete."), 400
    if not contract.functions.getPickedUp().call():
        return jsonify(message="Delivery not complete."), 400

    transaction = contract.functions.transferToCourier().build_transaction({
        "from": customer_address,
        "nonce": web3.eth.get_transaction_count(customer_address),
        "gasPrice": web3.eth.gas_price
    })
    signed_transaction = web3.eth.account.sign_transaction(transaction, customer_private_key)
    transaction_hash = web3.eth.send_raw_transaction(signed_transaction.rawTransaction)
    contract_receipt = web3.eth.wait_for_transaction_receipt(transaction_hash)

    transaction = contract.functions.transferToOwner().build_transaction({
        "from": customer_address,
        "nonce": web3.eth.get_transaction_count(customer_address),
        "gasPrice": web3.eth.gas_price
    })
    signed_transaction = web3.eth.account.sign_transaction(transaction, customer_private_key)
    transaction_hash = web3.eth.send_raw_transaction(signed_transaction.rawTransaction)
    contract_receipt = web3.eth.wait_for_transaction_receipt(transaction_hash)

    order.status = "COMPLETE"
    db.session.add(order)
    db.session.commit()

    return "", 200


@app.route("/pay", methods=["POST"])
@role_check("customer")
def pay():
    if "id" not in request.json:
        return jsonify(message="Missing order id."), 400

    try:
        order_id = int(request.json["id"])
    except ValueError:
        return jsonify(message="Invalid order id."), 400
    if order_id <= 0:
        return jsonify(message="Invalid order id."), 400
    order = Order.query.filter(Order.id == order_id).first()
    if not order:
        return jsonify(message="Invalid order id."), 400

    if "keys" not in request.json or len(request.json["keys"]) == 0:
        return jsonify(message="Missing keys."), 400
    if "passphrase" not in request.json or len(request.json["passphrase"]) == 0:
        return jsonify(message="Missing passphrase."), 400
    try:
        keys = json.loads(request.json["keys"].replace("\'", "\""))
        customer_address = web3.to_checksum_address(keys["address"])
        customer_private_key = Account.decrypt(
            keys, request.json["passphrase"]).hex()
    except ValueError:
        return jsonify(message="Invalid credentials."), 400

    abi = read_file("./solidity/output/OrderContract.abi")
    contract = web3.eth.contract(address=order.contract_address, abi=abi)
    if web3.eth.get_balance(customer_address) < int(order.total_price):
        return jsonify(message="Insufficient funds." + customer_address), 400

    if contract.functions.getTransfered().call():
        return jsonify(message="Transfer already complete."), 400

    transaction = contract.functions.pay().build_transaction({
        "from": customer_address,
        "value": int(order.total_price),
        "nonce": web3.eth.get_transaction_count(customer_address),
        "gasPrice": web3.eth.gas_price
    })
    signed_transaction = web3.eth.account.sign_transaction(transaction, customer_private_key)
    transaction_hash = web3.eth.send_raw_transaction(signed_transaction.rawTransaction)
    contract_receipt = web3.eth.wait_for_transaction_receipt(transaction_hash)

    return "", 200


if __name__ == "__main__":
    HOST = "0.0.0.0" if ("PRODUCTION" in os.environ) else "127.0.0.1"
    app.run(debug=True, host=HOST)
