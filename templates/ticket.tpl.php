<?php declare(strict_types = 1); ?>

<?php function drawTicketPage(Session $session, array $tickets, array $departments) { ?>
    <main>
        <div id="page_container">
            <div class="page_header">
                <h1>Tickets</h1>
                <a href="../pages/add_ticket.php" class="new_add_button">New Ticket</a>
            </div>
            <div id="filter_page">
                <label class="filter">Department:
                    <select id="department_filter_name">
                        <option value="all">All</option>
                    <?php foreach ($departments as $department) { if ($department->name === 'General') continue; ?>
                        <option value="<?=htmlspecialchars($department->name, ENT_QUOTES)?>"><?php echo htmlentities($department->name)?></option>
                    <?php } ?>
                    </select>
                </label>
                <label class="filter">Priority:
                    <select id="priority_filter_name">
                        <option value="all">All</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </label>
                <label class="filter">Status:
                    <select id="status_filter_name">
                        <option value="all">All</option>
                        <option value="open">Open</option>
                        <option value="pending">Pending</option>
                        <option value="closed">Closed</option>
                    </select>
                </label>    
            </div>    
            <?php if ($session->getRole() !== Roles::Client->value) { ?>
                <div id="select_option">
                    <button type="button" class="my_tickets">My Tickets</button>
                    <button type="button" class="agent_tickets">Agent Tickets</button>
                    <button type="button" class="general_tickets">General Tickets</button>
                </div>
            <?php } ?>    
            <div class="page_content_department"> 
                <nav class="page_nav">
                    <ul class="page_list_content">
                    <?php foreach ($tickets as $ticket) {    
                        $status = '';
                        if ($ticket->status === 1) $status = 'Open';
                        if ($ticket->status === 2) $status = 'Pending';
                        if ($ticket->status === 3) $status = 'Closed';
                        
                        $priority = '';
                        if ($ticket->priority === 1) $priority = 'High';
                        if ($ticket->priority === 2) $priority = 'Medium';
                        if ($ticket->priority === 3) $priority = 'Low';
                    ?>
                        <li class="element_of_list">
                            <a href="../pages/ticket.php?ticket_id=<?php echo htmlentities(strval($ticket->id))?>" class="element_link">
                                <span class="element_id">#<?php echo htmlentities(strval($ticket->id))?></span>
                                <span class="title"><?php echo htmlentities($ticket->title)?></span>
                                <span class="status"><?php echo htmlentities($status)?></span>
                                <span class="priority"><?php echo htmlentities($priority)?></span>  
                            </a>
                            <?php if ($ticket->clientTrack === 0) { ?>
                                <span class="track" onclick=trackTicket(<?php echo $ticket->id;?>)><i class="far fa-star"></i></span>
                            <?php } else { ?>
                                <span class="track" onclick=unTrackTicket(<?php echo $ticket->id;?>)><i class="fa-solid fa-star"></i></span>
                            <?php } ?>   
                        </li>
                    <?php } ?>
                    </ul>  
                </nav>  
            </div>
        </div>    
    </main>
<?php } ?>


