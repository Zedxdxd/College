import ast

from Crypto.Cipher import DES3, AES
import secrets
from cryptography.hazmat.primitives import padding

from OpenPGP.asymmetric_encryption.asymmetricencryption import AsymmetricEncryption
from OpenPGP.asymmetric_encryption.keys.privatekey import PrivateKey
from OpenPGP.asymmetric_encryption.keys.publickey import PublicKey
from OpenPGP.asymmetric_encryption.keys.privatekeyring import PrivateKeyring
from OpenPGP.asymmetric_encryption.keys.publickeyring import PublicKeyring
from OpenPGP.users.users_db import usersDB
from Crypto.Util.Padding import pad, unpad

asymmetric_encryption = AsymmetricEncryption()


class SymmetricEncryption:

    def __init__(self, algorithm, public_keyring: PublicKeyring, private_keyring: PrivateKeyring):
        if algorithm == "3DES":
            self.algorithm = DES3
            self.iv = b'20200373'
        else:
            self.algorithm = AES
            self.iv = b'2020000320200073'
        self.public_keyring = public_keyring
        self.private_keyring = private_keyring

    def encrypt(self, message, recipient_id, recipient_key_id):

        # encrypting signature+message with session key

        if self.algorithm == DES3:
            session_key = secrets.token_bytes(24)
        else:
            session_key = secrets.token_bytes(16)

        algorithm = self.algorithm.new(session_key, self.algorithm.MODE_CBC, self.iv)

        if isinstance(message, str):
            message_padded = pad(message.encode('utf-8'), algorithm.block_size)
        else:
            message_padded = pad(message, algorithm.block_size)

        encrypted_message = algorithm.encrypt(message_padded)

        # encrypting session key

        logged_in_userID = usersDB.logged_in_user.name + ":" + usersDB.logged_in_user.email
        public_key = self.public_keyring.get_key(logged_in_userID, recipient_id, recipient_key_id)
        if not public_key:
            print(f"Kljuc primaoca {recipient_id} ne postoji u prstenu kljuceva")
            return

        # session_key_string = str(session_key)
        pu = public_key.public_key

        from cryptography.hazmat.primitives import hashes
        from cryptography.hazmat.primitives.asymmetric import padding
        encrypted_session_key = pu.encrypt(
            session_key,
            padding.OAEP(
                mgf=padding.MGF1(algorithm=hashes.SHA256()),
                algorithm=hashes.SHA256(),
                label=None
            )
        )
        return encrypted_message, encrypted_session_key

    def decrypt(self, encrypted_message, encrypted_session_key, receiver_id, receiver_key_id, passphrase):

        private_key = self.private_keyring.get_key(receiver_id, receiver_key_id)

        session_key = asymmetric_encryption.decrypt(private_key, encrypted_session_key, passphrase)
        # session_key = session_key.decode('utf-8')
        # session_key = ast.literal_eval(session_key)

        algorithm = self.algorithm.new(session_key, self.algorithm.MODE_CBC, self.iv)

        # decrypting encrypted message+signature

        decrypted = algorithm.decrypt(encrypted_message)

        decrypted = unpad(decrypted, algorithm.block_size)

        return decrypted
