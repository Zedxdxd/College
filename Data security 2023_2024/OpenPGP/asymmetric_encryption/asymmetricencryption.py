from cryptography.hazmat.primitives import hashes

from cryptography.hazmat.primitives.asymmetric import padding
from OpenPGP.asymmetric_encryption.keys.privatekey import PrivateKey
from OpenPGP.asymmetric_encryption.keys.publickey import PublicKey


class AsymmetricEncryption:

    def sign(self, private_key: PrivateKey, message, passphrase):

        private_key = PrivateKey.retrieve_private_key(private_key=private_key, passphrase=passphrase)

        message_digest = hashes.Hash(hashes.SHA1())
        message_digest.update(message.encode('utf-8'))
        message_digest = message_digest.finalize()

        signature = private_key.sign(
            message_digest,
            padding.PKCS1v15(),
            hashes.SHA1()
        )

        return signature

    def verify(self, public_key: PublicKey, signature, message):
        try:

            message_digest = hashes.Hash(hashes.SHA1())
            message_digest.update(message.encode('utf-8'))
            message_digest = message_digest.finalize()

            public_key.verify(
                signature,
                message_digest,
                padding.PKCS1v15(),
                hashes.SHA1()
            )
            return True
        except:
            return False

    def encrypt(self, public_key, message):

        # OAEP - optimal asymmetric encryption padding
        # valid paddings for encryption are OAEP and PKCS1v15
        # mgf - mask generation function is based on a hash function
        # algorithm - specifies the hash algorithm used for OAEP padding
        encrypted = public_key.encrypt(
            message.encode('utf-8'),
            padding.OAEP(
                mgf=padding.MGF1(algorithm=hashes.SHA256()),
                algorithm=hashes.SHA256(),
                label=None
            )
        )

        return encrypted

    def decrypt(self, private_key: PrivateKey, encrypted, passphrase):

        private_key = PrivateKey.retrieve_private_key(private_key, passphrase)

        decrypted = private_key.decrypt(
            encrypted,
            padding.OAEP(
                mgf=padding.MGF1(algorithm=hashes.SHA256()),
                algorithm=hashes.SHA256(),
                label=None
            )
        )

        return decrypted