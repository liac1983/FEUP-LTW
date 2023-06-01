<?php
    declare(strict_types = 1);

    class Message {
        public int $messageID;
        public int $ticketID;
        public int $userID;
        public string $date;
        public string $content;
        
        public function __construct(int $messageID, int $ticketID, string $date, int $userID, string $content) {
            $this->messageID = $messageID;
            $this->ticketID = $ticketID;
            $this->userID = $userID;
            $this->date = $date;
            $this->content = $content;
        }

        static function getMessagesFromTicket(PDO $db, int $ticket) : array {
            $stmt = $db->prepare('
                SELECT MessageID, TicketID, Date, ClientID, Content
                FROM Message
                WHERE TicketID = ?
            ');

            $stmt->execute(array($ticket));

            $messages = array();

            while ($message = $stmt->fetch()) {
                $messages[] = new Message(
                    $message['MessageID'],
                    $message['TicketID'],
                    $message['Date'],
                    $message['ClientID'],
                    $message['Content']
                );
            }

            return $messages;
        }

        static function getSpecificMessage(PDO $db, int $ticket, int $user, string $date, string $content) : int {
            $stmt = $db->prepare('
                SELECT MessageID
                FROM Message
                WHERE TicketID = ? AND Date = ? AND ClientID = ? AND Content = ?
            ');

            $stmt->execute(array($ticket, $date, $user, $content));
            $id = $stmt->fetch();
            return $id['MessageID'];
        }

        static function createMessage(PDO $db, int $ticket, int $user, string $date , string $content) {
            $stmt = $db->prepare('
                INSERT INTO Message(TicketID, Date, ClientID, Content) VALUES (?, ?, ?, ?)
            ');

            if ($stmt->execute(array($ticket, $date, $user, $content))) {
                $id = Message::getSpecificMessage($db, $ticket, $user, $date, $content);
                return $id;
            }
            else {
                return -1;
            }
        }

        static function searchMessage(PDO $db, int $ticket) : array {
            $stmt = $db->prepare('
                SELECT Username, Content, Date, C.ClientID
                FROM Message as M, Client as C
                Where M.ClientID = C.ClientID AND TicketID = ?
            ');

            $stmt->execute(array($ticket));

            $messages = array();

            while ($message = $stmt->fetch()) {
                $messages[] = array(
                    'username' => $message['Username'],
                    'content' => $message['Content'],
                    'date' => $message['Date'],
                    'userID' => $message['ClientID']
                );
            }

            return $messages;
        }
    }
?>
