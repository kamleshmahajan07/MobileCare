<?php
$pageTitle = 'About the Shop | Anand Mobile and Electrics';
$pageDescription = 'Learn about Anand Mobile and Electrics, owned by Anand Singh, in Sachin, Surat.';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-intro">
    <div class="container">
        <span class="eyebrow">ABOUT THE SHOP</span>
        <h1>Everyday mobile care, close to home.</h1>
        <p>MobileCare is the website experience for Anand Mobile and Electrics, a local mobile accessories and repair shop in Sachin, Surat.</p>
    </div>
</section>

<section class="section container">
    <div class="about-layout">
        <div class="about-copy">
            <span class="eyebrow">ANAND MOBILE AND ELECTRICS</span>
            <h2>Helpful service for your everyday device.</h2>
            <p>Whether you need a charging accessory, a protective cover or help with a mobile issue, the website makes it easier to explore products and send a repair enquiry before visiting the shop.</p>
            <p>Customers can browse available accessories, review repair service categories and submit their mobile model and issue through the repair enquiry form.</p>
            <a class="btn" href="repair-booking.php">Request a repair ↗</a>
        </div>
        <div class="shop-info-panel">
            <div class="shop-info-icon">⌖</div>
            <span class="eyebrow">SHOP DETAILS</span>
            <h3>Anand Mobile and Electrics</h3>
            <dl>
                <div><dt>Owner</dt><dd>Anand Singh</dd></div>
                <div><dt>Location</dt><dd>Sachin, Surat, Gujarat</dd></div>
                <div><dt>Opening hours</dt><dd>10:00 AM – 9:00 PM</dd></div>
                <div><dt>Services</dt><dd>Mobile repair and accessories</dd></div>
            </dl>
            <a class="text-link" href="https://www.google.com/maps/search/?api=1&query=Anand+Mobile+and+Electrics+Sachin+Surat" target="_blank" rel="noopener noreferrer">Find the shop on Maps ↗</a>
        </div>
    </div>
</section>

<section class="section section-soft">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">HOW WE CAN HELP</span>
                <h2>Accessories and repair enquiries in one place.</h2>
            </div>
        </div>
        <div class="feature-grid">
            <article class="feature-card">
                <div class="feature-icon">⌁</div>
                <h3>Mobile Accessories</h3>
                <p>Browse chargers, cables, covers and screen protection items.</p>
                <a class="text-link" href="products.php">Browse accessories →</a>
            </article>
            <article class="feature-card">
                <div class="feature-icon">⚙</div>
                <h3>Repair Assistance</h3>
                <p>Choose a repair category and describe the issue with your device.</p>
                <a class="text-link" href="services.php">View services →</a>
            </article>
            <article class="feature-card">
                <div class="feature-icon">💬</div>
                <h3>Simple Enquiries</h3>
                <p>Send your details so the shop can review your request.</p>
                <a class="text-link" href="repair-booking.php">Send an enquiry →</a>
            </article>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
