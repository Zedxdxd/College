from cryptography.hazmat.primitives import serialization
from binascii import hexlify
import hashlib
from cryptography.fernet import Fernet
import base64

from OpenPGP.asymmetric_encryption.keys.publickey import PublicKey
from cryptography.hazmat.primitives import serialization
from cryptography.hazmat.primitives.asymmetric import rsa
from Crypto.Cipher import DES3
from cryptography.hazmat.primitives import padding
from Crypto.Util.Padding import pad
def get_key_id(public_key):

    # serialize to DER format
    # serializing public key
    # SubjectPublicKeyInfo - public key format, consists of an algorithm identifier and the public key as a bit string; PKCS1 - just the public key elements (without algorithm identifier), RSA only but older
    der = public_key.public_bytes(
        encoding = serialization.Encoding.DER,
        format = serialization.PublicFormat.SubjectPublicKeyInfo
    )

    # DER encoded public key to hex
    hex = hexlify(der).decode('utf-8')

    # keyID = PUi mod 2^64
    return int(hex, 16) % (2**64)

def print_public_key(public_key):

    pem = public_key.public_bytes(
        encoding=serialization.Encoding.PEM,
        format=serialization.PublicFormat.SubjectPublicKeyInfo
    )

    return pem.decode('utf-8')