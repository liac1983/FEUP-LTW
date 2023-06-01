<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn() || $_SESSION['csrf'] !== $_POST['csrf'] || 
            $session->getRole() !== Roles::Admin->value) die(header("Location: /"));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/clientClass.php');
    require_once(__DIR__ . '/../database/adminClass.php');
    require_once(__DIR__ . '/../database/agentClass.php');

    $db = getDatabaseConnection();

    $user_id = intval($_GET['user_id']);
    $new_role = $_POST['role'];
    $user = Client::getClient($db, $user_id);

    if ($new_role === 'Client' && $user->role === Roles::Client->value) {
        $session->addMessage('error', 'User is already a client');
        die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
    }

    if ($new_role === 'Agent' && $user->role === Roles::Agent->value) {
        $session->addMessage('error', 'User is already an agent');
        die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
    }

    if ($new_role === 'Admin' && $user->role === Roles::Admin->value) {
        $session->addMessage('error', 'User is already an admin');
        die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
    }

    if ($new_role !== null && trim($new_role) !== '') {
        if ($new_role === 'Client' || $new_role === 'Agent' || $new_role === 'Admin') {
            if ($new_role === 'Client') {
                if (Agent::removeAgent($db, $user->id)) {
                    $user->role = Roles::Client->value;
                    $user->saveRole($db);
                    $session->addMessage('success', 'Role updated successfully');
                }
                else $session->addMessage('error', 'Error updating role');
            }

            if ($new_role === 'Agent') {
                // old role -> client
                if ($user->role === Roles::Client->value) {
                    if ($id = Agent::addAgent($db, $user->id)) $session->addMessage('success', 'Role updated successfully');
                    else $session->addMessage('error', 'Error updating role');
                }

                // old role -> admin
                if ($user->role === Roles::Admin->value) {
                    if (Admin::removeAdmin($db, Agent::getAgentIDFromClientID($db, $user->id))) $session->addMessage('success', 'Role updated successfully');
                    else $session->addMessage('error', 'Error updating role');
                }

                $user->role = Roles::Agent->value;
                $user->saveRole($db);
            }

            if ($new_role === 'Admin') {
                $agent_id = -2;
                if ($user->role === Roles::Client->value) {
                    $agent_id = Agent::addAgent($db, $user->id);
                    if ($agent_id === -1) {
                        $session->addMessage('error', 'Error updating role');
                        die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
                    }
                }

                if ($agent_id === -2) $agent_id = Admin::getAgentIDFromClientID($db, $user->id);
                if (Admin::addAdmin($db, $agent_id)) {
                    $user->role = Roles::Admin->value;
                    $user->saveRole($db);
                    $session->addMessage('success', 'Role updated successfully');
                }
                else $session->addMessage('error', 'Error updating role');
            }

        }
        else {
            $session->addMessage('error', 'Invalid role');
            die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
        }
    }
    else {
        $session->addMessage('error', 'Role can not be empty.');
    }

    header("Location:" . $_SERVER['HTTP_REFERER']. "");
?>