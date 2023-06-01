<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn() || $_SESSION['csrf'] !== $_POST['csrf'] || 
            $session->getRole() !== Roles::Admin->value) die(header("Location: /"));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/departmentClass.php');

    $db = getDatabaseConnection();

    $departmentName = $_GET['department_name'];

    $department = Department::getDepartment($db, $departmentName);

    if ($department) {
        if ($_POST['name'] !== null && trim($_POST['name']) !== '' && $_POST['description'] !== null && trim($_POST['description']) !== '') {
            $department->name = $_POST['name'];
            $department->description = $_POST['description'];
            $department->save($db, $departmentName);
            $session->addMessage('success', 'Department updated successfully');
            header("Location:../pages/department_page.php");
        }
        else {
            $session->addMessage('error', 'Name and Description can not be empty');
            header("Location:" . $_SERVER['HTTP_REFERER']. "");
        }
    } 
?>