from flask import Flask, request, jsonify
from flask_jwt_extended import JWTManager, create_access_token, create_refresh_token, get_jwt_identity, jwt_required
import os
import re
from jwt import decode

from configuration import Configuration
from modelsUsers import db, migrate, User, UserRole, Role

app = Flask(__name__)
app.config.from_object(Configuration)
db.init_app(app)
migrate.init_app(app, db)
jwt = JWTManager(app)


@app.route("/register_customer", methods=["POST"])
def register_customer():
    if "forename" not in request.json or len(request.json["forename"]) == 0:
        return jsonify(message="Field forename is missing."), 400
    if "surname" not in request.json or len(request.json["surname"]) == 0:
        return jsonify(message="Field surname is missing."), 400
    if "email" not in request.json or len(request.json["email"]) == 0:
        return jsonify(message="Field email is missing."), 400
    if "password" not in request.json or len(request.json["password"]) == 0:
        return jsonify(message="Field password is missing."), 400

    email = request.json["email"]
    password = request.json["password"]
    forename = request.json["forename"]
    surname = request.json["surname"]

    regex_email = r'\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,7}\b'
    if not re.fullmatch(regex_email, email):
        return jsonify(message="Invalid email."), 400

    if len(password) < 8 or len(password) > 256:
        return jsonify(message="Invalid password."), 400

    user_with_email = User.query.filter(User.email == email).first()
    if user_with_email:
        return jsonify(message="Email already exists."), 400

    new_user = User(
        email=email,
        password=password,
        forename=forename,
        surname=surname
    )

    db.session.add(new_user)
    db.session.commit()

    customer_role = Role.query.filter(Role.name == "customer").first()
    new_user = User.query.filter(User.email == email).first()

    new_userrole = UserRole(
        user_id=new_user.id,
        role_id=customer_role.id
    )

    db.session.add(new_userrole)
    db.session.commit()

    return "", 200


@app.route("/register_courier", methods=["POST"])
def register_courier():
    if "forename" not in request.json or len(request.json["forename"]) == 0:
        return jsonify(message="Field forename is missing."), 400
    if "surname" not in request.json or len(request.json["surname"]) == 0:
        return jsonify(message="Field surname is missing."), 400
    if "email" not in request.json or len(request.json["email"]) == 0:
        return jsonify(message="Field email is missing."), 400
    if "password" not in request.json or len(request.json["password"]) == 0:
        return jsonify(message="Field password is missing."), 400

    email = request.json["email"]
    password = request.json["password"]
    forename = request.json["forename"]
    surname = request.json["surname"]

    regex_email = r'\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,7}\b'
    if not re.fullmatch(regex_email, email):
        return jsonify(message="Invalid email."), 400

    if len(password) < 8 or len(password) > 256:
        return jsonify(message="Invalid password."), 400

    user_with_email = User.query.filter(User.email == email).first()
    if user_with_email:
        return jsonify(message="Email already exists."), 400

    new_user = User(
        email=email,
        password=password,
        forename=forename,
        surname=surname
    )

    db.session.add(new_user)
    db.session.commit()

    customer_role = Role.query.filter(Role.name == "courier").first()
    new_user = User.query.filter(User.email == email).first()

    new_userrole = UserRole(
        user_id=new_user.id,
        role_id=customer_role.id
    )

    db.session.add(new_userrole)
    db.session.commit()

    return "", 200


@app.route("/login", methods=["POST"])
def login():
    if "email" not in request.json or len(request.json["email"]) == 0:
        return jsonify(message="Field email is missing."), 400
    if "password" not in request.json or len(request.json["password"]) == 0:
        return jsonify(message="Field password is missing."), 400

    email = request.json["email"]
    password = request.json["password"]

    regex_email = r'\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,7}\b'
    if not re.fullmatch(regex_email, email):
        return jsonify(message="Invalid email."), 400

    logged_user = User.query.filter(
        User.email == email, User.password == password).first()
    if not logged_user:
        return jsonify(message="Invalid credentials."), 400

    claims = {
        "forename": logged_user.forename,
        "surname": logged_user.surname,
        "roles": [role.name for role in logged_user.roles]
    }

    access_token = create_access_token(
        identity=logged_user.email, additional_claims=claims)
    refresh_token = create_refresh_token(
        identity=logged_user.email, additional_claims=claims)
    return jsonify(accessToken=access_token), 200


@app.route("/delete", methods=["POST"])
@jwt_required()
def delete():
    token = None
    if "Authorization" in request.headers:
        token = request.headers["Authorization"].split(" ")[1]
    if not token:
        return jsonify(msg="Missing Authorization Header"), 401

    user_email = get_jwt_identity()
    user = User.query.filter(User.email == user_email).first()
    if not user:
        return jsonify(message="Unknown user."), 400

    db.session.delete(user)
    db.session.commit()

    return "", 200


if __name__ == "__main__":
    HOST = "0.0.0.0" if ("PRODUCTION" in os.environ) else "127.0.0.1"
    app.run(debug=True, host=HOST)
