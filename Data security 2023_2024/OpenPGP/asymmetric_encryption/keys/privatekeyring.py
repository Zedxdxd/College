import hashlib
import os

from cryptography.hazmat.primitives import serialization
from cryptography.hazmat.primitives.asymmetric import rsa
from Crypto.Cipher import DES3
from cryptography.hazmat.primitives import padding
from Crypto.Util.Padding import pad

from OpenPGP.asymmetric_encryption.keys.privatekey import PrivateKey
from OpenPGP.asymmetric_encryption.keys.publickey import PublicKey
from OpenPGP.asymmetric_encryption.keys import utilities
from cryptography.hazmat.primitives.serialization import load_pem_private_key
from OpenPGP.users.users_db import usersDB


class PrivateKeyring:

    def __init__(self):
        self.private_keyring_userID: dict[str: PrivateKey] = {}
        private_keys_path = "../asymmetric_encryption/pem/private_keyring/"
        self.initialization = True
        prev_logged_in_user = usersDB.logged_in_user
        for file in os.listdir(private_keys_path):
            if not file.endswith(".pem"):
                continue
            usersDB.logged_in_user = usersDB.get_user(file.split("_")[0])
            self.import_key(private_keys_path + file, usersDB.logged_in_user.password)

        usersDB.logged_in_user = prev_logged_in_user

    def get_keys_by_userID(self, userID):
        if userID in self.private_keyring_userID:
            return self.private_keyring_userID[userID]
        else:
            return []

    def get_key(self, userID, keyID):
        if userID in self.private_keyring_userID:
            keys = self.private_keyring_userID[userID]
            for key in keys:
                if keyID == key.keyID:
                    return key
        return None

    def remove_key(self, userID, keyID):
        if userID in self.private_keyring_userID:
            self.private_keyring_userID[userID] = list(
                filter(lambda key: keyID != key.keyID, self.private_keyring_userID[userID]))
            private_key = f"../asymmetric_encryption/pem/private_keyring/{userID.split(':')[1]}_{keyID}.pem"
            if os.path.exists(private_key):
                # Delete the file
                os.remove(private_key)


    def generate_keys(self, name, email, key_size, passphrase):
        # hashed passphrase used as key for encrypting with symmetric algorithm
        string_to_bytes = passphrase.encode('utf-8')
        hash = hashlib.sha1(string_to_bytes)
        hash_hex = hash.digest()

        # extending hash to 24 bytes
        # TDES uses key length of 24 bytes, and SHA1 produces 20 bytes hash
        key = hash_hex.ljust(24, b'\0')

        # symmetric algorithm for encrypting private key
        algorithm = DES3.new(key, DES3.MODE_CBC, b'20200373')

        private_key = rsa.generate_private_key(public_exponent=65537, key_size=key_size)
        public_key = private_key.public_key()

        userID = f"{name}:{email}"
        keyID = utilities.get_key_id(public_key)

        # serializing private key (transforming to bytes)
        # Encoding.PEM - generates PEM-encoded byte string
        # PKCS1 (OpenSSL) traditionally for PEM encoded RSA key; PKCS8 modern format with better encryption
        private_key_pem = private_key.private_bytes(
            encoding=serialization.Encoding.PEM,
            format=serialization.PrivateFormat.PKCS8,
            encryption_algorithm=serialization.NoEncryption()
        )

        # symmetric padding - making sure key is a multiple of the block size for CBC mode
        padded_private_key_pem = pad(private_key_pem, algorithm.block_size)

        # encrypt private key
        encrypted_private_key = algorithm.encrypt(padded_private_key_pem)

        from OpenPGP.asymmetric_encryption.keys.privatekey import PrivateKey
        private_key = PrivateKey(
            public_key=public_key,
            encrypted_private_key=encrypted_private_key,
            userID=userID,
            key_size=key_size
        )

        if userID not in self.private_keyring_userID:
            self.private_keyring_userID[userID] = []
        self.private_keyring_userID[userID].append(private_key)

        # export to save permanently
        private_key.export_key(passphrase)

        return keyID

    def import_public(self, filename, passphrase, public_keyring):
        with open(filename, 'r') as file:

            try:
                data = file.read()
                userID, key_size, pem = data.split('#')

                private_key = load_pem_private_key(pem.encode('utf-8'), passphrase.encode('utf-8'))

                pu = PublicKey(
                    public_key=private_key.public_key(),
                    userID=userID
                )

                logged_in_userID = usersDB.logged_in_user.name + ":" + usersDB.logged_in_user.email

                if logged_in_userID in public_keyring.public_keyring_userID:
                    for key in public_keyring.public_keyring_userID[logged_in_userID]:
                        if key.keyID == pu.keyID:
                            return 0, pu
                    public_keyring.public_keyring_userID[logged_in_userID].append(pu)
                else:
                    public_keyring.public_keyring_userID[logged_in_userID] = []
                    public_keyring.public_keyring_userID[logged_in_userID].append(pu)

                if not self.initialization:
                    public_keyring.write_to_file()

                return 0, pu
            except Exception as e:
                return 1, None

    def import_key(self, filename, passphrase):

        with open(filename, 'r') as file:

            try:
                data = file.read()
                userID, key_size, pem = data.split('#')

                private_key = load_pem_private_key(pem.encode('utf-8'), passphrase.encode('utf-8'))

                # hashed passphrase used as key for encrypting with symmetric algorithm
                string_to_bytes = passphrase.encode('utf-8')
                hash = hashlib.sha1(string_to_bytes)
                hash_hex = hash.digest()

                # extending hash to 24 bytes
                # TDES uses key length of 24 bytes, and SHA1 produces 20 bytes hash
                key = hash_hex.ljust(24, b'\0')

                # symmetric algorithm for encrypting private key
                algorithm = DES3.new(key, DES3.MODE_CBC, b'20200373')

                private_key_pem = private_key.private_bytes(
                    encoding=serialization.Encoding.PEM,
                    format=serialization.PrivateFormat.PKCS8,
                    encryption_algorithm=serialization.NoEncryption()
                )

                padded_private_key_pem = pad(private_key_pem, algorithm.block_size)

                # encrypt private key
                encrypted_private_key = algorithm.encrypt(padded_private_key_pem)

                pr = PrivateKey(
                    public_key=private_key.public_key(),
                    encrypted_private_key=encrypted_private_key,
                    userID=userID,
                    key_size=int(key_size)
                )
                if userID in self.private_keyring_userID:
                    for key in self.private_keyring_userID[userID]:
                        if key.keyID == pr.keyID:
                            return 0, pr
                    self.private_keyring_userID[userID].append(pr)
                else:
                    self.private_keyring_userID[userID] = []
                    self.private_keyring_userID[userID].append(pr)
                return 0, pr
            except Exception as e:
                app = e
                return 1, None


private_keyring = PrivateKeyring()
