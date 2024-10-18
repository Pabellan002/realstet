<?php
require_once 'classes/Property.php';

$db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Pagination
$page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
$perPage = 10;
$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

// Search parameters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$minPrice = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : 0;
$maxPrice = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : PHP_FLOAT_MAX;

// Display message handling
$message = '';
$messageType = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $messageType = $_SESSION['message_type'];
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

// Get properties
$properties = Property::getAll($db, $start, $perPage, $search, $type, $status, $minPrice, $maxPrice);

// Get total number of properties for pagination
$totalProperties = Property::getCount($db, $search, $type, $status, $minPrice, $maxPrice);
$totalPages = ceil($totalProperties / $perPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listings - Real Estate Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'views/navigation.php'; ?>

    <main class="container">
        <div class="page-header">
            <h1>Property Listings</h1>
            <div class="action-buttons">
                <a href="index.php?page=add_property" class="btn btn-primary">Add Property</a>
                <button id="toggle-search" class="btn btn-secondary">Search Properties</button>
            </div>
        </div>

        <?php if ($message): ?>
        <div class="alert alert-<?php echo $messageType; ?>">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <div id="search-form" class="search-form" style="display: none;">
            <form action="" method="GET">
                <input type="hidden" name="page" value="property_listings">
                <div class="search-row">
                    <div class="search-group">
                        <input type="text" name="search" placeholder="Search properties" value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="search-group">
                        <select name="type">
                            <option value="">All Types</option>
                            <option value="house" <?php echo $type == 'house' ? 'selected' : ''; ?>>House</option>
                            <option value="apartment" <?php echo $type == 'apartment' ? 'selected' : ''; ?>>Apartment</option>
                            <option value="commercial" <?php echo $type == 'commercial' ? 'selected' : ''; ?>>Commercial</option>
                        </select>
                    </div>
                    <div class="search-group">
                        <select name="status">
                            <option value="">All Statuses</option>
                            <option value="available" <?php echo $status == 'available' ? 'selected' : ''; ?>>Available</option>
                            <option value="sold" <?php echo $status == 'sold' ? 'selected' : ''; ?>>Sold</option>
                            <option value="rented" <?php echo $status == 'rented' ? 'selected' : ''; ?>>Rented</option>
                        </select>
                    </div>
                </div>
                <div class="search-row">
                    <div class="search-group">
                        <input type="number" name="min_price" placeholder="Min Price" value="<?php echo $minPrice > 0 ? $minPrice : ''; ?>">
                    </div>
                    <div class="search-group">
                        <input type="number" name="max_price" placeholder="Max Price" value="<?php echo $maxPrice < PHP_FLOAT_MAX ? $maxPrice : ''; ?>">
                    </div>
                    <div class="search-group">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </form>
        </div>

        <section id="property-listings">
            <h2>Current Listings</h2>
            <div class="property-grid">
                <?php foreach ($properties as $property): ?>
                <div class="property-card" data-property-id="<?php echo $property->getId(); ?>">
                    <div class="property-image-container">
                        <img src="<?php echo htmlspecialchars($property->getImagePath()); ?>" 
                             alt="<?php echo htmlspecialchars($property->getTitle()); ?>" 
                             class="property-image">
                        <div class="property-overlay">
                            <div class="property-status <?php echo strtolower($property->getStatus()); ?>">
                                <?php echo ucfirst($property->getStatus()); ?>
                            </div>
                            <div class="property-details">
                                <h3 class="property-title"><?php echo htmlspecialchars($property->getTitle()); ?></h3>
                                <p class="property-price">₱<?php echo number_format($property->getPrice(), 2); ?></p>
                                <p class="property-area"><?php echo $property->getAreaSize(); ?> sqft</p>
                            </div>
                        </div>
                    </div>
                    <div class="property-actions">
                        <a href="index.php?page=edit_property&id=<?php echo $property->getId(); ?>" class="btn btn-sm btn-update" onclick="event.stopPropagation();">
                            <i class="fas fa-edit"></i> Update
                        </a>
                        <a href="actions/delete_property.php?id=<?php echo $property->getId(); ?>" class="btn btn-sm btn-delete" onclick="event.stopPropagation(); return confirm('Are you sure you want to delete this property?')">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php
            $currentPage = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
            $range = 2;

            // Previous button
            if ($currentPage > 1) {
                echo "<a href='?page=property_listings&page_num=" . ($currentPage - 1) . "&" . http_build_query(array_filter(['search' => $search, 'type' => $type, 'status' => $status, 'min_price' => $minPrice, 'max_price' => $maxPrice])) . "' class='btn btn-pagination'>&laquo; Previous</a>";
            } else {
                echo "<span class='btn btn-pagination disabled'>&laquo; Previous</span>";
            }

            // Page numbers
            for ($i = 1; $i <= $totalPages; $i++) {
                if ($i == 1 || $i == $totalPages || ($i >= $currentPage - $range && $i <= $currentPage + $range)) {
                    echo "<a href='?page=property_listings&page_num=$i&" . http_build_query(array_filter(['search' => $search, 'type' => $type, 'status' => $status, 'min_price' => $minPrice, 'max_price' => $maxPrice])) . "' class='btn btn-pagination " . ($currentPage == $i ? "active" : "") . "'>$i</a>";
                } elseif ($i == $currentPage - $range - 1 || $i == $currentPage + $range + 1) {
                    echo "<span class='btn btn-pagination disabled'>...</span>";
                }
            }

            // Next button
            if ($currentPage < $totalPages) {
                echo "<a href='?page=property_listings&page_num=" . ($currentPage + 1) . "&" . http_build_query(array_filter(['search' => $search, 'type' => $type, 'status' => $status, 'min_price' => $minPrice, 'max_price' => $maxPrice])) . "' class='btn btn-pagination'>Next &raquo;</a>";
            } else {
                echo "<span class='btn btn-pagination disabled'>Next &raquo;</span>";
            }
            ?>
        </div>
        <?php endif; ?>
    </main>

    <div id="property-details-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="property-details"></div>
        </div>
    </div>

    <div id="propertyModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="property-details">
                <img src="" alt="Property Image" class="property-details-image" id="modalPropertyImage">
                <div class="property-details-info">
                    <h2 id="modalPropertyTitle"></h2>
                    <p class="property-price" id="modalPropertyPrice"></p>
                    <ul class="property-details-list">
                        <li><strong>Location:</strong> <span id="modalPropertyLocation"></span></li>
                        <li><strong>Type:</strong> <span id="modalPropertyType"></span></li>
                        <li><strong>Status:</strong> <span id="modalPropertyStatus"></span></li>
                        <li><strong>Bedrooms:</strong> <span id="modalPropertyBedrooms"></span></li>
                        <li><strong>Bathrooms:</strong> <span id="modalPropertyBathrooms"></span></li>
                        <li><strong>Area Size:</strong> <span id="modalPropertyAreaSize"></span></li>
                    </ul>
                    <h3>Description</h3>
                    <p id="modalPropertyDescription"></p>
                    <h3>Features</h3>
                    <p id="modalPropertyFeatures"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('toggle-search').addEventListener('click', function() {
        var searchForm = document.getElementById('search-form');
        searchForm.style.display = searchForm.style.display === 'none' ? 'block' : 'none';
    });

    const propertyModal = document.getElementById("propertyModal");
    const closeButtons = document.querySelectorAll('.close');

    // Function to open the modal with property details
    function showPropertyDetails(propertyId) {
        // Fetch property details using AJAX
        fetch(`get_property_details.php?id=${propertyId}`)
            .then(response => response.json())
            .then(property => {
                document.getElementById("modalPropertyImage").src = property.image_path;
                document.getElementById("modalPropertyTitle").textContent = property.title;
                document.getElementById("modalPropertyPrice").textContent = `₱${parseFloat(property.price).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                document.getElementById("modalPropertyLocation").textContent = property.location;
                document.getElementById("modalPropertyType").textContent = property.type;
                document.getElementById("modalPropertyStatus").textContent = property.status;
                document.getElementById("modalPropertyBedrooms").textContent = property.bedrooms;
                document.getElementById("modalPropertyBathrooms").textContent = property.bathrooms;
                document.getElementById("modalPropertyAreaSize").textContent = `${property.area_size} sq ft`;
                document.getElementById("modalPropertyDescription").textContent = property.description;
                document.getElementById("modalPropertyFeatures").textContent = property.features;

                propertyModal.style.display = "block";
            })
            .catch(error => console.error('Error:', error));
    }

    // Function to close the modal
    function closePropertyModal() {
        propertyModal.style.display = "none";
    }

    // Close modal when clicking on the close button
    closeButtons.forEach(function(closeBtn) {
        closeBtn.onclick = closePropertyModal;
    });

    // Close modal when clicking outside the modal content
    window.onclick = function(event) {
        if (event.target == propertyModal) {
            closePropertyModal();
        }
    }

    // Add click event listeners to all property cards
    document.querySelectorAll('.property-card').forEach(card => {
        card.addEventListener('click', function(event) {
            // Prevent the event from bubbling up to parent elements
            event.stopPropagation();
            const propertyId = this.getAttribute('data-property-id');
            showPropertyDetails(propertyId);
        });
    });

    // Prevent modal from closing when clicking inside the modal content
    document.querySelector('.modal-content').addEventListener('click', function(event) {
        event.stopPropagation();
    });
    </script>
</body>
</html>
