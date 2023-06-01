<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/css.tpl.php');
  require_once(__DIR__ . '/../templates/loginSignup.tpl.php');
    
  drawHeader();
  drawCSSLoginSignUp();
  drawRegister();
  drawFooter();
?>




