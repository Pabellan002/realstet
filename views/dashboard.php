<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}
require_once 'classes/Property.php';
require_once 'classes/Sale.php';
require_once 'classes/Inquiry.php';

$db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Get summary of properties
$availableProperties = Property::getCountByStatus($db, 'available');
$soldProperties = Property::getCountByStatus($db, 'sold');
$rentedProperties = Property::getCountByStatus($db, 'rented');
$totalProperties = $availableProperties + $soldProperties + $rentedProperties;

// Get recent sales and listings
$recentSales = Sale::getRecent($db, 5);
$recentListings = Property::getRecent($db, 5);

// Get recent inquiries
$recentInquiries = Inquiry::getRecent($db, 5);

// Get sales data for chart
$salesData = Sale::getMonthlyData($db, 6); // Last 6 months
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Real Estate Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'views/navigation.php'; ?>

    <main class="container dashboard">
        <h1>Dashboard</h1>

        <section class="summary-cards">
            <div class="card">
                <i class="fas fa-home"></i>
                <h2>Total Properties</h2>
                <p class="large-number"><?php echo $totalProperties; ?></p>
            </div>
            <div class="card">
                <i class="fas fa-check-circle"></i>
                <h2>Available</h2>
                <p class="large-number"><?php echo $availableProperties; ?></p>
            </div>
            <div class="card">
                <i class="fas fa-dollar-sign"></i>
                <h2>Sold</h2>
                <p class="large-number"><?php echo $soldProperties; ?></p>
            </div>
            <div class="card">
                <i class="fas fa-key"></i>
                <h2>Rented</h2>
                <p class="large-number"><?php echo $rentedProperties; ?></p>
            </div>
        </section>

        <section class="dashboard-grid">
            <div class="card recent-sales">
                <h2>Recent Sales</h2>
                <ul>
                    <?php if (!empty($recentSales)): ?>
                        <?php foreach ($recentSales as $sale): ?>
                        <li>
                            <span><?php echo htmlspecialchars($sale['property_title'] ?? 'N/A'); ?></span>
                            <span class="price">₱<?php echo number_format($sale['sale_price'] ?? 0, 2); ?></span>
                        </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No recent sales</li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="card recent-listings">
                <h2>Recent Listings</h2>
                <ul>
                    <?php if (!empty($recentListings)): ?>
                        <?php foreach ($recentListings as $listing): ?>
                        <li>
                            <span><?php echo htmlspecialchars($listing->getTitle()); ?></span>
                            <span class="price">₱<?php echo number_format($listing->getPrice(), 2); ?></span>
                        </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No recent listings</li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="card recent-inquiries">
                <h2>Recent Inquiries</h2>
                <ul>
                    <?php if (!empty($recentInquiries)): ?>
                        <?php foreach ($recentInquiries as $inquiry): ?>
                        <li>
                            <span><?php echo htmlspecialchars($inquiry['client_name'] ?? 'N/A'); ?></span>
                            <span><?php echo htmlspecialchars($inquiry['property_title'] ?? 'N/A'); ?></span>
                        </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No recent inquiries</li>
                    <?php endif; ?>
                </ul>
            </div>
        </section>

        <section class="card sales-chart">
            <h2>Property Sales Trend</h2>
            <canvas id="salesChart"></canvas>
        </section>
    </main>

    <script>
    // Sales Chart
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesData = <?php echo json_encode($salesData); ?>;
    var labels = salesData.map(function(item) { return item.month; });
    var data = salesData.map(function(item) { return parseFloat(item.total); });

    var salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Sales',
                data: data,
                backgroundColor: 'rgba(52, 152, 219, 0.2)',
                borderColor: 'rgba(52, 152, 219, 1)',
                borderWidth: 1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Month'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Sales Amount (₱)'
                    },
                    beginAtZero: true,
                    ticks: {
                        callback: function(value, index, values) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Sales: ₱' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });
    </script>
</body>
</html>
