<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(header("Location: /"));

  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/css.tpl.php');
  require_once(__DIR__ . '/../templates/ticket.tpl.php');
  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/clientClass.php');
  require_once(__DIR__ . '/../database/ticketClass.php');
  require_once(__DIR__ . '/../database/hashtagClass.php');
  require_once(__DIR__ . '/../database/departmentClass.php');

  $db = getDatabaseConnection();

  $ticketID = intval($_GET['ticket_id']);
  $ticket = Ticket::getTicket($db, $ticketID);
  $client = Client::getClient($db, $ticket->client);
  $hashtags = Hashtag::getHashtags($db, $ticket->id);
  $departments = Department::getAllDepartments($db);
  $agentUsername = '';

  if ($ticket->agent !== null) {
    $agent = Client::getClient($db, $ticket->agent);
    $agentUsername = $agent->username;
  }

  $hashtags_str = "";

  foreach ($hashtags as $hastag) {
    $hashtags_str .= $hastag->name . " ";
  }

  drawHeader();
  drawCSSStyle();
  drawCSSForm();
  drawUserLoggedIn($session);
  drawAside($session);
  drawEditTicket($ticket, $client->username, $hashtags_str, $agentUsername, $departments);
  drawFooter(); 
?>





