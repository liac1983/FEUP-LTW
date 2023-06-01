<?php
    declare(strict_types = 1);

    require_once('agentClass.php');

    class Admin extends Agent {
        public int $adminID;
        
        public function __construct(int $id, string $username, string $name, string $password, string $email, string $pfp, int $agentID, int $adminID) {
            parent::__construct($id, $username, $name, $password, $email, $pfp, $agentID, Roles::Admin->value);
            $this->adminID = $adminID;
        }

        static function getAdminIDFromAgentID(PDO $db, int $id) : int {
            $stmt = $db->prepare('
                SELECT AdminID
                FROM Admin
                WHERE AgentID = ?
            ');

            $stmt->execute(array($id));

            if ($admin = $stmt->fetch()) {
                return $admin['AdminID'];
            }
            else {
                return -1;
            }
        }

        static function addAdmin(PDO $db, int $agentID) : int {
            try {
                $stmt = $db->prepare('
                    INSERT INTO Admin(AgentID) VALUES(?)
                ');

                $stmt->execute(array($agentID));
                
                if ($id = Admin::getAdminIDFromAgentID($db, $agentID)) return $id;
                return -1;
                
            } catch (PDOException $e) {
                return -1;
            }
        }

        static function removeAdmin(PDO $db, int $agentID) : bool {
            try {

                $stmt = $db->prepare('
                    DELETE FROM Admin WHERE AgentID = ?
                ');

                if ($stmt->execute(array($agentID))) return true;
                return false;

            } catch (PDOException $e) {
                return false;
            }
        }
    }
?>
