from flask_sqlalchemy import SQLAlchemy
from flask_migrate import Migrate

db = SQLAlchemy()
migrate = Migrate()


class ProductCategory(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    product_id = db.Column(db.Integer, db.ForeignKey("product.id"), nullable=False)
    category_id = db.Column(db.Integer, db.ForeignKey("category.id"), nullable=False)

    
class OrderProduct(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    product_id = db.Column(db.Integer, db.ForeignKey("product.id"), nullable=False)
    order_id = db.Column(db.Integer, db.ForeignKey("order.id"), nullable=False)
    quantity = db.Column(db.Integer, nullable=False)


class Product(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(256), nullable=False, unique=True)
    price = db.Column(db.Double, nullable=False)

    categories = db.relationship("Category", secondary=ProductCategory.__table__, back_populates="products")
    orders = db.relationship("Order", secondary=OrderProduct.__table__, back_populates="products")


class Category(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(256), nullable=False, unique=True)

    products = db.relationship("Product", secondary=ProductCategory.__table__, back_populates="categories")


class Order(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    user_email = db.Column(db.String(256), nullable=False)
    status = db.Column(db.String(256), nullable=False)  # "CREATED", "PENDING", "COMPLETE"
    total_price = db.Column(db.Double, nullable=False)
    created_at = db.Column(db.DateTime, nullable=False)
    contract_address = db.Column(db.String(256), nullable=False)

    products = db.relationship("Product", secondary=OrderProduct.__table__, back_populates="orders")
    

