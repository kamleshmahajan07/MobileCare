<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Repair Services | MobileCare';

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
    <p>Tell us about your device issue to request assistance.</p>

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
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>