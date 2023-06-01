<?php
    declare(strict_types = 1);

    class Faq {
        public int $id;
        public string $title;
        public string $content;
        
        public function __construct(int $id, string $title, string $content) {
            $this->id = $id;
            $this->title = $title;
            $this->content = $content;
        }

        static function getAllFAQs(PDO $db) : array {
            $stmt = $db->prepare('
                SELECT FaqID, Title, Content
                FROM FAQ
            ');

            $stmt->execute(array());

            $faqs = array();

            while ($faq = $stmt->fetch()) {
                $faqs[] = new Faq(
                    $faq['FaqID'],
                    $faq['Title'],
                    $faq['Content']
                );
            }
            
            return $faqs;
        }

        static function getFaq(PDO $db, int $id) : ?Faq {
            $stmt = $db->prepare('
                SELECT FaqID, Title, Content
                FROM FAQ
                WHERE FaqID = ?
            ');

            $stmt->execute(array($id));
            $faq = $stmt->fetch();
            return new Faq(
                $faq['FaqID'],
                $faq['Title'],
                $faq['Content']
            );
        }

        static function getFaqFromTitleContent(PDO $db, string $title, string $content) : int {
            $stmt = $db->prepare('
                SELECT FaqID
                FROM FAQ
                WHERE Title = ? AND Content = ?
            ');

            $stmt->execute(array($title, $content));

            $faq = $stmt->fetch();

            return $faq['FaqID'];
        }

        static function createFaq(PDO $db, string $title, string $content) {
            $stmt = $db->prepare('
                INSERT INTO Faq(Title, Content) VALUES (?, ?)
            ');

            if ($stmt->execute(array($title, $content))) {
                $id = Faq::getFaqFromTitleContent($db, $title, $content);
                return $id;
            }
            else {
                return -1;
            }
        }

        function save(PDO $db) {
            $stmt = $db->prepare('
                UPDATE FAQ SET Title = ?, Content = ?
                WHERE FaqID = ?
            ');

            $stmt->execute(array($this->title, $this->content, $this->id));
        }

        static function deleteFaq(PDO $db, int $faq) : bool {
            try {
                $stmt = $db->prepare('
                    DELETE FROM FAQ WHERE FaqID = ?
                ');

                if ($stmt->execute(array($faq))) return true;
                return false;

            } catch (PDOException $e) {
                return false;
            }    
        }
    }
?>
