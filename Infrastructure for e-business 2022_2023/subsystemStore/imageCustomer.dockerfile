FROM python:3

COPY ./configuration.py  /configuration.py
COPY ./subsystemStore/mainCustomer.py  /mainCustomer.py
COPY ./subsystemStore/modelsStore.py  /modelsStore.py
COPY ./requirements.txt  /requirements.txt
COPY ./solidity /solidity
COPY ./keys.json /keys.json

RUN pip install -r ./requirements.txt

ENTRYPOINT [ "python", "/mainCustomer.py" ]