<?php function drawAddTicket(string $titlePage, array $departments) { ?>
    <main>
        <div id="page_container">
            <div class="page_header">
                <h1><?php echo htmlentities($titlePage)?></h1>
            </div>
            <div class="add_content">
                <form action="../actions/action_create_ticket.php" method="post" class="form">
                    <label id="title">Title:</label>
                    <input name="title" type="text" placeholder="Title" required="required">
                    <label id="hashtag">Hashtag:</label>
                    <input name="hashtag" type="text" placeholder="Hashtag">
                    <label id="department">Department:</label>
                    <select name="department">
                        <?php foreach ($departments as $department) { if ($department->name === 'General') { $departmentG = '---' ?>
                            <option value="<?php echo htmlentities($departmentG)?>"><?php echo htmlentities($departmentG)?></option>
                            <?php } else { ?>
                                <option value="<?php echo htmlentities($department->name)?>"><?php echo htmlentities($department->name)?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                    <label id="category">Category:</label>
                    <input name="category" type="text" placeholder="Category">
                    <label id="description">Description:</label>
                    <textarea name="description" placeholder="Description" required="required"></textarea>
                    <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                    <input name="Submit" type="submit" value="Submit">
                </form>
            </div>
        </div>
    </main>
<?php } ?>


<?php function drawTicket(Session $session, Ticket $ticket, string $username, array $hashtags) { ?>
    <main>
        <div id="page_container">
            <div class="ticket_header">
                <h1><?php echo htmlentities($ticket->title)?></h1>
                <div class="header_buttons">  
                    <?php if ($session->getRole() !== Roles::Client->value) { ?>
                        <a href="../pages/edit_ticket.php?ticket_id=<?php echo htmlentities(strval($ticket->id))?>" class="new_add_button">Edit Ticket</a>
                    <?php } ?>
                    <a href="../pages/ticket_changes.php?ticket_id=<?php echo htmlentities(strval($ticket->id))?>" class="new_add_button">List Changes</a>     
                </div>
            </div>
            <?php 
                $status = "";
                if ($ticket->status === 1) $status = 'Open';
                if ($ticket->status === 2) $status = 'Pending';
                if ($ticket->status === 3) $status = 'Closed';
            ?>
            <h1 class="ticket_status"><?php echo htmlentities($status)?></h1>
            <div class="hashtag_container">
                <?php foreach ($hashtags as $hashtag) { ?>
                    <span class="hashtag"><?php echo htmlentities($hashtag->name)?></span>
                <?php } ?>
            </div>
            <div class="ticket_container">
                <p class="ticket_content"><?php echo htmlentities($ticket->description)?></p>
                <footer class="ticket_author">
                    <span class="author"><?php echo htmlentities($username)?></span>
                    <time datetime="2023-04-20T10:00"><?php echo htmlentities($ticket->date)?></time>
                </footer>
            </div>
<?php } ?>


<?php function drawTicketChanges(string $title, int $ticketid, array $changes) { ?>
        <main>
            <div id="page_container">
                <div class="page_header">
                    <h1>Ticket Changes</h1>
                    <h1><?php echo htmlentities($title)?></h1>
                    <h1>#<?php echo htmlentities(strval($ticketid))?></h1>
                </div>
                <div class="changes_container">
                    <?php foreach ($changes as $change) {    ?>
                        <div class="change">
                            <p><?=$change->content?></p>
                            <footer class="comment_author">
                                <span class="username_comment_author"><?php echo htmlentities($change->username)?></span>
                                <time datetime="2023-04-20T10:07"><?php echo htmlentities($change->date)?></time>
                            </footer>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </main>
<?php } ?>      


<?php function drawEditTicket(Ticket $ticket, string $username, string $hashtags, string $agentUsername, array $departments) { ?>
        <main>
            <div id="page_container">
                <div class="ticket_page_header">
                    <div class="header_title">
                        <h1>Edit Ticket</h1>
                    </div>
                    <div class="header_info">
                        <h2>#<?php echo htmlentities(strval($ticket->id))?></h2>    
                        <h2><?php echo htmlentities($username)?></h2>
                    </div>
                </div>    
                <form action="../actions/action_edit_ticket.php?ticket_id=<?php echo htmlentities(strval($ticket->id))?>" method="post" class="form">
                    <label for="agent">Agent:</label>
                    <input list="agents" id="agent" name="agent" value="<?php echo htmlentities($agentUsername)?>">
                    <datalist id="agents"></datalist>
                    <label for="department">Department:</label>
                    <?php if ($ticket->department === 'General') { $department = '---' ?>
                        <input list="department_names" id="department" name="department" value="<?php echo htmlentities($department)?>" required>
                    <?php } else { ?>
                        <input list="department_names" id="department" name="department" value="<?php echo htmlentities($ticket->department)?>" required>
                    <?php } ?>
                    <datalist id="department_names">
                        <?php foreach ($departments as $department) { 
                            if ($department->name === 'General') continue;
                        ?>
                            <option value="<?=htmlspecialchars($department->name, ENT_QUOTES)?>"></option>
                        <?php } ?>
                    </datalist>
                    <label for="status">Status:</label>
                    <input list="status_names" id="status" name="status" 
                    <?php
                        $status = "Open";
                        if ($ticket->status === 2) {
                            $status = "Pending";
                        }
                        if ($ticket->status === 3) {
                            $status = "Closed";
                        }
                    ?>
                    value=<?php echo htmlentities($status)?> required>
                    <datalist id="status_names">
                        <option value="Open"></option>
                        <option value="Pending"></option>
                        <option value="Closed"></option>
                    </datalist>
                    <label for="priority">Priority:</label>
                    <?php
                        $priority = "Low";
                        if ($ticket->priority === 2) $priority = "Medium";
                        if ($ticket->priority === 1) $priority = "High";
                    ?>
                    <input list="priorities" id="priority" name="priority" value="<?php echo htmlentities($priority)?>" required>
                    <datalist id="priorities">
                        <option value="Low"></option>
                        <option value="Medium"></option>
                        <option value="High"></option>
                    </datalist>
                    <label for="hashtag">Hashtag:</label>
                    <input list="hashtags" id="hashtag" name="hashtag" value="<?php echo htmlentities($hashtags)?>">
                    <datalist id="hashtags"></datalist>
                    <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                    <input type="submit" value="Save">
                </form>
            </div>    
        </main>
<?php } ?>   
