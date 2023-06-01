<?php declare(strict_types = 1); ?>


<?php function drawProfile(Session $session, Client $user, string $role) { ?>

<main>
    <div id="page_container">
        <div class="profile_header">
          <img class="profile_image" src="../images/<?php echo htmlentities($user->pfp)?>" alt="Profile Picture">
          <div class="profile_header_info">
            <h2 class="profile_name"><?php echo htmlentities($user->name)?></h2>
            <h2 class="user_level"><?php echo htmlentities($role)?></h2>
          </div>
          <?php if ($session->getRole() === Roles::Admin->value || ($session->getID() === $user->id)) { ?>
            <form action="../actions/action_delete_account.php" method="post" class="delete_form">
              <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
              <input id="delete_account" type="submit" value="Delete Account">
            </form>
          <?php } ?>  
        </div>
        <div class="add_content">
          <?php if ($session->getID() === $user->id) { ?>
            <form action="../actions/action_edit_profile.php" method="post" class="form">
              <label for="name">Name:</label>
              <input type="text" id="name" name="name" value="<?php echo htmlentities($user->name)?>" required>
              <label for="username">Username:</label>
              <input type="text" id="username" name="username" value="<?php echo htmlentities($user->username)?>" required>
              <label for="email">Email:</label>
              <input type="email" id="email" name="email" value="<?php echo htmlentities($user->email)?>" required>
              <label for="password">Password:</label>
              <input type="password" id="password" name="password" placeholder="Password">
              <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
              <input type="submit" value="Save">
            </form>
          <?php } ?>  
          <?php if ($session->getRole() === Roles::Admin->value) { ?>
            <form action="../actions/action_edit_role.php?user_id=<?php echo htmlentities(strval($user->id))?>" method="post" class="form">
              <label for="role">Role:</label>
              <input list="role_names" id="role" name="role" value="<?php echo htmlentities($role)?>" required>
              <datalist id="role_names">
                <option value="Client"></option>
                <option value="Agent"></option>
                <option value="Admin"></option>
              </datalist>
              <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
              <input type="submit" value="Update Role">
            </form>
          <?php } ?>  
        </div>  
      </div>
    </main>  

<?php } ?>
