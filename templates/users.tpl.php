<?php declare(strict_types = 1); ?>

<?php function drawUsers(array $users) { ?>
    <main>
        <div id="page_container">
            <div class="page_header">
                <h1>Users</h1>
            </div>
            <div id="filter_page">
                <label for="username">Filter by username:</label>
                <input type="text" id="username" name="username">
            </div> 
                <div class="page_content_department">
                    <nav class="page_nav">
                        <ul class="page_list_content">
                            <?php foreach ($users as $user) { ?>
                                <li class="element_of_list">
                                    <a href="../pages/profile.php?user_id=<?php echo htmlentities(strval($user->id))?>" class="element_link">
                                        <div class="agent_info">
                                            <img src="../images/<?php echo htmlentities($user->pfp)?>" class="pfp" alt="pfp_user">
                                            <span class="username_element"><?php echo htmlentities($user->username)?></span>
                                        </div>    
                                    </a>
                                </li>
                            <?php } ?>    
                        </ul>
                    </nav>
                </div>
        </div>
    </main>
<?php } ?>