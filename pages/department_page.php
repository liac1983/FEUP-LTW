<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(header("Location: /"));

  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/departmentClass.php');

  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/css.tpl.php');
  require_once(__DIR__ . '/../templates/department.tpl.php');

  $db = getDatabaseConnection();

  $departments = Department::getAllDepartments($db);

  drawHeader();
  drawCSSStyle();
  drawCSSDepartment_index();
  drawUserLoggedIn($session);
  drawAside($session);
  drawDepartmentPage($session, 'Departments', $departments);
  drawFooter(); 
?>
