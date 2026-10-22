<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Book a Repair | Anand Mobile and Electrics';

$stmt = $pdo->query(
    'SELECT id, service_name FROM repair_services
     WHERE is_active = 1 ORDER BY service_name'
);
$services = $stmt->fetchAll();

$selectedService = filter_input(INPUT_GET, 'service_id', FILTER_VALIDATE_INT) ?: 0;

require_once __DIR__ . '/includes/header.php';
?>

<section class="section container">
    <span class="eyebrow">WE ARE HERE TO HELP</span>
    <h1>Book a Repair</h1>
    <p>Share your mobile model and describe the issue. Anand Mobile and Electrics can review your enquiry.</p>
    <div class="service-note"><strong>Anand Mobile and Electrics</strong><span>Sachin, Surat · Open daily 10 AM – 9 PM</span></div>

    <?php if (isset($_GET['success'])): ?>
        <p class="feature-card">Your repair enquiry was submitted successfully!</p>
    <?php elseif (isset($_GET['error'])): ?>
        <p class="feature-card">Please check your details and try again.</p>
    <?php endif; ?>

    <form action="actions/submit-enquiry.php" method="POST"
          class="feature-card booking-form" style="max-width:720px">

        <label for="customer_name">Your Name</label>
        <input id="customer_name" name="customer_name"
               type="text" maxlength="100" required>

        <label for="phone">Phone Number</label>
        <input id="phone" name="phone" type="tel" inputmode="tel"
               maxlength="20" pattern="[0-9+() .-]{7,20}" required>
        <small class="form-hint">Enter a number where the shop can reach you.</small>

        <label for="device_model">Mobile Model</label>
        <input id="device_model" name="device_model"
               type="text" maxlength="100" required>

        <label for="service_id">Repair Service</label>
        <select id="service_id" name="service_id">
            <option value="">Select a service (optional)</option>
            <?php foreach ($services as $service): ?>
                <option value="<?= (int) $service['id'] ?>"
                    <?= $selectedService === (int) $service['id'] ? 'selected' : '' ?>>
                    <?= e($service['service_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="problem_description">Describe the Problem</label>
        <textarea id="problem_description" name="problem_description"
                  rows="5" maxlength="2000" required></textarea>

        <button class="btn" type="submit">Submit Repair Enquiry</button>
    </form>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
