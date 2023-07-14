FROM python:3

COPY ./configuration.py  /configuration.py
COPY ./subsystemStore/mainOwner.py  /mainOwner.py
COPY ./subsystemStore/modelsStore.py  /modelsStore.py
COPY ./requirements.txt  /requirements.txt

RUN pip install -r ./requirements.txt

ENTRYPOINT [ "python", "/mainOwner.py" ]