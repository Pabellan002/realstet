<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Real Estate Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="register-page">
    <div class="register-container">
        <div class="register-form-container">
            <h1>Create an Account</h1>
            <p>Join our Real Estate Management System</p>
            <?php
            if (isset($_GET['error'])) {
                echo '<p class="error-message"><i class="fas fa-exclamation-circle"></i> ' . htmlspecialchars($_GET['error']) . '</p>';
            }
            ?>
            <form action="actions/register.php" method="post" class="register-form">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i></label>
                    <input type="text" id="username" name="username" required placeholder="Username">
                </div>
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i></label>
                    <input type="email" id="email" name="email" required placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i></label>
                    <input type="password" id="password" name="password" required placeholder="Password">
                </div>
                <div class="form-group">
                    <label for="confirm_password"><i class="fas fa-lock"></i></label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm Password">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </form>
            <div class="additional-links">
                <p>Already have an account? <a href="index.php?page=login">Log In</a></p>
            </div>
        </div>
    </div>
</body>
</html>
