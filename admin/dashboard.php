<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/_layout.php';

$totalEnquiries = (int)$pdo->query('SELECT COUNT(*) FROM repair_enquiries')->fetchColumn();
$newEnquiries = (int)$pdo->query("SELECT COUNT(*) FROM repair_enquiries WHERE status = 'New'")->fetchColumn();
$totalProducts = (int)$pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();

admin_header('Dashboard', 'dashboard');
?>
<div class="page-heading">
    <div><h2>Dashboard Overview</h2><p>Here is a quick summary of your MobileCare shop.</p></div>
</div>
<div class="stats-grid">
    <div class="stat-card"><div><div class="stat-label">Total Repair Enquiries</div><div class="stat-number"><?= $totalEnquiries ?></div></div><div class="stat-icon">▤</div></div>
    <div class="stat-card"><div><div class="stat-label">New Enquiries</div><div class="stat-number"><?= $newEnquiries ?></div></div><div class="stat-icon">◷</div></div>
    <div class="stat-card"><div><div class="stat-label">Total Products</div><div class="stat-number"><?= $totalProducts ?></div></div><div class="stat-icon">▣</div></div>
</div>
<div class="page-heading"><div><h2>Quick Actions</h2><p>Open a section to manage your website.</p></div></div>
<div class="quick-grid">
    <a class="quick-link" href="enquiries.php"><div class="stat-icon">▤</div><strong>Repair Enquiries</strong><span>View customer repair requests and update their progress.</span></a>
    <a class="quick-link" href="products.php"><div class="stat-icon">▣</div><strong>Manage Products</strong><span>Review products, edit details and change availability.</span></a>
    <a class="quick-link" href="product-form.php"><div class="stat-icon">＋</div><strong>Add New Product</strong><span>Add a product with its price, stock and optional image.</span></a>
</div>
<?php admin_footer(); ?>
