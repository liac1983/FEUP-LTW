<?php declare(strict_types = 1); ?>


<?php function drawFaqs(Session $session, array $faqs) { ?>
    <main>
    <div id="page_container">
        <div class="page_header">
            <h1>FAQs</h1>
            <?php if ($session->getRole() !== Roles::Client->value && $session->isLoggedIn()) { ?>
                <a href="../pages/add_faq.php" class="new_add_button">Add FAQ</a>
            <?php } ?>    
        </div>
        <div class="page_content">
            <nav class="page_nav">
                <ul class="page_list_content"> 
                <?php foreach ($faqs as $faq) {    ?>
                    <li class="element_of_list">
                        <section class="faq">
                            <h3 class="faq_title"><?php echo htmlentities($faq->title)?> 
                            <?php if ($session->getRole() !== Roles::Client->value && $session->isLoggedIn()) { ?>
                                <a href="edit_faq.php?faq_id=<?php echo htmlentities(strval($faq->id))?>"> <i class='fas fa-edit'></i></a>
                            <?php } ?>
                            </h3>
                            <p class="content_faq"><?php echo htmlentities($faq->content)?></p>
                        </section>
                    </li>
                <?php } ?>
                </ul>
            </nav>
        </div>
    </div>
</main>
<?php } ?>


<?php function drawAddFAQ(string $titlePage) { ?>
    <main>
        <div id="page_container">
            <div class="page_header">
                <h1><?php echo htmlentities($titlePage)?></h1>
            </div>
            <div class="add_content">
                <form action="../actions/action_create_faq.php" method="post" class="form">
                    <label id="title">Title:</label>
                    <input name="title" type="text" placeholder="Title" required="required">
                    <label id="description">Description:</label>
                    <textarea name="description" placeholder="Description" required="required"></textarea>
                    <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                    <input name="submit" type="submit" value="Submit">
                </form>
            </div>
        </div>
    </main>
<?php } ?>


<?php function drawEditFaq(FAQ $faq) { ?>
    <main>
            <div id="page_container">
                <div class="page_header">
                    <h1>Edit FAQ</h1>
                    <form action="../actions/action_delete_faq.php?faq_id=<?php echo htmlentities(strval($faq->id))?>" method="post" class="delete_form">
                        <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                        <input id="delete_faq" type="submit" value="Delete FAQ">
                    </form> 
                </div>    
                <form action="../actions/action_edit_faq.php?faq_id=<?php echo htmlentities(strval($faq->id))?>" method="post" class="form">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlentities($faq->title)?>" required>
                    <label id="content">Description:</label>
                    <textarea name="content" required="required"><?php echo htmlspecialchars($faq->content); ?></textarea>
                    <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                    <input type="submit" value="Save">
                </form>
            </div>    
        </main>
<?php } ?>

