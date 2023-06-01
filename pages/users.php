<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn() || $session->getRole() !== Roles::Admin->value) die(header("Location: /"));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/css.tpl.php');
    require_once(__DIR__ . '/../templates/users.tpl.php');
    require_once(__DIR__ . '/../database/clientClass.php');

    $db = getDatabaseConnection();

    $users = Client::getAllClients($db);

    drawHeader();
    drawCSSStyle();
    drawCSSDepartment_index();
    drawUserLoggedIn($session);
    drawAside($session);
    drawUsers($users);
    drawFooter();
?>