FROM bde2020/spark-python-template:3.3.0-hadoop3.3

# ENV SPARK_APPLICATION_PYTHON_LOCATION /app/database.py

CMD [ "python3", "/app/mainStatistics.py" ]
