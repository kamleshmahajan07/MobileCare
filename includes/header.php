<?php
$pageTitle = $pageTitle ?? 'MobileCare | Accessories & Repair';
$currentPage = basename($_SERVER['PHP_SELF'] ?? 'index.php');
$pageDescription = $pageDescription ?? 'Mobile accessories and repair enquiries at Anand Mobile and Electrics in Sachin, Surat.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
          content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
    <nav class="navbar container">
        <a class="brand" href="index.php">
            <span class="brand-icon">M</span>
            MobileCare<span class="brand-dot">.</span>
        </a>

        <button class="menu-toggle" id="menuToggle"
                aria-label="Toggle navigation"
                aria-expanded="false">☰</button>

        <div class="nav-links" id="navLinks">
            <a href="index.php"
               <?= $currentPage === 'index.php' ? 'aria-current="page"' : '' ?>>Home</a>
            <a href="products.php" <?= $currentPage === 'products.php' ? 'aria-current="page"' : '' ?>>Accessories</a>
            <a href="services.php" <?= $currentPage === 'services.php' ? 'aria-current="page"' : '' ?>>Repair Services</a>
            <a href="about.php" <?= $currentPage === 'about.php' ? 'aria-current="page"' : '' ?>>About</a>
            <a href="contact.php" <?= $currentPage === 'contact.php' ? 'aria-current="page"' : '' ?>>Contact</a>
            <a class="btn btn-small" href="repair-booking.php">Book a Repair</a>
        </div>
    </nav>
</header>
<main>
