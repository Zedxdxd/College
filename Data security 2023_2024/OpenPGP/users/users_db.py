from OpenPGP.users.user import User
import base64
from cryptography.fernet import Fernet


key = b'abcdefghijklmnopqrstuvwxyz123456'
key = base64.urlsafe_b64encode(key)


def encrypt_password(password: str) -> bytes:
    f = Fernet(key)
    return f.encrypt(password.encode('utf-8'))


def decrypt_password(encrypted_password: bytes) -> str:
    f = Fernet(key)
    return f.decrypt(encrypted_password).decode('utf-8')


class UsersDB:
    def __init__(self):
        self.users = {}
        self.logged_in_user: User = None

        # wicked putanja jer mora da se pristupa relativno iz fajla koji je pokrenut
        with open("../users/users.txt", "rb") as file:
            for line in file:
                name = line.strip().split(b"|")[0].decode('utf-8')
                email = line.strip().split(b"|")[1].decode('utf-8')
                password = decrypt_password(line.strip().split(b"|")[2])
                self.users[email] = User(name=name, email=email, password=password)

    def login(self, email: str, password: str):
        if email not in self.users:
            return None
        user = self.users[email]
        if user.password != password:
            return None
        return user

    def register(self, name: str, email: str, password: str):
        new_user = User(name=name, email=email, password=password)
        self.users[email] = new_user
        with open("../users/users.txt", "ab") as file:
            file.write(new_user.name.encode("utf-8") + b"|" + new_user.email.encode("utf-8") + b"|")
            file.write(encrypt_password(new_user.password) + b"\n")

    def get_user(self, email: str):
        if email not in self.users:
            return None
        return self.users[email]


usersDB = UsersDB()
