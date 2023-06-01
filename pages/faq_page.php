<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/faqClass.php');

  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/loginSignup.tpl.php');
  require_once(__DIR__ . '/../templates/css.tpl.php');
  require_once(__DIR__ . '/../templates/faq.tpl.php');

  $db = getDatabaseConnection();

  $faqs = Faq::getAllFAQs($db);

  drawHeader();
  drawCSSStyle();
  drawCSSFaqPage();
  if ($session->isLoggedIn()) drawUserLoggedIn($session);
  else drawUserNotLoggedIn();
  drawAside($session);
  drawFaqs($session, $faqs);
  drawFooter();
?>

