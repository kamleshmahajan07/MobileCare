<?php
$pageTitle = 'MobileCare | Mobile Accessories & Repair';
require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container hero-grid">
        <div class="hero-content">
            <span class="eyebrow">YOUR LOCAL MOBILE EXPERTS</span>
            <h1>Better accessories.<br>
                <span>Smarter repairs.</span></h1>

            <p class="hero-description">
                Discover everyday mobile essentials and get help
                with your device from our repair team.
            </p>

            <div class="hero-actions">
                <a href="products.php" class="btn">Explore Accessories ↗</a>
                <a href="repair-booking.php" class="btn btn-outline">
                    Book a Repair
                </a>
            </div>

            <div class="trust-line">
                <span>✓ Friendly service</span>
                <span>✓ Quality-focused repairs</span>
            </div>
        </div>

        <div class="hero-visual">
            <div class="visual-glow"></div>
            <div class="phone-card">
                <div class="phone-camera"></div>
                <div class="phone-screen">
                    <span class="screen-label">MOBILECARE</span>
                    <div class="screen-symbol">M</div>
                    <p>Technology, made simple.</p>
                </div>
            </div>
            <div class="floating-card">
                <span class="floating-icon">✦</span>
                <div>
                    <strong>Need a repair?</strong>
                    <p>Tell us what's wrong.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section container">
    <div class="section-heading">
        <div>
            <span class="eyebrow">WHAT WE OFFER</span>
            <h2>Everything your phone needs.</h2>
        </div>
        <p>Accessories and support, all in one place.</p>
    </div>

    <div class="feature-grid">
        <article class="feature-card">
            <div class="feature-icon">⌁</div>
            <h3>Mobile Accessories</h3>
            <p>Chargers, cables, covers and screen protection.</p>
            <a href="products.php" class="text-link">
                Explore products →
            </a>
        </article>

        <article class="feature-card">
            <div class="feature-icon">⚙</div>
            <h3>Repair Services</h3>
            <p>Get assistance with charging, screens and other issues.</p>
            <a href="services.php" class="text-link">
                View services →
            </a>
        </article>

        <article class="feature-card">
            <div class="feature-icon">↗</div>
            <h3>Easy Enquiries</h3>
            <p>Send your device details and request a repair.</p>
            <a href="repair-booking.php" class="text-link">
                Request a repair →
            </a>
        </article>
    </div>
</section>

<section class="cta-section">
    <div class="container cta-inner">
        <div>
            <span class="eyebrow">LET'S GET IT SORTED</span>
            <h2>Your phone needs attention?</h2>
            <p>Submit a repair enquiry and let our team contact you.</p>
        </div>
        <a href="repair-booking.php" class="btn btn-light">
            Request a Repair ↗
        </a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
