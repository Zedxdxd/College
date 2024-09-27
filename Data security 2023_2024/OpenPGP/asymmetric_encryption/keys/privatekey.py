import time
import os

from cryptography.hazmat.primitives.serialization import load_pem_private_key, load_pem_public_key, load_der_private_key
from cryptography.hazmat.primitives import serialization

from Crypto.Cipher import DES3
import hashlib
from cryptography.hazmat.primitives import padding
from OpenPGP.asymmetric_encryption.keys import utilities
from Crypto.Util.Padding import unpad

PEM = '../asymmetric_encryption/pem/private_keyring/'


class PrivateKey:

    def __init__(self, public_key, encrypted_private_key, userID, key_size):
        self.timestamp = time.time()
        self.keyID = utilities.get_key_id(public_key)
        self.public_key = public_key
        self.encrypted_private_key = encrypted_private_key
        self.userID = userID
        self.key_size = key_size

    def export_key(self, passphrase):

        private_key = PrivateKey.retrieve_private_key(self, passphrase)

        pem = private_key.private_bytes(
            encoding=serialization.Encoding.PEM,
            format=serialization.PrivateFormat.PKCS8,
            encryption_algorithm=serialization.BestAvailableEncryption(passphrase.encode('utf-8'))
        ).decode('utf-8')

        key = self.userID + "#" + str(self.key_size) + "#" + pem
        filename = f'{PEM}{self.userID.split(":")[1]}_{self.keyID}.pem'

        with open(filename, 'w') as file:
            file.write(key)

    @classmethod
    def retrieve_private_key(self, private_key, passphrase):

        string_to_bytes = passphrase.encode('utf-8')
        hash = hashlib.sha1(string_to_bytes)
        hash_hex = hash.digest()

        key = hash_hex.ljust(24, b'\0')

        encrypted_private_key = private_key.encrypted_private_key

        algorithm = DES3.new(key, DES3.MODE_CBC, b'20200373')

        decrypted_private_key = algorithm.decrypt(encrypted_private_key)

        decrypted_private_key_pem = unpad(decrypted_private_key, algorithm.block_size)

        decrypted_private_key = load_pem_private_key(decrypted_private_key_pem, password=None)

        return decrypted_private_key

    def repr_public_key(self):
        numbers = self.public_key.public_numbers()
        return f"e = {numbers.e} n = {numbers.n}"
