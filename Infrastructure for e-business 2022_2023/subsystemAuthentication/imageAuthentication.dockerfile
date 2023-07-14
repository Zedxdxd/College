FROM python:3

COPY ./configuration.py  /configuration.py
COPY ./subsystemAuthentication/mainAuthentication.py  /mainAuthentication.py
COPY ./subsystemAuthentication/modelsUsers.py  /modelsUsers.py
COPY ./requirements.txt  /requirements.txt

RUN pip install -r ./requirements.txt

ENTRYPOINT [ "python", "/mainAuthentication.py" ]