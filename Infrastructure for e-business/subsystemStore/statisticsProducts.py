import json
from pyspark.sql import SparkSession
from pyspark.sql.functions import col

import os

PRODUCTION = True if ("PRODUCTION" in os.environ) else False
DATABASE_IP = os.environ["DATABASE_IP"] if (
    "DATABASE_IP" in os.environ) else "localhost"
DATABASE_IP = "databaseStore"

builder = SparkSession.builder.appName("Product Statistics")

if (not PRODUCTION):
    builder = builder.master("local[*]")\
        .config(
        "spark.driver.extraClassPath",
        "mysql-connector-j-8.0.33.jar"
    )


spark = builder.getOrCreate()

product = spark.read \
    .format("jdbc") \
    .option("driver", "com.mysql.cj.jdbc.Driver") \
    .option("url", f"jdbc:mysql://{DATABASE_IP}/store") \
    .option("dbtable", "store.product") \
    .option("user", "root") \
    .load()

order = spark.read \
    .format("jdbc") \
    .option("driver", "com.mysql.cj.jdbc.Driver") \
    .option("url", f"jdbc:mysql://{DATABASE_IP}/store") \
    .option("dbtable", "store.order") \
    .option("user", "root") \
    .load()

order_product = spark.read \
    .format("jdbc") \
    .option("driver", "com.mysql.cj.jdbc.Driver") \
    .option("url", f"jdbc:mysql://{DATABASE_IP}/store") \
    .option("dbtable", "store.order_product") \
    .option("user", "root") \
    .load()

product.createOrReplaceTempView("product")
order.createOrReplaceTempView("order")
order_product.createOrReplaceTempView("order_product")

# result = spark.sql("select 	p.name as name, \
# 	(select sum(op2.quantity) from order_product op2 join order o2 on op2.order_id = o2.id where o2.status = 'COMPLETE' and op2.product_id = p.id group by op2.product_id) as sold, \
# 	coalesce ((select sum(op3.quantity) from order_product op3 join order o3 on op3.order_id = o3.id where o3.status = 'PENDING' and op3.product_id = p.id group by op3.product_id), 0) as waiting \
# from product p join order_product op on op.product_id = p.id join order o on o.id = op.order_id \
# where (select sum(op1.quantity) from order_product op1 join order o1 on op1.order_id = o1.id where o1.status = 'COMPLETE' and op1.product_id = p.id group by op1.product_id) > 0 \
# group by p.name, p.id;")

# result = spark.sql("select * from product")



product_sold = product.join(order_product, order_product["product_id"] == product["id"]) \
    .join(order, order_product["order_id"] == order["id"]) \
    .filter(order["status"] == "COMPLETE") \
    .groupBy(product["name"]) \
    .agg({"quantity": "sum"}) \
    .select(col("name").alias("name"), col("sum(quantity)").alias("sold"))

product_waiting = product.join(order_product, order_product["product_id"] == product["id"]) \
    .join(order, order_product["order_id"] == order["id"]) \
    .filter(order["status"] != "COMPLETE") \
    .groupBy(product["name"]) \
    .agg({"quantity": "sum"}) \
    .select(col("name").alias("name"), col("sum(quantity)").alias("waiting"))

result = product_sold.join(product_waiting, product_waiting["name"] == product_sold["name"], "fullouter") \
    .fillna(0).filter((product_sold["sold"] > 0 ) | (product_waiting["waiting"] > 0))


# print("start_res" + str(result.toJSON().collect()) + "end_res")

with open("data.txt", "w+") as json_file:
    for res in result.toJSON().collect():
        jsoned_data = json.dumps(res)
        json_file.write(jsoned_data)
        json_file.write("\n")

spark.stop()
