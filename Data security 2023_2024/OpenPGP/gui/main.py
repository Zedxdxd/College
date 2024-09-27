# pyuic5 -x OpenPGP/ui/import_export_window.ui -o OpenPGP/gui/import_export_window.py

from cryptography.fernet import Fernet
import base64
from OpenPGP.asymmetric_encryption.keys import utilities

password = "12345"

print(password == utilities.decrypt_password(utilities.encrypt_password(password)))