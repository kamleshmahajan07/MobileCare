<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Accessories | Anand Mobile and Electrics';
$pageDescription = 'Browse mobile chargers, cables, covers and screen protection at Anand Mobile and Electrics in Sachin, Surat.';
$description = 'Explore mobile chargers, cables, covers and screen protection at Anand Mobile and Electrics in Sachin, Surat.';

$stmt = $pdo->query(
    'SELECT id, name, category, description, price, image, stock_quantity
     FROM products
     WHERE is_active = 1 AND stock_quantity > 0
     ORDER BY created_at DESC'
);
$products = $stmt->fetchAll();

function productFallbackImage(array $product): string
{
    $text = strtolower(($product['category'] ?? '') . ' ' . ($product['name'] ?? ''));
    if (str_contains($text, 'cable') || str_contains($text, 'wire')) return 'cable.svg';
    if (str_contains($text, 'cover') || str_contains($text, 'case')) return 'cover.svg';
    if (str_contains($text, 'glass') || str_contains($text, 'screen protection') || str_contains($text, 'protector')) return 'tempered-glass.svg';
    if (str_contains($text, 'earphone') || str_contains($text, 'earbud') || str_contains($text, 'headphone')) return 'earphones.svg';
    if (str_contains($text, 'power bank') || str_contains($text, 'powerbank')) return 'power-bank.svg';
    return 'charger.svg';
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-intro">
    <div class="container">
        <span class="eyebrow">THE EVERYDAY ESSENTIALS</span>
        <h1>Mobile Accessories</h1>
        <p>Useful accessories for charging, protection and everyday phone care.</p>
        <div class="intro-meta">
            <span>✓ Local shop in Sachin, Surat</span>
            <span>✓ Product availability shown on this page</span>
        </div>
    </div>
</section>

<section class="section container">
    <div class="section-heading">
        <div>
            <span class="eyebrow">OUR COLLECTION</span>
            <h2>Find your phone essentials</h2>
        </div>
        <p>Want to check compatibility? Send us an enquiry.</p>
    </div>

    <div class="feature-grid product-grid">
        <?php foreach ($products as $product): ?>
            <?php
            $imageName = basename((string) ($product['image'] ?? ''));
            $uploadedPath = __DIR__ . '/assets/images/products/' . $imageName;
            $hasUploadedImage = $imageName !== ''
                && $imageName !== 'be4fbe95f6816bd02e20ef7d2b265062.png'
                && is_file($uploadedPath)
                && in_array(strtolower(pathinfo($imageName, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'svg'], true);
            $displayImage = $hasUploadedImage
                ? 'assets/images/products/' . rawurlencode($imageName)
                : 'assets/images/products/' . productFallbackImage($product);
            ?>
            <article class="feature-card product-card">
                <a class="product-image" href="contact.php" aria-label="Ask about <?= e($product['name']) ?>">
                    <img src="<?= e($displayImage) ?>" alt="<?= e($product['name']) ?>" loading="lazy">
                </a>
                <p class="eyebrow product-category"><?= e($product['category']) ?></p>
                <h3><?= e($product['name']) ?></h3>
                <p class="product-description"><?= e($product['description'] ?? '') ?></p>
                <div class="product-card-bottom">
                    <strong class="product-price">Price on request</strong>
                    <span class="stock-pill"><span></span> In stock</span>
                </div>
                <a class="text-link" href="contact.php">Enquire about this product <span aria-hidden="true">→</span></a>
            </article>
        <?php endforeach; ?>
    </div>

    <?php if (!$products): ?>
        <div class="empty-state">
            <div class="feature-icon">📦</div>
            <h3>Products are being updated</h3>
            <p>Please contact the shop to check current availability.</p>
            <a class="btn" href="contact.php">Contact the shop</a>
        </div>
    <?php endif; ?>
</section>

<section class="cta-section">
    <div class="container cta-inner">
        <div>
            <span class="eyebrow">NOT SURE WHAT FITS?</span>
            <h2>Check compatibility before you buy.</h2>
            <p>Tell us your mobile model and the accessory you need.</p>
        </div>
        <a href="contact.php" class="btn btn-light">Ask the shop ↗</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
