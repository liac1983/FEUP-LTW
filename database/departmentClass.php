<?php
    declare(strict_types = 1);
    
    class Department {
        public string $name;
        public string $description;

        public function __construct(string $name, string $description) {
            $this->name = $name;
            $this->description = $description;
        }

        static function getAllDepartments(PDO $db) : array {
            $stmt = $db->prepare('
                SELECT departmentName, Description
                FROM Department
            ');

            $stmt->execute(array());

            $departments = array();

            while ($department = $stmt->fetch()) {
                $departments[] = new Department(
                    $department['departmentName'],
                    $department['Description']
                );
            }
            return $departments;
        }

        static function getDepartment(PDO $db, $departmentName) : Department {
            $stmt = $db->prepare('
                SELECT departmentName, Description
                FROM Department
                WHERE departmentName = ?
            ');

            $stmt->execute(array($departmentName));
            $department = $stmt->fetch();

            return new Department(
                $department['departmentName'],
                $department['Description']
            );
        }

        static function createDepartment(PDO $db, string $title, string $description) : bool {
            $stmt = $db->prepare('
                INSERT INTO Department(departmentName, Description) VALUES (?, ?)
            ');

            if ($stmt->execute(array($title, $description))) {
                return true;
            }
            else {
                return false;
            }
        }

        function save(PDO $db, string $department) {
            $stmt = $db->prepare('
                UPDATE Department SET departmentName = ?, Description = ?
                WHERE departmentName = ?
            ');

            $stmt->execute(array($this->name, $this->description, $department));
        }

        static function deleteDepartment(PDO $db, string $department) : bool {
            try {
                $stmt = $db->prepare('
                    DELETE FROM Department WHERE departmentName = ?
                ');

                if ($stmt->execute(array($department))) return true;
                return false;

            } catch (PDOException $e) {
                return false;
            }    
        }

        static function addAgent(PDO $db, int $agent, string $department) : bool {
            try {
                $stmt = $db->prepare('
                    INSERT INTO AgentDepartment(AgentID, departmentName) VALUES (?, ?)
                ');

                $stmt->execute(array($agent, $department));

                return true;
                
            } catch (PDOExcpetion $e) {
                return false;
            }
        }

        static function removeAgent(PDO $db, int $agent, string $department) : bool {
            try {
                $stmt = $db->prepare('
                    DELETE FROM AgentDepartment WHERE departmentName = ? AND AgentID = ?
                ');

                $stmt->execute(array($department, $agent));

                return true;
                
            } catch (PDOException $e) {
                return false;
            }
        }
    }
?>
