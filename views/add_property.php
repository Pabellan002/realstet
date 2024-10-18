<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Property - Real Estate Management System</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php include 'views/navigation.php'; ?>

    <main class="container">
        <div class="page-header">
            <h1>Submit Property</h1>
            <a href="index.php?page=property_listings" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Listings</a>
        </div>
        <form action="actions/add_property.php" method="POST" enctype="multipart/form-data" class="submit-property-form">
            <section>
                <h2>Basic Information</h2>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" required placeholder="Enter your property title here...">
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" rows="5" required placeholder="Describe your property..."></textarea>
                </div>
                <div class="form-group">
                    <label for="property_image">Property Image</label>
                    <input type="file" id="property_image" name="property_image" accept="image/*" required>
                </div>
            </section>

            <section>
                <h2>Property Details</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="bedroom">Bedroom</label>
                        <input type="number" id="bedroom" name="bedroom" min="0">
                    </div>
                    <div class="form-group">
                        <label for="bathroom">Bathroom</label>
                        <input type="number" id="bathroom" name="bathroom" min="0">
                    </div>
                    <div class="form-group">
                        <label for="kitchen">Kitchen</label>
                        <input type="number" id="kitchen" name="kitchen" min="0">
                    </div>
                    <div class="form-group">
                        <label for="balcony">Balcony</label>
                        <input type="number" id="balcony" name="balcony" min="0">
                    </div>
                </div>
            </section>

            <section>
                <h2>Price & Location</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="floor">Floor</label>
                        <select id="floor" name="floor">
                            <option value="">Select Floor</option>
                            <?php for ($i = 1; $i <= 50; $i++) echo "<option value='$i'>$i</option>"; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="total_floors">Total Floors</label>
                        <select id="total_floors" name="total_floors">
                            <option value="">Select Total Floors</option>
                            <?php for ($i = 1; $i <= 50; $i++) echo "<option value='$i'>$i</option>"; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" id="price" name="price" required step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label for="area_size">Area Size (in sqft)</label>
                        <input type="number" id="area_size" name="area_size" min="0">
                    </div>
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" required>
                </div>
                <div class="form-group">
                    <label for="state">State</label>
                    <input type="text" id="state" name="state" required>
                </div>
            </section>

            <section>
                <h2>Property Features</h2>
                <div class="form-group">
                    <label for="features">Features</label>
                    <textarea id="features" name="features" rows="5" placeholder="List property features..."></textarea>
                </div>
            </section>

            <!-- Add these fields to the form -->
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="5" required></textarea>
            </div>

            <div class="form-group">
                <label for="type">Property Type</label>
                <select id="type" name="type" required>
                    <option value="house">House</option>
                    <option value="apartment">Apartment</option>
                    <option value="commercial">Commercial</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="available">Available</option>
                    <option value="sold">Sold</option>
                    <option value="rented">Rented</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Submit Property</button>
                <a href="index.php?page=property_listings" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </main>
</body>
</html>
