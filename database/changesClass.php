<?php
    declare(strict_types = 1);

    class Changes {
        public int $id;
        public string $date;
        public string $content;
        public string $username;
        public int $ticket;
        
        public function __construct(int $id, string $date, string $content, string $username, int $ticket) {
            $this->id = $id;
            $this->date = $date;
            $this->content = $content;
            $this->username = $username;
            $this->ticket = $ticket;
        }

        static function getChangesFromTicket(PDO $db, int $ticket) : array {
            $stmt = $db->prepare('
                SELECT ChangesID, Date, Content, Username, TicketID
                FROM Changes
                WHERE TicketID = ?
            ');

            $stmt->execute(array($ticket));

            $changes = array();

            while ($change = $stmt->fetch()) {
                $changes[] = new Changes(
                    $change['ChangesID'],
                    $change['Date'],
                    $change['Content'],
                    $change['Username'],
                    $change['TicketID']
                );
            }

            return $changes;
        }

        static function getChangeID(PDO $db, int $ticket, string $username, string $date, string $content) : int {
            $stmt = $db->prepare('
                SELECT ChangesID
                FROM Changes
                WHERE Date = ? AND Content = ? AND Username = ? and TicketID = ?
            ');

            $stmt->execute(array($date, $content, $username, $ticket));
            $id = $stmt->fetch();
            return $id['ChangesID'];
        }

        static function createChange(PDO $db, int $ticket, string $username, string $date, $priority, $department, $status) {
            $content = '';

            if ($status === 1) $status = 'Open';
            if ($status === 2) $status = 'Pending';
            if ($status === 3) $status = 'Closed';

            if ($priority === 1) $priority = 'High';
            if ($priority === 2) $priority = 'Medium';
            if ($priority === 3) $priority = 'Low';

            if ($priority) $content .= 'Priority changed to: ' . $priority . '<br>'; 
            if ($department) $content .= 'Department changed to: ' . $department . '<br>';
            if ($status) $content .= 'Status changed to: ' . $status . '<br>';

            $stmt = $db->prepare('
                INSERT INTO Changes(Date, Content, Username, TicketID) VALUES (?, ?, ?, ?)
            ');

            if ($stmt->execute(array($date, $content, $username, $ticket))) {
                $id = Changes::getChangeID($db, $ticket, $username, $date, $content);
                return $id;
            }
            else {
                return -1;
            }
        }

        static function createNewTicketChange(PDO $db, int $ticket, string $username, string $date) : int {
            try {

                $content = 'Ticket created.';

                $stmt = $db->prepare('
                    INSERT INTO Changes(Date, Content, Username, TicketID) VALUES(?, ?, ?, ?)
                ');

                if ($stmt->execute(array($date, $content, $username, $ticket))) {
                    $id = Changes::getChangeID($db, $ticket, $username, $date, $content);
                    return $id;
                }

                return -1;

            } catch (PDOException $e) {
                return -1;
            }
        }

        static function createAgentChange(PDO $db, int $ticket, string $username, string $date) : int {
            try {

                $content = 'New agent assigned: ' . $username;

                $stmt = $db->prepare('
                    INSERT INTO Changes(Date, Content, Username, TicketID) VALUES (?, ?, ?, ?)
                ');

                if ($stmt->execute(array($date, $content, $username, $ticket))) {
                    $id = Changes::getChangeID($db, $ticket, $username, $date, $content);
                    return $id;
                }

                return -1;

            } catch (PDOException $e) {
                return -1;
            }
        }
    }
?>
