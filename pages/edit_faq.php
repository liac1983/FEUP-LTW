<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(header("Location: /"));

  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/css.tpl.php');
  require_once(__DIR__ . '/../templates/faq.tpl.php');
  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/clientClass.php');
  require_once(__DIR__ . '/../database/faqClass.php');

  $db = getDatabaseConnection();

  $faqID = intval($_GET['faq_id']);
  $faq = Faq::getFaq($db, $faqID);

  drawHeader();
  drawCSSStyle();
  drawCSSForm();
  drawUserLoggedIn($session);
  drawAside($session);
  drawEditFaq($faq);
  drawFooter();
?>