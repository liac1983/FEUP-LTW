<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/departmentClass.php');

    if (!$session->isLoggedIn() || $_SESSION['csrf'] !== $_POST['csrf'] || 
            $session->getRole() !== Roles::Admin->value) die(header("Location: /"));


    $db = getDatabaseConnection();

    if ($_POST['name'] !== null && trim($_POST['name']) !== '' && $_POST['description'] !== null && trim($_POST['description']) !== '') {
        if (Department::createDepartment($db, $_POST['name'], $_POST['description'])) {
            $session->addMessage('success', 'Department created successfully');
            header("Location:../pages/department_page.php");
        }
        else {
            $session->addMessage('error', 'Error creating department');
            header("Location:" . $_SERVER['HTTP_REFERER']. "");
        }
    }
    else {
        $session->addMessage('error', 'Name and description can not be empty');
        header("Location:" . $_SERVER['HTTP_REFERER']. "");
    }   
?>