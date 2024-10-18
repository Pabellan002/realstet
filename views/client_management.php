<?php
require_once 'classes/Database.php';
require_once 'classes/Client.php';
require_once 'classes/Appointment.php';
require_once 'classes/Feedback.php';
require_once 'classes/Property.php';

$db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Fetch clients, appointments, and feedback
$clients = Client::getAll($db);
$appointments = Appointment::getAll($db);
$feedbacks = Feedback::getAll($db);
$properties = Property::getAll($db);

// Function to get property title by ID
function getPropertyTitle($db, $propertyId) {
    $property = Property::getById($db, $propertyId);
    return $property ? $property->getTitle() : 'Unknown';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Management - Real Estate Management System</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'views/navigation.php'; ?>

    <main class="container">
        <h1>Client Management</h1>
        
        <!-- Client Profiles Section -->
        <section id="client-profiles">
            <h2>Client Profiles</h2>
            <div class="client-grid">
                <?php foreach ($clients as $client): ?>
                <div class="client-card" onclick="showClientDetails(<?php echo $client->getId(); ?>)">
                    <h3><?php echo htmlspecialchars($client->getName()); ?></h3>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($client->getEmail()); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($client->getPhone()); ?></p>
                    <button class="btn btn-sm btn-secondary">View Details</button>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Appointment Scheduling Section -->
        <section id="appointment-scheduling">
            <h2>Appointment Scheduling</h2>
            <button id="add-appointment-btn" class="btn btn-primary">Schedule New Appointment</button>
            <table class="appointment-table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Date & Time</th>
                        <th>Property</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment->getClientName()); ?></td>
                        <td><?php echo $appointment->getDateTime(); ?></td>
                        <td><?php echo htmlspecialchars(getPropertyTitle($db, $appointment->getPropertyId())); ?></td>
                        <td><?php echo htmlspecialchars($appointment->getType()); ?></td>
                        <td>
                            <button class="btn btn-sm btn-secondary edit-appointment-btn" data-appointment-id="<?php echo $appointment->getId(); ?>">Edit</button>
                            <button class="btn btn-sm btn-danger delete-appointment-btn" data-appointment-id="<?php echo $appointment->getId(); ?>">Cancel</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Feedback Section -->
        <section id="feedback">
            <h2>Client Feedback</h2>
            <table class="feedback-table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Property</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($feedbacks as $feedback): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($feedback->getClientName()); ?></td>
                        <td><?php echo htmlspecialchars(getPropertyTitle($db, $feedback->getPropertyId())); ?></td>
                        <td><?php echo $feedback->getRating(); ?>/5</td>
                        <td><?php echo htmlspecialchars($feedback->getComment()); ?></td>
                        <td><?php echo $feedback->getDate(); ?></td>
                        <td>
                            <button class="btn btn-sm btn-secondary view-feedback-btn" data-feedback-id="<?php echo $feedback->getId(); ?>">View</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <!-- Client Details Modal -->
    <div id="client-details-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Client Details</h2>
            <div id="client-details"></div>
        </div>
    </div>

    <!-- Add Appointment Modal -->
    <div id="add-appointment-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Schedule New Appointment</h2>
            <form id="add-appointment-form">
                <select name="client_id" required>
                    <option value="">Select Client</option>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?php echo $client->getId(); ?>"><?php echo htmlspecialchars($client->getName()); ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="property_id" required>
                    <option value="">Select Property</option>
                    <?php foreach ($properties as $property): ?>
                        <option value="<?php echo $property->getId(); ?>"><?php echo htmlspecialchars($property->getTitle()); ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="datetime-local" name="date_time" required>
                <select name="type" required>
                    <option value="viewing">Viewing</option>
                    <option value="meeting">Meeting</option>
                </select>
                <button type="submit" class="btn btn-primary">Schedule Appointment</button>
            </form>
        </div>
    </div>

    <!-- View Feedback Modal -->
    <div id="view-feedback-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>View Feedback</h2>
            <div id="feedback-details"></div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show Client Details
        function showClientDetails(clientId) {
            fetch(`actions/get_client_details.php?id=${clientId}`)
                .then(response => response.json())
                .then(data => {
                    const detailsHtml = `
                        <p><strong>Name:</strong> ${data.name}</p>
                        <p><strong>Email:</strong> ${data.email}</p>
                        <p><strong>Phone:</strong> ${data.phone}</p>
                        <p><strong>Property Preferences:</strong> ${data.property_preferences}</p>
                    `;
                    document.getElementById('client-details').innerHTML = detailsHtml;
                    document.getElementById('client-details-modal').style.display = 'block';
                });
        }

        // Add event listeners to client cards
        document.querySelectorAll('.client-card').forEach(card => {
            card.addEventListener('click', function() {
                const clientId = this.getAttribute('data-client-id');
                showClientDetails(clientId);
            });
        });

        // Add Appointment
        document.getElementById('add-appointment-btn').addEventListener('click', function() {
            document.getElementById('add-appointment-modal').style.display = 'block';
        });

        document.getElementById('add-appointment-form').addEventListener('submit', function(e) {
            e.preventDefault();
            fetch('actions/add_appointment.php', {
                method: 'POST',
                body: new FormData(this)
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert('Appointment scheduled successfully');
                      location.reload();
                  } else {
                      alert('Failed to schedule appointment');
                  }
              });
        });

        // View Feedback
        document.querySelectorAll('.view-feedback-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const feedbackId = this.getAttribute('data-feedback-id');
                fetch(`actions/get_feedback_details.php?id=${feedbackId}`)
                    .then(response => response.json())
                    .then(data => {
                        const detailsHtml = `
                            <p><strong>Client:</strong> ${data.client_name}</p>
                            <p><strong>Property:</strong> ${data.property_title}</p>
                            <p><strong>Rating:</strong> ${data.rating}/5</p>
                            <p><strong>Comment:</strong> ${data.comment}</p>
                            <p><strong>Date:</strong> ${data.date}</p>
                        `;
                        document.getElementById('feedback-details').innerHTML = detailsHtml;
                        document.getElementById('view-feedback-modal').style.display = 'block';
                    });
            });
        });

        // Close modals
        var modals = document.getElementsByClassName('modal');
        var spans = document.getElementsByClassName("close");

        for (var i = 0; i < spans.length; i++) {
            spans[i].onclick = function() {
                for (var index in modals) {
                    if (typeof modals[index].style !== 'undefined') modals[index].style.display = "none";    
                }
            }
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                for (var index in modals) {
                    if (typeof modals[index].style !== 'undefined') modals[index].style.display = "none";    
                }
            }
        }
    });
    </script>
</body>
</html>
