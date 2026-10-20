
<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Accessories | MobileCare';

$stmt = $pdo->query(
    'SELECT id, name, category, description, price, image, stock_quantity
     FROM products
     WHERE is_active = 1 AND stock_quantity > 0
     ORDER BY created_at DESC'
);

$products = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="section container">
    <span class="eyebrow">OUR COLLECTION</span>
    <h1>Mobile Accessories</h1>
    <p>Find everyday essentials for your device.</p>

    <div class="feature-grid">
        <?php foreach ($products as $product): ?>
            <article class="feature-card product-card">

                <?php
                $imageName = $product['image'] ?? '';
                $imagePath = __DIR__ . '/assets/images/products/' . $imageName;

                $hasImage = $imageName !== ''
                    && basename($imageName) === $imageName
                    && is_file($imagePath);
                ?>

                <div class="product-image">
                    <?php if ($hasImage): ?>
                        <img
                            src="assets/images/products/<?= rawurlencode($imageName) ?>"
                            alt="<?= e($product['name']) ?>"
                            loading="lazy"
                        >
                    <?php else: ?>
                        <div class="product-placeholder" aria-label="No product image">
                            <span>📱</span>
                            <small>Image coming soon</small>
                        </div>
                    <?php endif; ?>
                </div>

                <p class="eyebrow"><?= e($product['category']) ?></p>

                <h3><?= e($product['name']) ?></h3>

                <p><?= e($product['description'] ?? '') ?></p>

                <strong class="product-price">
                    <?= e(formatPrice($product['price'])) ?>
                </strong>

                <p class="product-stock">In Stock</p>

                <p>
                    <a class="text-link"
                       href="contact.php">
                        Enquire about this product →
                    </a>
                </p>
            </article>
        <?php endforeach; ?>
    </div>

    <?php if (!$products): ?>
        <p>Products are currently unavailable. Please check back later.</p>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
