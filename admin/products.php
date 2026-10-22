<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/_layout.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$stmt = $pdo->query('SELECT id, name, category, price, image, stock_quantity, is_active FROM products ORDER BY id DESC');
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

admin_header('Manage Products', 'products');
?>
<div class="page-heading"><div><h2>Manage Products</h2><p>Review product information, edit details or change availability.</p></div><a class="btn" href="product-form.php">＋ Add Product</a></div>
<?php if (isset($_GET['added'])): ?><div class="alert alert-success">Product added successfully.</div><?php endif; ?>
<?php if (isset($_GET['updated'])): ?><div class="alert alert-success">Product updated successfully.</div><?php endif; ?>
<?php if (isset($_GET['status_changed'])): ?><div class="alert alert-success">Product status updated successfully.</div><?php endif; ?>
<?php if (isset($_GET['error']) && $_GET['error'] === 'csrf'): ?><div class="alert alert-error">Security check failed. Refresh the page and try again.</div><?php endif; ?>
<?php if (isset($_GET['error']) && $_GET['error'] === 'invalid'): ?><div class="alert alert-error">Invalid product request.</div><?php endif; ?>
<div class="card" style="padding:0">
<?php if (!$products): ?>
<div class="empty-state"><strong>No products found</strong>Add your first product to start managing your catalogue.<p><a class="btn" href="product-form.php">Add Product</a></p></div>
<?php else: ?>
<div class="table-wrap" style="border:0;border-radius:16px"><table>
<thead><tr><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
<?php foreach ($products as $product): ?>
<tr>
<td><div style="display:flex;align-items:center;gap:12px">
<?php
$image = trim((string)($product['image'] ?? ''));
$imgSrc = '';
if ($image !== '') {
    $imgSrc = (preg_match('~^https?://~i', $image) || str_starts_with($image, '/')) ? $image : '../' . ltrim($image, '/');
}
?>
<?php if ($imgSrc !== ''): ?><img class="product-thumb" src="<?= admin_h($imgSrc) ?>" alt="<?= admin_h($product['name']) ?>" onerror="this.style.display='none'"><?php else: ?><div class="product-thumb" style="display:grid;place-items:center;color:#64748b;font-size:20px">▣</div><?php endif; ?>
<div><strong><?= admin_h($product['name']) ?></strong><div style="color:var(--muted);font-size:11px;margin-top:4px">ID #<?= (int)$product['id'] ?></div></div>
</div></td>
<td><?= admin_h($product['category']) ?></td>
<td>₹<?= number_format((float)$product['price'], 2) ?></td>
<td><?= (int)$product['stock_quantity'] ?></td>
<td><span class="status <?= (int)$product['is_active'] === 1 ? 'status-active' : 'status-inactive' ?>"><?= (int)$product['is_active'] === 1 ? 'Active' : 'Inactive' ?></span></td>
<td><div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
<a class="btn btn-secondary" href="product-edit.php?id=<?= (int)$product['id'] ?>">Edit</a>
<form method="post" action="product-toggle.php" onsubmit="return confirm('Change this product availability?');">
<input type="hidden" name="csrf_token" value="<?= admin_h($_SESSION['csrf_token']) ?>">
<input type="hidden" name="id" value="<?= (int)$product['id'] ?>">
<button class="btn <?= (int)$product['is_active'] === 1 ? 'btn-danger' : '' ?>" type="submit" style="<?= (int)$product['is_active'] === 1 ? '' : 'background:#dcfce7;color:#166534' ?>"><?= (int)$product['is_active'] === 1 ? 'Deactivate' : 'Activate' ?></button>
</form>
</div></td>
</tr>
<?php endforeach; ?>
</tbody></table></div>
<?php endif; ?>
</div>
<?php admin_footer(); ?>
