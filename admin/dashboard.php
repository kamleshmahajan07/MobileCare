<?php
require_once __DIR__ . '/../includes/auth.php';

$pageTitle = 'Admin Dashboard | MobileCare';
require_once __DIR__ . '/../config/database.php';

$totalEnquiries = (int) $pdo->query(
    'SELECT COUNT(*) FROM repair_enquiries'
)->fetchColumn();

$newEnquiries = (int) $pdo->query(
    "SELECT COUNT(*) FROM repair_enquiries WHERE status = 'New'"
)->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body>
    <h1>MobileCare Admin Dashboard</h1>

    <p>Welcome, <?= htmlspecialchars(
        $_SESSION['admin_name'], ENT_QUOTES, 'UTF-8'
    ) ?>!</p>

    <h2>Total Enquiries: <?= $totalEnquiries ?></h2>
    <h2>New Enquiries: <?= $newEnquiries ?></h2>

    <p>Next, we will add enquiry management here.</p>

    <p>
    <a href="enquiries.php">Manage Repair Enquiries</a>
</p>

<p>
    <a href="logout.php">Logout</a>
</p>
<a href="products.php">Manage Products</a>
</body>
</html>
