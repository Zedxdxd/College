FROM python:3

COPY ./configuration.py  /configuration.py
COPY ./subsystemStore/mainCourier.py  /mainCourier.py
COPY ./subsystemStore/modelsStore.py  /modelsStore.py
COPY ./requirements.txt  /requirements.txt
COPY ./solidity /solidity

RUN pip install -r ./requirements.txt

ENTRYPOINT [ "python", "mainCourier.py" ]