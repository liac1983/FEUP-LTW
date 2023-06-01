<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/agentClass.php');

    $db = getDatabaseConnection();

    $agents = Agent::searchAgentsDontBelongDepartment($db, $_GET['search'], $_GET['department_name'], 8);

    echo json_encode($agents);
?> 