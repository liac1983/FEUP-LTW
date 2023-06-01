<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(header("Location: /"));

  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/department.tpl.php');
  require_once(__DIR__ . '/../templates/css.tpl.php');

  drawHeader();
  drawCSSStyle();
  drawCSSForm();
  if ($session->isLoggedIn()) drawUserLoggedIn($session); //if user not logged drawUserNotLoggedin
  else drawUserNotLoggedIn();
  drawAside($session);
  drawAddDepartment('SwiftTicket - Add Department');
  drawFooter();
?>



