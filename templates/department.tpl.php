<?php 
    declare(strict_types = 1); 

    require_once(__DIR__ . '/../utils/session.php');
?>


<?php function drawDepartmentPage(Session $session, string $titlePage, array $departments) { ?>
    <main>
        <div id="page_container">
            <div class="page_header">
                <h1><?=$titlePage?></h1>
                <?php if ($session->getRole() === Roles::Admin->value) { ?>
                        <a href="../pages/add_department.php" class="new_add_button">New Department</a>
                <?php } ?>    
            </div>    
            <div class="page_content">
                <nav class="page_nav">
                    <ul class = "page_list_content">
                        <?php foreach ($departments as $department) { if ($department->name === 'General') continue;    ?>
                            <li class="element_of_list">
                                <a href="../pages/department.php?department_name=<?php echo htmlentities($department->name)?>"><?php echo htmlentities($department->name)?></a>
                                <?php if ($session->getRole() === Roles::Admin->value) { ?>
                                        <a href="../pages/edit_department.php?department_name=<?php echo htmlspecialchars(($department->name))?>"> <i class='fas fa-edit'></i></a>
                                <?php } ?>    
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
        </div>
    </main>
<?php } ?>


<?php function drawDepartment(Session $session, array $agents, Department $department) { ?>
    <main>
        <div id="page_container">
            <div class="page_header">
                <h1><?php echo htmlentities($department->name)?></h1>
            </div>
            <p class="department_description"><?php echo htmlentities($department->description)?></p>
            <?php if ($session->getRole() !== Roles::Client->value) { ?>
            <div id="filter_page_department">
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
                <div id="select_option">
                    <button type="button" class="agents_button click">Agents</button>
                    <button type="button" class="tickets_button">Tickets</button>
                </div>
                <div class="page_content_department">
                    <nav class="page_nav">
                        <ul class="page_list_content">
                            <?php foreach ($agents as $agent) { ?>
                                <li class="element_of_list">
                                    <a href="../pages/profile.php?user_id=<?php echo htmlentities(strval($agent->id))?>" class="element_link">
                                        <div class="agent_info">
                                            <img src="../images/<?php echo htmlentities($agent->pfp)?>" class="pfp" alt="pfp_user">
                                            <span class="username_element"><?php echo htmlentities($agent->username)?></span>
                                        </div>    
                                    </a>
                                </li>
                            <?php } ?>    
                        </ul>
                    </nav>
                </div>
            <?php } ?>    
        </div>
    </main>
<?php } ?>    


<?php function drawAddDepartment(string $titlePage) { ?>
    <main>
        <div id="page_container">
            <div class="page_header">
                <h1><?php echo htmlentities($titlePage)?></h1>
            </div>
            <div class="add_content">
                <form action="../actions/action_create_department.php" method="post" class="form">
                    <label id="name">Name:</label>
                    <input name="name" type="text" required="required" placeholder="Name">
                    <label id="description">Description:</label>
                    <textarea name="description" placeholder="Description" required="required"></textarea>
                    <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                    <input name="submit" type="submit" value="Add Department">
                </form>
            </div>
        </div>
    </main>
<?php } ?>


<?php function drawEditDepartment(Department $department) { ?>
    <main>
        <div id="page_container">
            <div class="page_header">
                <h1>Edit <?php echo htmlentities($department->name)?> Department</h1>
                <form action="../actions/action_delete_department.php?department_name=<?php echo htmlentities($department->name)?>" method="post" class="delete_form">
                    <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                    <input id="delete_department" type="submit" value="Delete department">
                </form> 
            </div>
            <div class="add_content">
                <form action="../actions/action_edit_department.php?department_name=<?php echo htmlentities($department->name)?>" method="post" class="form">
                    <label id="name">Name:</label>
                    <input name="name" type="text" required="required" value="<?=htmlspecialchars($department->name, ENT_QUOTES)?>">
                    <label id="description">Description:</label>
                    <textarea name="description" placeholder="Description" required="required"><?php echo htmlspecialchars($department->description); ?></textarea>
                    <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                    <input name="submit" type="submit" value="Save">
                </form>
                <form action="../actions/action_add_agent_department.php?department_name=<?php echo htmlentities($department->name)?>" method="post" class="form">
                    <label for="new_agent">Add agent to department:</label>
                    <input list="agents_new" id="new_agent" name="new_agent" placeholder="Agent's username" >
                    <datalist id="agents_new"></datalist>
                    <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                    <input name="submit" type="submit" value="Add agent">
                </form>
                <form action="../actions/action_remove_agent_department.php?department_name=<?php echo htmlentities($department->name)?>" method="post" class="form">
                    <label for="agent_remove">Remove agent from department:</label>
                    <input list="agents_remove" id="agent_remove" name="agent_remove" placeholder="Agent's username" required>
                    <datalist id="agents_remove"></datalist>
                    <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                    <input name="submit" type="submit" value="Remove agent">
                </form>
            </div>
        </div>
    </main>
<?php } ?>
