<?php
    declare(strict_types = 1);

    class Client {
        public int $id;
        public string $username;
        public string $name;
        public string $password;
        public string $email;
        public string $pfp;
        public int $role;
        
        public function __construct(int $id, string $username, string $name, string $password, string $email, string $pfp, int $role) {
            $this->id = $id;
            $this->username = $username;
            $this->name = $name;
            $this->password = $password;
            $this->email = $email;
            $this->pfp = $pfp;
            $this->role = $role;
        }

        static function getClientWithPassoword(PDO $db, string $username, string $password) : ?Client {
            $passHashed = hash('sha256', $password);

            $stmt = $db->prepare('
                SELECT ClientID, Username, Name, Password, Email, Photo, Role
                FROM Client
                WHERE Username = ? AND Password = ?
            ');
            
            $stmt->execute(array($username, $passHashed));

            if ($client = $stmt->fetch()) {
                return new Client(
                    $client['ClientID'],
                    $client['Username'],
                    $client['Name'],
                    $client['Password'],
                    $client['Email'],
                    $client['Photo'],
                    $client['Role']
                );
            }

            return null;
        }

        static function getAllClients(PDO $db) : array {
            $stmt = $db->prepare('
                SELECT * FROM Client;
            ');

            $stmt->execute(array());

            $users = array();

            while ($user = $stmt->fetch()) {
                $users[] = new Client(
                    $user['ClientID'],
                    $user['Username'],
                    $user['Name'],
                    $user['Password'],
                    $user['Email'],
                    $user['Photo'],
                    $user['Role']
                );
            }

            return $users;
        }

        static function getAllClientsDynamic(PDO $db, string $search) : array {
            $stmt = $db->prepare('
                SELECT * 
                FROM Client
                WHERE Username LIKE ?
            ');

            $stmt->execute(array($search . '%'));

            $users = array();

            while ($user = $stmt->fetch()) {
                $users[] = new Client(
                    $user['ClientID'],
                    $user['Username'],
                    $user['Name'],
                    $user['Password'],
                    $user['Email'],
                    $user['Photo'],
                    $user['Role']
                );
            }

            return $users;
        }

        static function getClientID(PDO $db, string $username) : int {
            $stmt = $db->prepare('
                SELECT ClientID
                FROM Client
                WHERE Username = ?
            ');

            $stmt->execute(array($username));

            $id = $stmt->fetch();
            return $id['ClientID'];
        }

        static function getClient(PDO $db, int $id) : Client {
            $stmt = $db->prepare('
                SELECT ClientID, Username, Name, Password, Email, Photo, Role
                FROM Client
                WHERE ClientID = ?
            ');

            $stmt->execute(array($id));

            $client = $stmt->fetch();

            return new Client(
                $client['ClientID'],
                $client['Username'],
                $client['Name'],
                $client['Password'],
                $client['Email'],
                $client['Photo'],
                $client['Role']
            );
        }

        static function getClientFromUsername(PDO $db, string $username) : ?Client {
            try {
                $stmt = $db->prepare('
                    SELECT ClientID, Username, Name, Password, Email, Photo, Role
                    FROM Client
                    WHERE Username = ?
                ');

                $stmt->execute(array($username));

                if ($client = $stmt->fetch()) {
                    return new Client(
                        $client['ClientID'],
                        $client['Username'],
                        $client['Name'],
                        $client['Password'],
                        $client['Email'],
                        $client['Photo'],
                        $client['Role']
                    );
                }

                return null;
            } catch (PDOEsception $e) {
                return null;
            }
        }

        static function duplicateUsername(PDO $db, string $username) : bool {
            $stmt = $db->prepare('
                SELECT ClientID
                FROM Client
                WHERE Username = ?
            ');
            
            $stmt->execute(array($username));
            return $stmt->fetch() !== false;
        }

        static function duplicateEmail(PDO $db, string $email) : bool {
            $stmt = $db->prepare('
                SELECT Email
                FROM Client
                WHERE Email = ?
            ');

            $stmt->execute(array($email));
            return $stmt->fetch() !== false;
        }

        static function createClient($db, $username, $name, $password, $email) {
            $passHash = hash('sha256', $password);
            
            $stmt = $db->prepare('
                INSERT INTO Client(Username, Name, Password, Email) VALUES (?, ?, ?, ?)
            ');

            if ($stmt->execute(array($username, $name, $passHash, $email))) {
                $id = Client::getClientID($db, $username);
                return $id;
            }
            else {
                return -1;
            }

        }

        function save($db) {
            $stmt = $db->prepare('
                UPDATE Client SET Username = ?, Name = ?, email = ?
                WHERE ClientID = ?
            ');

            $stmt->execute(array($this->username, $this->name, $this->email, $this->id));
        }

        function savePassword($db) {
            $stmt = $db->prepare('
                UPDATE Client SET Password = ?
            ');

            $stmt->execute(array($this->password));
        }

        function saveRole($db) {
            $stmt = $db->prepare('
                UPDATE Client SET Role = ?
                WHERE ClientID = ?
            ');

            $stmt->execute(array($this->role, $this->id));
        }

        static function deleteClient($db, int $id) : bool {
            try {
                $stmt = $db->prepare('
                    DELETE FROM Client WHERE ClientID = ?
                ');

                if ($stmt->execute(array($id))) return true;
                return false;

            } catch (PDOException $e) {
                return false;
            }
        }
    }
?>
