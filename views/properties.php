<?php
require_once 'classes/Property.php';

$db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$properties = Property::getAll($db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properties - Real Estate Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'views/navigation.php'; ?>

    <main class="container">
        <h1>Available Properties</h1>
        <div class="property-grid">
            <?php foreach ($properties as $property): ?>
            <div class="property-card">
                <img src="<?php echo htmlspecialchars($property->getImagePath()); ?>" alt="<?php echo htmlspecialchars($property->getTitle()); ?>" class="property-image">
                <div class="property-details">
                    <h2><?php echo htmlspecialchars($property->getTitle()); ?></h2>
                    <p class="property-price">₱<?php echo number_format($property->getPrice(), 2); ?></p>
                    <p class="property-location"><?php echo htmlspecialchars($property->getLocation()); ?></p>
                    <a href="index.php?page=property_details&id=<?php echo $property->getId(); ?>" class="btn btn-primary">View Details</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
