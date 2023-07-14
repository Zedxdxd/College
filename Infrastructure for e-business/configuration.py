from datetime import timedelta
import os


class Configuration:
    SQLALCHEMY_DATABASE_URI = os.environ["DATABASE_URL"]
    JWT_SECRET_KEY = "JWT_SECRET_KEY"
    JWT_ACCESS_TOKEN_EXPIRES = timedelta(minutes=60)
    JWT_REFRESH_TOKEN_EXPIRES = timedelta(days=30)
