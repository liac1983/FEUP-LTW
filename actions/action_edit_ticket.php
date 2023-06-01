<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn() || $_SESSION['csrf'] !== $_POST['csrf'] || 
            $session->getRole() === Roles::Client->value) die(header("Location: /"));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/ticketClass.php');
    require_once(__DIR__ . '/../database/hashtagClass.php');
    require_once(__DIR__ . '/../database/clientClass.php');
    require_once(__DIR__ . '/../database/changesClass.php');
    require_once(__DIR__ . '/../database/agentClass.php');

    $db = getDatabaseConnection();

    $ticketID = intval($_GET['ticket_id']);

    $ticket = Ticket::getTicket($db, $ticketID);

    $date = new DateTime("now", new DateTimeZone('Europe/Lisbon'));
    $date_str = $date->format('d-m-Y H:i:s');

    if ($ticket) {
        if ($_POST['status'] !== null && trim($_POST['status']) !== ''
            && $_POST['priority'] !== null && trim($_POST['priority']) !== '') {
            $status;

            switch ($_POST['status']) {
                case 'Open':
                    $status = 1; break;
                
                case 'Pending':
                    $status = 2; break;
        
                case 'Closed':
                    $status = 3; break;  
        
                default:
                    $session->addMessage('error', 'Invalid ticket status');
                    die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
                    break;
            }

            if (trim($_POST['agent']) !== '') {
                if (is_numeric($_POST['agent'])) {
                    $session->addMessage('error', 'Agent box must be given an username');
                    die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
                }

                if (trim($_POST['department']) === '' || $_POST['department'] === '---') {
                    $session->addMessage('error', 'Agent does not belong to selected department');
                    die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
                }

                $user = Client::getClientFromUsername($db, $_POST['agent']);
                $agentsDepartment = Agent::getAgentsFromDepartment($db, $_POST['department']);

                $agentBelongsToDepartment = false;

                foreach ($agentsDepartment as $agent) {
                    if ($agent->id === $user->id) $agentBelongsToDepartment = true;
                }

                if ($user->role !== Roles::Admin->value) {
                    if (!$agentBelongsToDepartment) {
                        $session->addMessage('error', 'Agent does not belong to selected department');
                        die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
                    }
                }    

                if ($user->role !== Roles::Client->value && $user !== null) {
                    if ($user->id !== $ticket->agent) {
                        $ticket->agent = $user->id;
                        $agentLog = Changes::createAgentChange($db, $ticket->id, $user->username, $date_str);
                        if ($ticket->status === 1) {
                            if ($status === 1) $status = 2;
                            Changes::createChange($db, $ticket->id, $session->getUsername(), $date_str, null, null, 2);
                        }
                        $ticket->saveAgent($db);
                    }
                }
                else {
                    $session->addMessage('error', 'Username provided does not belong to an agent');
                }
            }
            
            if ($_POST['hashtag'] !== null) {
                $hashtags = explode(" ", $_POST['hashtag']);
                
                if (!Hashtag::deleteTicketHashtags($db, $ticket->id)) {
                    $session->addMessage('error', 'Error updating ticket');
                    die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
                }

                foreach ($hashtags as $hashtag) {
                    if (!Hashtag::hashtagExists($db, $hashtag)) {
                        Hashtag::createHashtag($db, $hashtag);
                    }
                    Hashtag::createHashtagTicket($db, $hashtag, $ticket->id);
                }
            }

            $priority;
            if ($_POST['priority'] === 'Low') $priority = 3;
            else if ($_POST['priority'] === 'Medium') $priority = 2;
            else if ($_POST['priority'] === 'High') $priority = 1;
            else {
                $session->addMessage('error', 'Invalid priority');
                die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
            }   

            $priority_log = null;
            $department_log = null;
            $status_log = null;

            $department = $_POST['department'];
            if ($department === '---') $department = 'General';

            if ($ticket->priority !== $priority) $priority_log = $priority;
            if ($ticket->department !== $department) $department_log = $department;
            if ($ticket->status !== $status) $status_log = $status;

            Changes::createChange($db, $ticket->id, $session->getUsername(), $date_str, $priority_log, $department_log, $status_log);

            $ticket->priority = $priority;
            $ticket->department = $department;
            $ticket->status = $status;
            $ticket->save($db);
            $session->addMessage('success', 'Ticket successfully updated');
            header("Location:" . $_SERVER['HTTP_REFERER']. "");
        }
        else {
            $session->addMessage('error', 'Department, Status and Priority can not be empty');
            header("Location:" . $_SERVER['HTTP_REFERER']. "");
        }
    }  
?>