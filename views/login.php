<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Real Estate Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-form-container">
            <h1>Welcome Back</h1>
            <p>Log in to your account to manage your properties</p>
            <?php
            if (isset($_GET['error'])) {
                echo '<p class="error-message"><i class="fas fa-exclamation-circle"></i> Invalid username or password</p>';
            }
            ?>
            <form action="actions/login.php" method="post" class="login-form">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i></label>
                    <input type="text" id="username" name="username" required placeholder="Username">
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i></label>
                    <input type="password" id="password" name="password" required placeholder="Password">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Log In</button>
            </form>
            <div class="additional-links">
                <a href="index.php?page=register" class="create-account">Create an Account</a>
            </div>
        </div>
    </div>
</body>
</html>
