<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn() || $_SESSION['csrf'] !== $_POST['csrf'] || 
            $session->getRole() !== Roles::Admin->value) die(header("Location: /"));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/agentClass.php');
    require_once(__DIR__ . '/../database/departmentClass.php');

    $db = getDatabaseConnection();

    if ($_POST['new_agent'] !== null && trim($_POST['new_agent']) !== '') {
        $agentID = Agent::getAgentFromClientUsername($db, $_POST['new_agent']);
        $agentsFromDepartment = Agent::getAgentsFromDepartment($db, $_GET['department_name']);

        foreach ($agentsFromDepartment as $agent) {
            if ($agent->username === $_POST['new_agent']) {
                $session->addMessage('error', 'Agent already belongs to department');
                die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
            }
        }
        if ($agentID !== -1) {
            if (Department::addAgent($db, $agentID, $_GET['department_name'])) {
                $session->addMessage('success', 'Agent added to department successfully');
            }
            else {
                $session->addMessage('error', 'Error adding agent to department');
            }
        }
        else {
            $session->addMessage('error', 'User is not an agent');
        }
    }
    else {
        $session->addMessage('error', 'Username can not be empty');
    }
    header("Location:" . $_SERVER['HTTP_REFERER']. "");
?>