<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn() || $_SESSION['csrf'] !== $_POST['csrf'] || 
            $session->getRole() !== Roles::Admin->value) die(header("Location: /"));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/departmentClass.php');
    require_once(__DIR__ . '/../database/agentClass.php');

    $db = getDatabaseConnection();

    if ($_POST['agent_remove'] !== null && trim($_POST['agent_remove']) !== '') {
        $agentID = Agent::getAgentFromClientUsername($db, $_POST['agent_remove']);
        $agentsDepartmnet = Agent::getAgentsFromDepartment($db, $_GET['department_name']);
        $flag = false;
        foreach ($agentsDepartmnet as $agent) {
            if ($agent->username === $_POST['agent_remove']) $flag = true;
        }
        
        if (!$flag) {
            $session->addMessage('error', 'Agent does not belong to department');
            die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
        }
        if ($agentID !== -1) {
            if (Department::removeAgent($db, $agentID, $_GET['department_name'])) {
                $session->addMessage('success', 'Agent removed successfully');
                header("Location:" . $_SERVER['HTTP_REFERER']. "");
            }
            else {
                $session->addMessage('error', 'Error removing agent from department');
            }
        }
        else {
            $session->addMessage('error', 'User is not an agent');
        }
    }
    else {
        $session->addMessage('error', 'Error, username can not be empty');
    }
    header("Location:" . $_SERVER['HTTP_REFERER']. "");
?>