from sqlalchemy_utils import database_exists, create_database
from flask import Flask
from flask_migrate import init, migrate, upgrade

from configuration import Configuration
from modelsStore import db, migrate as mig

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