<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/clientClass.php');

    class Agent extends Client {
        public int $agentID;

        public function __construct(int $id, string $username, string $name, string $password, string $email, string $pfp, int $agentID, int $role) {
            parent::__construct($id, $username, $name, $password, $email, $pfp, $role);
            $this->agentID = $agentID;
        }

        static function getAgentsFromDepartment(PDO $db, string $department) : array {
            $stmt = $db->prepare('
                SELECT Client.ClientID, Username, Name, Password, Email, Photo, Agent.AgentID, Role
                FROM Client, Agent, AgentDepartment as AD
                WHERE Client.ClientID = Agent.ClientID AND Agent.AgentID = AD.AgentID AND AD.departmentName = ?
            ');

            $stmt->execute(array($department));

            $agents = array();

            while ($agent = $stmt->fetch()) {
                $agents[] = new Agent(
                    $agent['ClientID'],
                    $agent['Username'],
                    $agent['Name'],
                    $agent['Password'],
                    $agent['Email'],
                    $agent['Photo'],
                    $agent['AgentID'],
                    $agent['Role']
                );
            }

            return $agents;
        }

        static function getAgentIDFromClientID(PDO $db, int $id) : int {
            $stmt = $db->prepare('
                SELECT AgentID
                FROM Agent
                WHERE ClientID = ?
            ');

            $stmt->execute(array($id));

            if ($agent = $stmt->fetch()) {
                return $agent['AgentID'];
            }
            else {
                return -1;
            }
        }

        static function getAgentFromClientUsername(PDO $db, string $username) : int {
            $stmt = $db->prepare('
                SELECT AgentID
                FROM Agent, Client
                WHERE Agent.ClientID = Client.ClientID AND Client.Username = ?
            ');

            $stmt->execute(array($username));

            if ($agent = $stmt->fetch()) {
                return $agent['AgentID'];
            }
            else {
                return -1;
            }
        }

        static function searchAgentsDontBelongDepartment(PDO $db, string $search, string $department, int $count) : array {
            $stmt = $db->prepare('
                SELECT DISTINCT Username
                FROM Agent, Client
                WHERE Agent.ClientID = Client.ClientID AND Client.Username NOT IN (
                    SELECT DISTINCT Username
                    FROM Agent, Client, AgentDepartment as AD
                    WHERE Agent.ClientID = Client.ClientID AND AD.AgentID = Agent.AgentID AND AD.departmentName = ?)
                AND Client.Username LIKE ? LIMIT ?
            ');

            $stmt->execute(array($department, $search . '%', $count));
            $agents = array();

            while ($agent = $stmt->fetch()) {
                $agents[] = $agent;
            }

            return $agents;
        }

        static function getAgentsFromDepartmentDynamic(PDO $db, string $search, string $department) : array {
            $stmt = $db->prepare('
                SELECT Username
                FROM Agent, Client, AgentDepartment as AD
                WHERE Agent.ClientID = Client.ClientID AND AD.AgentID = Agent.AgentID AND AD.departmentName = ? AND Client.Username LIKE ?
            ');

            $stmt->execute(array($department, $search . '%'));
            $agents = array();

            while ($agent = $stmt->fetch()) {
                $agents[] = $agent;
            }

            return $agents;
        }

        static function removeAgent(PDO $db, int $user) : bool {
            try {

                $stmt = $db->prepare('
                    DELETE FROM Agent WHERE ClientID = ?
                ');

                if ($stmt->execute(array($user))) return true;
                return false;

            } catch (PDOExcpetion $e) {
                return false;
            }
        }

        static function addAgent(PDO $db, int $user) : int {
            try {

                $stmt = $db->prepare('
                    INSERT INTO Agent(ClientID) VALUES (?)
                ');

                if ($stmt = $stmt->execute(array($user))) {
                    $id = Agent::getAgentIDFromClientID($db, $user);
                    return $id;
                }
                else {
                    return -1;
                }

            } catch (PDOExpection $e) {
                return -1;
            }
        }
    }
?>
