from flask import Flask, request, jsonify
from flask_jwt_extended import JWTManager, get_jwt, jwt_required, get_jwt_identity
import os
from jwt import decode
from functools import wraps
from datetime import datetime
from web3 import Web3, HTTPProvider

from configuration import Configuration
from modelsStore import db, migrate, Product, Category, ProductCategory, Order, OrderProduct

app = Flask(__name__)
app.config.from_object(Configuration)
db.init_app(app)
migrate.init_app(app, db)
jwt = JWTManager(app)
web3 = Web3(HTTPProvider(f"http://{os.environ['BLOCKCHAIN_IP']}:8545"))

def read_file(path):
    with open(path, "r") as file:
        return file.read()


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


@app.route("/orders_to_deliver", methods=["GET"])
@role_check("courier")
def order_to_deliver():
    orders = Order.query.filter(Order.status == "CREATED").all()
    result = []
    for order in orders:
        result.append({
            "id": order.id,
            "email": order.user_email
        })

    return jsonify(orders=result), 200


@app.route("/pick_up_order", methods=["POST"])
@role_check("courier")
def pick_up_order():
    if "id" not in request.json:
        return jsonify(message="Missing order id."), 400

    try:
        order_id = int(request.json["id"])
    except ValueError:
        return jsonify(message="Invalid order id."), 400
    if order_id <= 0:
        return jsonify(message="Invalid order id."), 400

    order = Order.query.filter(Order.id == order_id).first()
    if not order or order.status == "PENDING" or order.status == "COMPLETE":
        return jsonify(message="Invalid order id."), 400
    
    if "address" not in request.json or len(request.json["address"]) == 0:
        return jsonify(message="Missing address."), 400
    if not web3.is_address(request.json["address"]):
        return jsonify(message="Invalid address."), 400
    
    abi = read_file("./solidity/output/OrderContract.abi")
    contract = web3.eth.contract(address=order.contract_address, abi=abi)
    if not contract.functions.getTransfered().call():
        return jsonify(message="Transfer not complete."), 400

    transaction_hash = contract.functions.pickUp(request.json["address"]).transact({
        "from": web3.eth.accounts[0]
    })
    contract_receipt = web3.eth.wait_for_transaction_receipt(transaction_hash)


    order.status = "PENDING"
    db.session.add(order)
    db.session.commit()

    return "", 200


if __name__ == "__main__":
    HOST = "0.0.0.0" if ("PRODUCTION" in os.environ) else "127.0.0.1"
    app.run(debug=True, host=HOST)
