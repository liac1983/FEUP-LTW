<?php declare(strict_types = 1); ?>


<?php function drawHeader() { ?>
    <!DOCTYPE html>
    <html lang="en-US">
    <head>
        <title>SwiftTicket</title> 
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer">
        <script src="../javascript/agents.js" defer></script>
        <script src="../javascript/hashtags.js" defer></script>
        <script src="../javascript/delete.js" defer></script>
        <script src="../javascript/department_tickets.js" defer></script>
        <script src="../javascript/ticket.js" defer></script>
        <script src="../javascript/comments.js" defer></script>
        <script src="../javascript/users.js" defer></script>
        <script src="../javascript/sidebar.js" defer></script>
    </head>
    <body>
        <header class="header">
            <div class="header_logo">
            <a href = "../index.php"><img src="../images/logoFinal2.png" alt="logotipo swiftticket" class="header_img_logo"></a>

            </div>
<?php } ?>


<?php function drawUserLoggedIn(Session $session) { ?>
    <div class="header_user">
        <h1 class="header_username"><?php echo htmlentities($session->getUsername())?></h1>
        <a href ="../pages/profile.php?user_id=<?php echo htmlentities(strval($session->getID()))?>"><img src="../images/default.png" alt="user profile picture" class="header_pfp"></a>
    </div>  
<?php } ?>


<?php function drawUserNotLoggedIn() { ?>
    <div class="header_user">
        <a href="../pages/login.php" class="loginSignup">Login</a>
        <a href="../pages/signup.php" class="loginSignup">Sign Up</a>
    </div>
<?php } ?>


<?php function drawAside(Session $session) { ?>
    </header>
        <aside class="sidebar">
            <nav class="sidebar_nav">
                <ul class="sidebar_list">
                    <?php if ($session->isLoggedIn()) { ?>
                        <li class="sidebar_link_container"><a class="sidebar_link" href="../pages/ticket_page.php">Ticket Page</a></li>
                        <li class="sidebar_link_container"><a class="sidebar_link" href="../pages/department_page.php">Departments</a></li>
                    <?php } ?>    
                    <li class="sidebar_link_container"><a class="sidebar_link" href="../pages/faq_page.php">FAQ's Page</a></li>
                    <?php if ($session->isLoggedIn() && $session->getRole() === Roles::Admin->value) { ?>
                        <li class="sidebar_link_container"><a class="sidebar_link" href="../pages/users.php">Users</a></li>
                    <?php } ?>
                    <?php if ($session->isLoggedIn()) { ?>
                        <li><a href="../actions/action_logout.php">Logout</a></li>
                    <?php } ?>    
                </ul>
            </nav>
        </aside>

        <div id="messages">
            <?php foreach ($session->getMessages() as $messsage) { ?>
                <article class="<?=$messsage['type']?>">
                    <?php echo htmlentities($messsage['text'])?>
                </article>
            <?php } $session->clearMessages(); ?>
        </div>
<?php } ?>


<?php function drawFooter() { ?>
        <footer class="footer">
            <p>Project developed for LTW by Henrique Silva, Lara Cunha and Nuno França</p>
        </footer>
    </body>
</html>
<?php } ?>
