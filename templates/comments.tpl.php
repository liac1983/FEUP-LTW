<?php declare(strict_types = 1); ?>


<?php function drawComments(array $messages, array $usernames, int $ticketID, bool $foreignAgent) { ?>
                <div class="page_comment_section">
                    <h2 class="comment_section_title">Comment Section</h2>
                    <div class="comment_section">
                        <?php for ($i = 0; $i < count($messages); $i++) {    ?>
                            <div class="comment">
                                    <p><?php echo htmlentities($messages[$i]->content)?></p>
                                    <footer class="comment_author">
                                        <span class="username_comment_author"><?php echo htmlentities($usernames[$i]->username)?></span>
                                        <time datetime="2023-04-20T10:07"><?php echo htmlentities($messages[$i]->date)?></time>
                                    </footer>
                            </div>
                        <?php } ?>
                    </div>    
                <?php if (!$foreignAgent) { ?>
                    <div class="add_comment">
                        <div class="form">
                            <label id="description">New comment:</label>
                            <textarea name="description" required="required"></textarea>
                            <input onclick='addMessage()' type="submit" value="Send">
                        </div>    
                    </div>  
                <?php } ?>
            </div>
        </div>    
    </main>

<?php } ?> 
