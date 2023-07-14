from flask import Flask, jsonify
import os
import subprocess
import json
from json import JSONDecodeError

app = Flask(__name__)


@app.route("/product_statistics", methods=["GET"])
def product_statistics():
    os.environ["SPARK_APPLICATION_PYTHON_LOCATION"] = "/app/statisticsProducts.py"
    os.environ["SPARK_SUBMIT_ARGS"] = "--driver-class-path /app/mysql-connector-j-8.0.33.jar --jars /app/mysql-connector-j-8.0.33.jar"
    

    result = subprocess.check_output(["/template.sh"]).decode()
    # return result
    resp = []
    with open("data.txt") as json_file:
     for line in json_file:
        json_data = json.loads(line)
        resp.append(json.loads(json_data))

    return jsonify(statistics=resp), 200


@app.route("/category_statistics", methods=["GET"])
def category_statistics():
    os.environ["SPARK_APPLICATION_PYTHON_LOCATION"] = "/app/statisticsCategory.py"
    os.environ["SPARK_SUBMIT_ARGS"] = "--driver-class-path /app/mysql-connector-j-8.0.33.jar --jars /app/mysql-connector-j-8.0.33.jar"

    result = subprocess.check_output(["/template.sh"]).decode()
    
    resp = []
    with open("data.txt") as json_file:
     for line in json_file:
        json_data = json.loads(line)
        resp.append(json.loads(json_data)["name"])

    return jsonify(statistics=resp), 200

if __name__ == "__main__":
    HOST = "0.0.0.0" if ("PRODUCTION" in os.environ) else "127.0.0.1"
    app.run(debug=True, host=HOST)
