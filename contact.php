<?php
$pageTitle = 'Contact Us | MobileCare';
require_once __DIR__ . '/includes/header.php';
?>

<section class="section container">
    <span class="eyebrow">GET IN TOUCH</span>
    <h1>Contact MobileCare</h1>
    <p>Have a question about mobile accessories or repairs? Get in touch with our team.</p>

    <div class="feature-grid">
        <article class="feature-card">
            <div class="feature-icon">📱</div>
            <h3>Repair Enquiries</h3>
            <p>Tell us about your mobile issue and request a repair.</p>
            <a class="btn" href="repair-booking.php">Book a Repair</a>
        </article>

        <article class="feature-card">
            <div class="feature-icon">🛍️</div>
            <h3>Mobile Accessories</h3>
            <p>Explore accessories and contact us about a product.</p>
            <a class="btn" href="products.php">View Products</a>
        </article>

        <article class="feature-card">
            <div class="feature-icon">⚙️</div>
            <h3>Repair Services</h3>
            <p>Explore the repair services available at our shop.</p>
            <a class="btn" href="services.php">View Services</a>
        </article>
    </div>

    <div class="feature-card" style="margin-top:28px">
        <h2>Visit or Contact Our Shop</h2>
        <p><strong>Shop Name:</strong> MobileCare</p>
        <p><strong>Address:</strong> Add the actual shop address here.</p>
        <p><strong>Phone:</strong> Add the shop's contact number here.</p>
        <p><strong>Business Hours:</strong> Add the actual opening hours here.</p>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
