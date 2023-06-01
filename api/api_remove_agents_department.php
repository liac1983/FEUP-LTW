<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) die(header("Location: /"));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/agentClass.php');

    $db = getDatabaseConnection();

    $agents = Agent::getAgentsFromDepartmentDynamic($db, $_GET['search'], $_GET['department_name']);

    echo json_encode($agents);
?>