from pyspark.sql import SparkSession

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

category = spark.read \
    .format("jdbc") \
    .option("driver", "com.mysql.cj.jdbc.Driver") \
    .option("url", f"jdbc:mysql://{DATABASE_IP}/store") \
    .option("dbtable", "store.category") \
    .option("user", "root") \
    .load()

order_product = spark.read \
    .format("jdbc") \
    .option("driver", "com.mysql.cj.jdbc.Driver") \
    .option("url", f"jdbc:mysql://{DATABASE_IP}/store") \
    .option("dbtable", "store.order_product") \
    .option("user", "root") \
    .load()

product_category = spark.read \
    .format("jdbc") \
    .option("driver", "com.mysql.cj.jdbc.Driver") \
    .option("url", f"jdbc:mysql://{DATABASE_IP}/store") \
    .option("dbtable", "store.product_category") \
    .option("user", "root") \
    .load()

order = spark.read \
    .format("jdbc") \
    .option("driver", "com.mysql.cj.jdbc.Driver") \
    .option("url", f"jdbc:mysql://{DATABASE_IP}/store") \
    .option("dbtable", "store.order") \
    .option("user", "root") \
    .load()

category.createOrReplaceTempView("category")
product.createOrReplaceTempView("product")
product_category.createOrReplaceTempView("product_category")
order_product.createOrReplaceTempView("order_product")
order.createOrReplaceTempView("order")

result = spark.sql("select c.name as name, sum(op.quantity) as q \
from category c join product_category pc on c.id = pc.category_id join product p on p.id = pc.product_id join order_product op on op.product_id = p.id join order o on o.id = op.order_id \
where o.status = 'COMPLETE' \
group by c.name \
\
union \
\
select c.name as name, 0 as q \
from category c  \
where c.name not in (select c1.name from category c1 join product_category pc1 on c1.id = pc1.category_id join product p1 on p1.id = pc1.product_id join order_product op1 on op1.product_id = p1.id join order o1 on o1.id = op1.order_id \
where o1.status = 'COMPLETE' \
group by c1.name) \
                   order by q desc, name asc; ")   #.orderBy(["q", "name"], ascending=[0, 1])

# result = spark.sql("select * from product")

# print("start_res" + str(result.toJSON().collect()) + "end_res")



import json

with open("data.txt", "w+") as json_file:
    for res in result.toJSON().collect():
        jsoned_data = json.dumps(res)
        json_file.write(jsoned_data)
        json_file.write("\n")

spark.stop()