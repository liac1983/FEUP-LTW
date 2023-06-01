<?php
    declare(strict_types = 1);

    class Hashtag {
        public string $name;

        
        public function __construct(string $name) {
            $this->name = $name;
        }

        static function hashtagExists(PDO $db, string $hashtag) : bool {
            $stmt = $db->prepare('
                SELECT Name
                FROM Hashtag
                WHERE Name = ?
            ');

            $stmt->execute(array($hashtag));

            if ($stmt->fetch()) {
                return true;
            }
            return false;
        }

        static function createHashtag(PDO $db, string $hashtag) : bool {
            $stmt = $db->prepare('
                INSERT INTO Hashtag(Name) VALUES (?)
            ');

            if ($stmt->execute(array($hashtag))) {
                return true;
            }
            else {
                return false;
            }
        }

        static function createHashtagTicket(PDO $db, string $hashtag, int $ticket) {
            $stmt = $db->prepare('
                INSERT INTO TicketHashtag(TicketID, HashtagName) VALUES (?, ?)
            ');

            $stmt->execute(array($ticket, $hashtag));
        }

        static function getHashtags(PDO $db, int $ticket) : array {
            $stmt = $db->prepare('
                SELECT HashtagName
                FROM TicketHashtag
                WHERE TicketID = ?
            ');

            $stmt->execute(array($ticket));

            $hashtags = array();
            while ($hashtag = $stmt->fetch()) {
                $hashtags[] = new Hashtag ($hashtag['HashtagName']);
            }

            return $hashtags;
        }

        static function searchHashtags(PDO $db, string $search, int $count) : array {
            $stmt = $db->prepare('
                SELECT Name
                FROM Hashtag
                WHERE Name LIKE ? LIMIT ?
            ');

            $stmt->execute(array($search . '%', $count));
            $hashtags = array();

            while ($hashtag = $stmt->fetch()) {
                $hashtags[] = new Hashtag($hashtag['Name']);
            }

            return $hashtags;
        }

        static function deleteTicketHashtags(PDO $db, int $id) : bool {
            try {
                $stmt = $db->prepare('
                    DELETE FROM TicketHashtag WHERE TicketID = ?
                ');

                $stmt->execute(array($id));

                return true;
            } catch (PDOException $e) {
                return false;
            }
        }
    }
?>
