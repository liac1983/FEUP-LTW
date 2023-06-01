<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn() || $_SESSION['csrf'] !== $_POST['csrf'] || 
            $session->getRole() !== Roles::Admin->value) die(header("Location: /"));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/departmentClass.php');

    $db = getDatabaseConnection();

    $department_name = $_GET['department_name'];

    if (Department::deleteDepartment($db, $department_name)) {
        $session->addMessage('success', 'Department deleted successfully');
    }
    else {
        $session->addMessage('error', 'Error deleting department');
        die(header("Location:" . $_SERVER['HTTP_REFERER']. ""));
    }

    header("Location:../pages/department_page.php");
?>