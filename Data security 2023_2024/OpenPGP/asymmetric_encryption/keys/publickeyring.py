from OpenPGP.asymmetric_encryption.keys import publickey
from datetime import datetime
import os
from OpenPGP.asymmetric_encryption.keys.publickey import PublicKey
from OpenPGP.asymmetric_encryption.keys import utilities
from cryptography.hazmat.primitives.serialization import load_pem_public_key
from OpenPGP.users.users_db import usersDB
from OpenPGP.asymmetric_encryption.keys.privatekeyring import private_keyring

class PublicKeyring:

    def __init__(self):
        # dictionary keys are userIDs (emails)
        self.public_keyring_userID = {}
        self.public_keyring_file = "../asymmetric_encryption/pem/public_keyring/public_keyring"
        private_keyring_path = "../asymmetric_encryption/pem/private_keyring/"
        prev_logged_in_user = usersDB.logged_in_user
        with open(self.public_keyring_file, "r") as file:
            for line in file:
                userID = line.split("|")[0]
                key_userID = line.split("|")[1]
                keyID = line.split("|")[2].rstrip("\n")
                usersDB.logged_in_user = usersDB.get_user(userID.split(":")[1])
                key_owner = usersDB.get_user(key_userID.split(":")[1])
                filename = private_keyring_path + key_userID.split(":")[1] + "_" + str(keyID) + ".pem"
                private_keyring.import_public(filename, key_owner.password, self)

        usersDB.logged_in_user = prev_logged_in_user
        private_keyring.initialization = False

    def write_to_file(self):
        with open(self.public_keyring_file, "w") as file:
            for userID in self.public_keyring_userID.keys():
                for key in self.public_keyring_userID[userID]:
                    file.write(userID + "|" + key.userID + "|" + str(key.keyID) + "\n")

    def get_keys_by_userID(self, userID):
        if userID in self.public_keyring_userID:
            return self.public_keyring_userID[userID]
        else:
            return []

    def get_key(self, userID, key_userID, keyID):
        if userID in self.public_keyring_userID:
            keys = self.public_keyring_userID[userID]
            for key in keys:
                if keyID == key.keyID and key.userID == key_userID:
                    return key
        return None

    def add_key(self, public_key, userID):

        new_public_key = PublicKey(
            public_key=public_key,
            userID=userID
        )

        if userID not in self.public_keyring_userID:
            self.public_keyring_userID[userID] = []
        self.public_keyring_userID[userID].append(new_public_key)

    def remove_key(self, key_userID, keyID):
        for userID in self.public_keyring_userID.keys():
            self.public_keyring_userID[userID] = list(
                filter(lambda key: keyID != key.keyID and key_userID != key.userID, self.public_keyring_userID[userID]))
            public_key = f"../asymmetric_encryption/pem/public_keyring/{key_userID.split(':')[1]}_{keyID}.pem"
            if os.path.exists(public_key):
                # Delete the file
                os.remove(public_key)
            self.write_to_file()

    def import_key(self, filename):
        with open(filename, 'r') as file:

            try:
                data = file.read()
                userID, pem = data.split('#')

                public_key = load_pem_public_key(pem.encode('utf-8'))

                pu = PublicKey(
                    public_key=public_key,
                    userID=userID
                )

                logged_in_userID = usersDB.logged_in_user.name + ":" + usersDB.logged_in_user.email

                if logged_in_userID in self.public_keyring_userID:
                    for key in self.public_keyring_userID[logged_in_userID]:
                        if key.keyID == pu.keyID:
                            return 0, pu
                    self.public_keyring_userID[logged_in_userID].append(pu)
                else:
                    self.public_keyring_userID[logged_in_userID] = []
                    self.public_keyring_userID[logged_in_userID].append(pu)

                self.write_to_file()
                return 0, pu
            except Exception as e:
                return 1, None


public_keyring = PublicKeyring()
