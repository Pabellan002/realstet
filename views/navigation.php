<?php
// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="main-nav">
    <div class="nav-container">
        <a href="index.php" class="logo">
            <img src="img/real.jpg" alt="Logo" class="logo-image">
            <span class="logo-text">Real <span class="logo-highlight">Estate</span> Management System</span>
        </a>
        <ul class="nav-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <li><a href="index.php?page=dashboard" class="nav-item">Dashboard</a></li>
                    <li><a href="index.php?page=property_listings" class="nav-item">Manage Properties</a></li>
                    <li><a href="index.php?page=client_management" class="nav-item">Manage Clients</a></li>
                <?php else: ?>
                    <li><a href="index.php" class="nav-item">Home</a></li>
                    <li><a href="index.php?page=properties" class="nav-item">Properties</a></li>
                    <li><a href="index.php?page=my_inquiries" class="nav-item">My Inquiries</a></li>
                <?php endif; ?>
                <li><a href="actions/logout.php" class="nav-item">Logout</a></li>
            <?php else: ?>
                <li><a href="index.php" class="nav-item">Home</a></li>
                <li><a href="index.php?page=properties" class="nav-item">Properties</a></li>
                <li><a href="index.php?page=login" class="nav-item">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
