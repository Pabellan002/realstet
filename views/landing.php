<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Real Estate Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'views/navigation.php'; ?>

    <main class="container home">
        <section class="hero">
            <div class="hero-content">
                <h1>Welcome to Real Estate Management System</h1>
                <p>Manage your properties efficiently and effectively</p>
                <a href="index.php?page=property_listings" class="btn btn-primary">View Properties</a>
            </div>
        </section>

        <section class="features">
            <h2>Our Services</h2>
            <div class="feature-grid">
                <div class="feature-card">
                    <i class="fas fa-home"></i>
                    <h3>Property Listings</h3>
                    <p>Manage and view all your property listings in one place.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-users"></i>
                    <h3>Client Management</h3>
                    <p>Keep track of your clients and their preferences.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-chart-line"></i>
                    <h3>Analytics</h3>
                    <p>Get insights into your real estate business performance.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-search"></i>
                    <h3>Property Search</h3>
                    <p>Advanced search options to find the perfect property.</p>
                </div>
            </div>
        </section>

        <section class="cta">
            <h2>Ready to get started?</h2>
            <p>Start managing your properties today!</p>
            <a href="index.php?page=add_property" class="btn btn-secondary">Add New Property</a>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Real Estate Management System. All rights reserved.</p>
            <div class="social-links">
                <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
    </footer>
</body>
</html>
