from flask import Flask, request, jsonify
from flask_jwt_extended import JWTManager, get_jwt, jwt_required
import os
from jwt import decode
from functools import wraps
import requests

from configuration import Configuration
from modelsStore import db, migrate, Product, Category, ProductCategory

app = Flask(__name__)
app.config.from_object(Configuration)
db.init_app(app)
migrate.init_app(app, db)
jwt = JWTManager(app)


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


@app.route("/update", methods=["POST"])
@role_check("owner")
def update():
    if not "file" in request.files:
        return jsonify(message="Field file is missing."), 400

    content = request.files["file"].stream.read().decode()
    all_products = []
    line_number = 0
    for line in content.split("\n"):
        data = line.split(",")
        if len(data) < 3:
            return jsonify(message=f"Incorrect number of values on line {line_number}."), 400
        try:
            if float(data[2]) <= 0:
                return jsonify(message=f"Incorrect price on line {line_number}."), 400
        except ValueError:
            return jsonify(message=f"Incorrect price on line {line_number}."), 400

        categories = data[0]
        product_name = data[1]
        product_price = data[2]

        product = Product.query.filter(Product.name == product_name).first()
        if product:
            return jsonify(message=f"Product {product_name} already exists."), 400

        all_products.append(data)

        line_number += 1

    for data in all_products:
        categories = data[0]
        product_name = data[1]
        product_price = data[2]

        product = Product(
            name=product_name,
            price=product_price
        )

        db.session.add(product)
        db.session.flush()

        for category_name in categories.split("|"):
            category = Category.query.filter(
                Category.name == category_name).first()
            if not category:
                category = Category(
                    name=category_name
                )
                db.session.add(category)
                db.session.flush()

            product_category = ProductCategory(
                product_id=product.id,
                category_id=category.id
            )

            db.session.add(product_category)
            db.session.flush()

    db.session.commit()
    return "", 200


@app.route("/product_statistics", methods=["GET"])
@role_check("owner")
def product_statistics():
    r = requests.get("http://statistics:5000/product_statistics").content
    return r, 200

@app.route("/category_statistics", methods=["GET"])
@role_check("owner")
def category_statistics():
    r = requests.get("http://statistics:5000/category_statistics").content
    return r, 200


if __name__ == "__main__":
    HOST = "0.0.0.0" if ("PRODUCTION" in os.environ) else "127.0.0.1"
    app.run(debug=True, host=HOST)
