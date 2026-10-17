<?php
$pageTitle = $pageTitle ?? 'MobileCare | Accessories & Repair';
$currentPage = basename($_SERVER['PHP_SELF'] ?? 'index.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
          content="Explore mobile accessories and request mobile repair services.">
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
            <a href="products.php">Accessories</a>
            <a href="services.php">Repair Services</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>
            <a class="btn btn-small" href="repair-booking.php">Book a Repair</a>
        </div>
    </nav>
</header>
<main>
