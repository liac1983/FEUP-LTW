<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(header("Location: /"));

  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/css.tpl.php');
  require_once(__DIR__ . '/../templates/faq.tpl.php');

  drawHeader();
  drawCSSStyle();
  drawCSSForm();
  drawUserLoggedIn($session); //if user not logged drawUserNotLoggedin
  drawAside($session);
  drawAddFAQ('Create New FAQ');
  drawFooter();
?>





