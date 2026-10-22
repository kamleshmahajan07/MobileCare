<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Repair Services | Anand Mobile and Electrics';
$pageDescription = 'Explore mobile repair service categories and submit a repair enquiry to Anand Mobile and Electrics.';

$stmt = $pdo->query(
    'SELECT id, service_name, description, starting_price
     FROM repair_services
     WHERE is_active = 1
     ORDER BY id ASC'
);

$services = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="section container">
    <span class="eyebrow">DEVICE CARE</span>
    <h1>Mobile Repair Services</h1>
    <p>Choose the closest category and tell us what is happening with your device. Final diagnosis and pricing are confirmed by the shop.</p>
    <div class="service-note"><strong>Service desk</strong><span>Anand Mobile and Electrics · Sachin, Surat · 10 AM – 9 PM</span></div>

    <div class="feature-grid">
        <?php foreach ($services as $service): ?>
            <article class="feature-card">
                <div class="feature-icon">⚙</div>

                <h3><?= e($service['service_name']) ?></h3>

                <p><?= e($service['description'] ?? '') ?></p>

                <?php if ($service['starting_price'] !== null): ?>
                    <p>
                        Starting at
                        <strong>
                            <?= e(formatPrice($service['starting_price'])) ?>
                        </strong>
                    </p>
                <?php else: ?>
                    <p>Price confirmed after device inspection.</p>
                <?php endif; ?>

                <a
                    class="btn"
                    href="repair-booking.php?service_id=<?= (int) $service['id'] ?>"
                >
                    Request Repair
                </a>
            </article>
        <?php endforeach; ?>
    </div>
    <?php if (!$services): ?>
        <div class="empty-state">
            <div class="feature-icon">⚙</div>
            <h3>Contact us about your repair</h3>
            <p>Describe the problem in a repair enquiry and the shop can review it.</p>
            <a class="btn" href="repair-booking.php">Request a repair</a>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>