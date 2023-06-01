<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/hashtagClass.php');

    $db = getDatabaseConnection();

    $hashtags = Hashtag::searchHashtags($db, $_GET['search'], 8);

    echo json_encode($hashtags);
?>