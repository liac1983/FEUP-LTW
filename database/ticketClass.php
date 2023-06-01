<?php
    declare(strict_types = 1);

    class Ticket {
        public int $id;
        public string $title;
        public string $description;
        public string $date;
        public int $status;
        public int $priority;
        public string $category;
        public string $department;
        public int $client;
        public int $clientTrack;
        public int $agentTrack;
        public $agent;
        
        public function __construct(int $id, string $title, string $description, string $date, int $status, int $priority, 
                                        string $category, string $department, int $client, $agent, int $clientTrack, int $agentTrack) {
            $this->id = $id;
            $this->title = $title;
            $this->description = $description;
            $this->date = $date;
            $this->status = $status;
            $this->priority = $priority;
            $this->category = $category;
            $this->department = $department;
            $this->client = $client;
            $this->agent = $agent;
            $this->clientTrack = $clientTrack;
            $this->agentTrack = $agentTrack;
        }

        static function getTicket(PDO $db, int $ticketID) : Ticket {
            $stmt = $db->prepare('
                SELECT TicketID, Title, Description, Date, Status, Priority, Category, departmentName, ClientID, AgentID, ClientTrack, AgentTrack
                FROM Ticket
                WHERE TicketID = ?
            ');

            $stmt->execute(array($ticketID));

            $ticket = $stmt->fetch();

            return new Ticket(
                $ticket['TicketID'],
                $ticket['Title'],
                $ticket['Description'],
                $ticket['Date'],
                $ticket['Status'],
                $ticket['Priority'],
                $ticket['Category'],
                $ticket['departmentName'],
                $ticket['ClientID'],
                $ticket['AgentID'],
                $ticket['ClientTrack'],
                $ticket['AgentTrack']
            );
        }

        static function getTicketsFromUser(PDO $db, int $id, $department, $priority, $status) : array {
            $stmt = $db->prepare('
                SELECT TicketID, Title, Description, Date, Status, Priority, Category, departmentName, ClientID, AgentID, ClientTrack, AgentTrack
                FROM Ticket
                WHERE ClientID = ? AND (departmentName = ? OR ? IS NULL) AND (Status = ? OR ? IS NULL) AND (Priority = ? OR ? IS NULL)
                ORDER BY ClientTrack DESC, Priority ASC
            ');

            $stmt->execute(array($id, $department, $department, $status, $status, $priority, $priority));

            $tickets = array();

            while ($ticket = $stmt->fetch()) {
                $tickets[] = new Ticket(
                    $ticket['TicketID'],
                    $ticket['Title'],
                    $ticket['Description'],
                    $ticket['Date'],
                    $ticket['Status'],
                    $ticket['Priority'],
                    $ticket['Category'],
                    $ticket['departmentName'],
                    $ticket['ClientID'],
                    $ticket['AgentID'],
                    $ticket['ClientTrack'],
                    $ticket['AgentTrack']
                );
            }

            return $tickets;
        }

        static function getAgentTickets(PDO $db, int $agent, $department, $priority, $status) : array {
            $stmt = $db->prepare('
                SELECT TicketID, Title, Description, Date, Status, Priority, Category, departmentName, ClientID, AgentID, ClientTrack, AgentTrack
                FROM Ticket
                WHERE AgentID = ? AND (departmentName = ? OR ? IS NULL) AND (Status = ? OR ? IS NULL) AND (Priority = ? OR ? IS NULL)
                ORDER BY AgentTrack DESC, Priority ASC
            ');

            $stmt->execute(array($agent, $department, $department, $status, $status, $priority, $priority));

            $tickets = array();

            while ($ticket = $stmt->fetch()) {
                $tickets[] = new Ticket(
                    $ticket['TicketID'],
                    $ticket['Title'],
                    $ticket['Description'],
                    $ticket['Date'],
                    $ticket['Status'],
                    $ticket['Priority'],
                    $ticket['Category'],
                    $ticket['departmentName'],
                    $ticket['ClientID'],
                    $ticket['AgentID'],
                    $ticket['ClientTrack'],
                    $ticket['AgentTrack']
                );
            }

            return $tickets;
        }

        static function getGeneralTickets(PDO $db, $priority) : array {
            $stmt = $db->prepare('
                SELECT *
                FROM Ticket
                WHERE departmentName = "General" AND (Priority = ? OR ? IS NULL)
                ORDER BY Priority ASC
            ');

            $stmt->execute(array($priority, $priority));

            $tickets = array();

            while ($ticket = $stmt->fetch()) {
                $tickets[] = new Ticket(
                    $ticket['TicketID'],
                    $ticket['Title'],
                    $ticket['Description'],
                    $ticket['Date'],
                    $ticket['Status'],
                    $ticket['Priority'],
                    $ticket['Category'],
                    $ticket['departmentName'],
                    $ticket['ClientID'],
                    $ticket['AgentID'],
                    $ticket['ClientTrack'],
                    $ticket['AgentTrack']
                );
            }

            return $tickets;
        }

        static function getTicketFromDepartment(PDO $db, string $department, $priority, $status) : array {
            $stmt = $db->prepare('
                SELECT TicketID, Title, Description, Date, Status, Priority, Category, departmentName, ClientID, AgentID, ClientTrack, AgentTrack
                FROM Ticket
                WHERE departmentName = ? AND (Priority = ? OR ? IS NULL) AND (Status = ? OR ? IS NULL)
                ORDER BY AgentID ASC, Priority ASC
            ');

            $stmt->execute(array($department, $priority, $priority, $status, $status));

            $tickets = array();

            while ($ticket = $stmt->fetch()) {
                $tickets[] = new Ticket(
                    $ticket['TicketID'],
                    $ticket['Title'],
                    $ticket['Description'],
                    $ticket['Date'],
                    $ticket['Status'],
                    $ticket['Priority'],
                    $ticket['Category'],
                    $ticket['departmentName'],
                    $ticket['ClientID'],
                    $ticket['AgentID'],
                    $ticket['ClientTrack'],
                    $ticket['AgentTrack']
                );
            }

            return $tickets;
        }

        static function getTicketFromTitleDescrDate(PDO $db, string $title, string $description, string $date) : int {
            $stmt = $db->prepare('
                SELECT TicketID
                FROM Ticket
                WHERE Title = ? AND Description = ? AND Date = ?
            ');

            $stmt->execute(array($title, $description, $date));
            $id = $stmt->fetch();
            return $id['TicketID'];
        }

        static function createTicket(PDO $db, string $title, string $description, string $date, string $category, string $department, int $client) {
            $stmt = $db->prepare('
                INSERT INTO Ticket(Title, Description, Date, Category, departmentName, ClientID) VALUES (?, ?, ?, ?, ?, ?)
            ');

            if ($stmt->execute(array($title, $description, $date, $category, $department, $client))) {
                $id = Ticket::getTicketFromTitleDescrDate($db, $title, $description, $date);
                return $id;
            }
            else {
                return -1;
            }
        }

        static function trackTicket(PDO $db, int $ticketID, int $user) : bool {
            if ($user === Roles::Client->value) {
                
                $stmt = $db->prepare('
                    UPDATE Ticket SET ClientTrack = ?
                    WHERE TicketID = ?
                ');

            }
            else {
            
                $stmt = $db->prepare('
                    UPDATE Ticket SET AgentTrack = ?
                    WHERE TicketID = ?
                '); 

            }

            try {
                $stmt->execute(array(1, $ticketID));
                return true;

            } catch (PDOException $e) {
                return false;
            }
        }

        static function untrackTicket(PDO $db, int $ticketID, int $user) : bool {
            if ($user === Roles::Client->value) {

                $stmt = $db->prepare('
                    UPDATE Ticket SET ClientTrack = ?
                    WHERE TicketID = ?
                ');
            }
            else {

                $stmt = $db->prepare('
                    UPDATE Ticket SET AgentTrack = ?
                    WHERE TicketID = ?
                ');
            }

            try {
                $stmt->execute(array(0, $ticketID));
                return true;
            } catch (PDOException $e) {
                return false;
            }
        }

        function save($db) {
            $stmt = $db->prepare('
                UPDATE Ticket SET departmentName = ?, Status = ?, Priority = ?
                WHERE TicketID = ?
            ');

            $stmt->execute(array($this->department, $this->status, $this->priority, $this->id));
        }

        function saveAgent($db) {
            $stmt = $db->prepare('
                UPDATE Ticket SET AgentID = ? WHERE TicketID = ?
            ');

            $stmt->execute(array($this->agent, $this->id));
        }
    }
?>