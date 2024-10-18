<?php
require_once 'classes/Property.php';

if (!isset($_GET['id'])) {
    header('Location: index.php?page=property_listings');
    exit;
}

$db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$property = Property::getById($db, $_GET['id']);

if (!$property) {
    header('Location: index.php?page=property_listings');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Property - Real Estate Management System</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php include 'views/navigation.php'; ?>

    <main class="container">
        <div class="page-header">
            <h1>Edit Property</h1>
            <a href="index.php?page=property_listings" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Listings</a>
        </div>
        <form action="actions/update_property.php" method="POST" enctype="multipart/form-data" class="submit-property-form">
            <input type="hidden" name="id" value="<?php echo $property->getId(); ?>">
            
            <section>
                <h2>Basic Information</h2>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($property->getTitle()); ?>">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($property->getDescription()); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="property_image">Property Image</label>
                    <input type="file" id="property_image" name="property_image" accept="image/*">
                    <?php if ($property->getImagePath()): ?>
                    <?php endif; ?>
                </div>
            </section>

            <section>
                <h2>Property Details</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="bedroom">Bedroom</label>
                        <input type="number" id="bedroom" name="bedroom" min="0" value="<?php echo $property->getBedrooms(); ?>">
                    </div>
                    <div class="form-group">
                        <label for="bathroom">Bathroom</label>
                        <input type="number" id="bathroom" name="bathroom" min="0" value="<?php echo $property->getBathrooms(); ?>">
                    </div>
                </div>
            </section>

            <section>
                <h2>Price & Location</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" id="price" name="price" required step="0.01" min="0" value="<?php echo $property->getPrice(); ?>">
                    </div>
                    <div class="form-group">
                        <label for="area_size">Area Size (in sqft)</label>
                        <input type="number" id="area_size" name="area_size" min="0" value="<?php echo $property->getAreaSize(); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" required value="<?php echo htmlspecialchars($property->getLocation()); ?>">
                </div>
            </section>

            <section>
                <h2>Property Features</h2>
                <div class="form-group">
                    <label for="features">Features</label>
                    <textarea id="features" name="features" rows="5"><?php echo htmlspecialchars($property->getFeatures()); ?></textarea>
                </div>
            </section>

            <div class="form-group">
                <label for="type">Property Type</label>
                <select id="type" name="type" required>
                    <option value="house" <?php echo $property->getType() == 'house' ? 'selected' : ''; ?>>House</option>
                    <option value="apartment" <?php echo $property->getType() == 'apartment' ? 'selected' : ''; ?>>Apartment</option>
                    <option value="commercial" <?php echo $property->getType() == 'commercial' ? 'selected' : ''; ?>>Commercial</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="available" <?php echo $property->getStatus() == 'available' ? 'selected' : ''; ?>>Available</option>
                    <option value="sold" <?php echo $property->getStatus() == 'sold' ? 'selected' : ''; ?>>Sold</option>
                    <option value="rented" <?php echo $property->getStatus() == 'rented' ? 'selected' : ''; ?>>Rented</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Property</button>
                <a href="index.php?page=property_listings" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </main>
</body>
</html>
