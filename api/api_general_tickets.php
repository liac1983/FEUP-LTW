<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) die(header("Location: /"));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/ticketClass.php');

    $db = getDatabaseConnection();

    $priority = $_GET['priority'];

    if ($priority === 'all') $priority = null;

    if ($priority === 'high') $priority = 1;
    if ($priority === 'medium') $priority = 2; 
    if ($priority === 'low') $priority = 3;

    $tickets = Ticket::getGeneralTickets($db, $priority);

    echo json_encode($tickets); 
?>