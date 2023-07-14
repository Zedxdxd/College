from sqlalchemy_utils import database_exists, create_database
from flask import Flask
from flask_migrate import init, migrate, upgrade

from configuration import Configuration
from modelsUsers import db, migrate as mig, User, UserRole, Role

app = Flask(__name__)
app.config.from_object(Configuration)
db.init_app(app)
mig.init_app(app, db)

if not database_exists(Configuration.SQLALCHEMY_DATABASE_URI):
    create_database(Configuration.SQLALCHEMY_DATABASE_URI)

with app.app_context():
    init()
    migrate(message="Migration")
    upgrade()

    customer_role = Role(
        name="customer"
    )
    db.session.add(customer_role)

    courier_role = Role(
        name="courier"
    )
    db.session.add(courier_role)

    owner_role = Role(
        name="owner"
    )
    db.session.add(owner_role)

    owner = User(
        forename="Scrooge",
        surname="McDuck",
        email="onlymoney@gmail.com",
        password="evenmoremoney"
    )
    db.session.add(owner)
    db.session.commit()

    owner_user_role = UserRole(
        user_id=owner.id,
        role_id=owner_role.id
    )

    db.session.add(owner_user_role)

    db.session.commit()
