<?php
require_once 'classes/Property.php';
require_once 'classes/Feedback.php';

$db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$property_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$property = Property::getById($db, $property_id);

if (!$property) {
    header('Location: index.php?page=properties');
    exit;
}

$feedbacks = Feedback::getByPropertyId($db, $property_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($property->getTitle()); ?> - Real Estate Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'views/navigation.php'; ?>

    <main class="container">
        <h1><?php echo htmlspecialchars($property->getTitle()); ?></h1>
        <div class="property-details">
            <img src="<?php echo htmlspecialchars($property->getImagePath()); ?>" alt="<?php echo htmlspecialchars($property->getTitle()); ?>" class="property-image">
            <p class="property-price">₱<?php echo number_format($property->getPrice(), 2); ?></p>
            <p class="property-location"><?php echo htmlspecialchars($property->getLocation()); ?></p>
            <p class="property-description"><?php echo nl2br(htmlspecialchars($property->getDescription())); ?></p>
            <!-- Add more property details here -->
        </div>

        <h2>Feedback</h2>
        <?php if (isset($_SESSION['user_id'])): ?>
        <form action="actions/submit_feedback.php" method="post" class="feedback-form">
            <input type="hidden" name="property_id" value="<?php echo $property->getId(); ?>">
            <textarea name="feedback" rows="4" required placeholder="Leave your feedback here"></textarea>
            <button type="submit" class="btn btn-primary">Submit Feedback</button>
        </form>
        <?php else: ?>
        <p>Please <a href="index.php?page=login">log in</a> to leave feedback.</p>
        <?php endif; ?>

        <div class="feedback-list">
            <?php foreach ($feedbacks as $feedback): ?>
            <div class="feedback-item">
                <p class="feedback-author"><?php echo htmlspecialchars($feedback['username']); ?></p>
                <p class="feedback-date"><?php echo date('F j, Y', strtotime($feedback['created_at'])); ?></p>
                <p class="feedback-content"><?php echo nl2br(htmlspecialchars($feedback['content'])); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

    <?php include 'views/footer.php'; ?>
</body>
</html>
