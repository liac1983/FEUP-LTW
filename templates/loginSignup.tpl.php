<?php declare(strict_types = 1); ?>


<?php function drawLogin() { ?>
    </header>
        <main>
            <div class="loginRegister_content">
                <h1>Login</h1>
                <form action="../actions/action_login.php" method="post" class="loginRegister_form">
                    <label id="username">Username:</label>
                    <input name="username" type="text" placeholder="Username" required="required">
                    <label id="password">Password:</label>
                    <input name="password" type="password" placeholder="Password" required="required">
                    <input name="submit" type="submit" value="Login">
                </form>
                <a href="../pages/signup.php" class="loginRegister_button">Create an account</a>
            </div>
        </main>
<?php } ?>



<?php function drawRegister() { ?>
    </header>
        <main>
            <div class="loginRegister_content">
                <h1>Sign Up</h1>
                <form action="../actions/action_signup.php" method="post" class="loginRegister_form">
                    <label id="name">Name:</label>
                    <input name="name" type="text" placeholder="Name" required="required">
                    <label id="username">Username:</label>
                    <input name="username" type="text" placeholder="Username" required="required">
                    <label id="password">Password:</label>
                    <input name="password" type="password" placeholder="Password" required="required">
                    <label id="email">Email:</label>
                    <input name="email" type="email" placeholder="Email" required="required">
                    <input name="submit" type="submit" value="Create account">
                </form>
                <div class="loginRegisterHelp">
                    <p>Already have an account?</p>
                    <a href="../pages/login.php" class="loginRegister_button">Sign in!</a>
                </div>    
            </div>
        </main>
<?php } ?>