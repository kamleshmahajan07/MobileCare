<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/_layout.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    http_response_code(400);
    exit('Invalid product ID.');
}
if (empty($_SESSION['product_edit_csrf'])) {
    $_SESSION['product_edit_csrf'] = bin2hex(random_bytes(32));
}
$stmt = $pdo->prepare('SELECT id, name, category, description, price, stock_quantity FROM products WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    http_response_code(404);
    exit('Product not found.');
}

$errors = [];
$name = trim((string)($_POST['name'] ?? $product['name']));
$category = trim((string)($_POST['category'] ?? $product['category']));
$description = trim((string)($_POST['description'] ?? $product['description']));
$price = trim((string)($_POST['price'] ?? $product['price']));
$stock = trim((string)($_POST['stock'] ?? $product['stock_quantity']));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals((string)$_SESSION['product_edit_csrf'], (string)($_POST['csrf_token'] ?? ''))) $errors[] = 'Security check failed. Refresh and try again.';
    if ($name === '') $errors[] = 'Product name is required.';
    if ($category === '') $errors[] = 'Category is required.';
    if ($price === '' || !is_numeric($price) || (float)$price < 0) $errors[] = 'Enter a valid non-negative price.';
    if ($stock === '' || filter_var($stock, FILTER_VALIDATE_INT) === false || (int)$stock < 0) $errors[] = 'Stock must be a non-negative whole number.';

    if (!$errors) {
        $update = $pdo->prepare('UPDATE products SET name = :name, category = :category, description = :description, price = :price, stock_quantity = :stock WHERE id = :id');
        $update->execute([
            'name' => $name,
            'category' => $category,
            'description' => $description,
            'price' => (float)$price,
            'stock' => (int)$stock,
            'id' => $id,
        ]);
        unset($_SESSION['product_edit_csrf']);
        header('Location: products.php?updated=1');
        exit;
    }
}
admin_header('Edit Product', 'products');
?>
<div class="page-heading"><div><h2>Edit Product #<?= (int)$id ?></h2><p>Update the product details below.</p></div><a class="btn btn-secondary" href="products.php">← Back to Products</a></div>
<?php if ($errors): ?><div class="alert alert-error"><?php foreach ($errors as $error): ?><div>• <?= admin_h($error) ?></div><?php endforeach; ?></div><?php endif; ?>
<div class="card">
<form method="post">
<input type="hidden" name="csrf_token" value="<?= admin_h($_SESSION['product_edit_csrf']) ?>">
<div class="form-grid">
<div class="form-group"><label for="name">Product Name *</label><input id="name" name="name" required maxlength="150" value="<?= admin_h($name) ?>"></div>
<div class="form-group"><label for="category">Category *</label><input id="category" name="category" required maxlength="100" value="<?= admin_h($category) ?>"></div>
<div class="form-group"><label for="price">Price (₹) *</label><input id="price" name="price" type="number" min="0" step="0.01" required value="<?= admin_h($price) ?>"></div>
<div class="form-group"><label for="stock">Stock Quantity *</label><input id="stock" name="stock" type="number" min="0" step="1" required value="<?= admin_h($stock) ?>"></div>
<div class="form-group full"><label for="description">Description</label><textarea id="description" name="description"><?= admin_h($description) ?></textarea></div>
</div>
<div class="form-actions"><button class="btn" type="submit">Save Changes</button><a class="btn btn-secondary" href="products.php">Cancel</a></div>
</form></div>
<?php admin_footer(); ?>
