<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();
    
    if (!$session->isLoggedIn()) die(header("Location: /"));
    
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/css.tpl.php');
    require_once(__DIR__ . '/../templates/profile.tpl.php');
    require_once(__DIR__ . '/../database/clientClass.php');
    require_once(__DIR__ . '/../database/agentClass.php');
    require_once(__DIR__ . '/../database/adminClass.php');

    $db = getDatabaseConnection();

    $user = Client::getClient($db, intval($_GET['user_id']));

    $role = "";
    if ($user->role === Roles::Client->value) $role = 'Client';
    if ($user->role === Roles::Agent->value) $role = 'Agent';
    if ($user->role === Roles::Admin->value) $role = 'Admin';

    drawHeader();
    drawCSSStyle();
    drawCSSForm();
    drawUserLoggedIn($session); //if user not logged drawUserNotLoggedin
    drawAside($session);
    drawProfile($session, $user, $role);
    drawFooter();
?>