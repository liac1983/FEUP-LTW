<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) die(header("Location: /"));

    require_once(__DIR__ . '/../templates/css.tpl.php');
    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/ticket.tpl.php');
    require_once(__DIR__ . '/../database/ticketClass.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/departmentClass.php');

    $db = getDatabaseConnection();

    $tickets = Ticket::getTicketsFromUser($db, $session->getID(), null, null, null);

    $departments = Department::getAllDepartments($db);

    drawHeader();
    drawCSSStyle();
    drawCSSDepartment_index();
    if ($session->isLoggedIn()) drawUserLoggedIn($session); 
    else drawUserNotLoggedIn();
    drawAside($session);
    drawTicketPage($session, $tickets, $departments);
    drawFooter();
?>

