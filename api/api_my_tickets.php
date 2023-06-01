<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) die(header("Location: /"));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/ticketClass.php');

    $department = $_GET['department'];
    $priority = $_GET['priority'];
    $status = $_GET['status'];

    if ($department === 'all') $department = null;
    if ($priority === 'all') $priority = null;
    if ($status === 'all') $status = null;

    if ($priority === 'high') $priority = 1;
    if ($priority === 'medium') $priority = 2; 
    if ($priority === 'low') $priority = 3;

    if ($status === 'open') $status = 1;
    if ($status === 'pending') $status = 2;
    if ($status === 'closed') $status = 3;

    $db = getDatabaseConnection();

    $tickets = Ticket::getTicketsFromUser($db, $session->getID(), $department, $priority, $status);
    echo json_encode($tickets); 
?>