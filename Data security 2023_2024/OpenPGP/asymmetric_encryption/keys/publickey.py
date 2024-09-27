import time

from cryptography.hazmat.primitives import serialization
from cryptography.hazmat.primitives.serialization import load_pem_public_key

from OpenPGP.asymmetric_encryption.keys import utilities
from OpenPGP.users.users_db import usersDB

PEM = '../pem/public_keyring/'


class PublicKey:

    def __init__(self, public_key, userID):
        self.timestamp = time.time()
        self.keyID = utilities.get_key_id(public_key)
        self.public_key = public_key
        self.userID = userID

    def export_key(self):

        pem = self.public_key.public_bytes(
            encoding=serialization.Encoding.PEM,
            format=serialization.PublicFormat.SubjectPublicKeyInfo
        ).decode('utf-8')

        key = self.userID + "#" + pem

        # ovo ne radi jer putanja treba da se formira iz konteksta gde je pokrenut .py
        # a ne relativno u odnosu na ovaj fajl, pa posto se pokrece gui/login_register_window.py
        # pravi se relativna putanja odatle :(
        # filename = f'{PEM}{self.keyID}.pem'
        filename = f'../asymmetric_encryption/pem/public_keyring/{self.userID.split(":")[1]}_{self.keyID}.pem'

        with open(filename, 'w') as file:
            file.write(key)

    def repr_public_key(self):
        numbers = self.public_key.public_numbers()
        return f"e = {numbers.e} n = {numbers.n}"