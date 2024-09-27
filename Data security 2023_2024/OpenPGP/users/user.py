class User:
    def __init__(self, name: str, email: str, password: str):
        self.name = name
        self.email = email
        self.password = password

    def __repr__(self):
        return f'User(name={self.name}, email={self.email}, password={self.password})'
