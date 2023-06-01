<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(header("Location: /"));

  require_once(__DIR__ . '/../templates/css.tpl.php');
  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/ticket.tpl.php');
  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/departmentClass.php');

  $db = getDatabaseConnection();

  $departments = Department::getAllDepartments($db);

  drawHeader();
  drawCSSStyle();
  drawCSSForm();
  drawUserLoggedIn($session);
  drawAside($session);
  drawAddTicket('Create New Ticket', $departments);
  drawFooter();
?>
