<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/_layout.php';

if (empty($_SESSION['product_csrf'])) {
    $_SESSION['product_csrf'] = bin2hex(random_bytes(32));
}
$errors = [];
$name = trim((string)($_POST['name'] ?? ''));
$category = trim((string)($_POST['category'] ?? ''));
$description = trim((string)($_POST['description'] ?? ''));
$price = trim((string)($_POST['price'] ?? ''));
$stock = trim((string)($_POST['stock'] ?? ''));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals((string)$_SESSION['product_csrf'], (string)($_POST['csrf_token'] ?? ''))) {
        $errors[] = 'Security check failed. Refresh the page and try again.';
    }
    if ($name === '') $errors[] = 'Product name is required.';
    if ($category === '') $errors[] = 'Category is required.';
    if ($price === '' || !is_numeric($price) || (float)$price < 0) $errors[] = 'Enter a valid non-negative price.';
    if ($stock === '' || filter_var($stock, FILTER_VALIDATE_INT) === false || (int)$stock < 0) $errors[] = 'Stock must be a non-negative whole number.';

    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Image upload failed. Please choose the image again.';
        } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Image must be 2 MB or smaller.';
        } else {
            $tmp = $_FILES['image']['tmp_name'];
            $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);
            $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
            if (!isset($allowed[$mime]) || @getimagesize($tmp) === false) {
                $errors[] = 'Only JPG, PNG or WebP image files are allowed.';
            } else {
                $uploadDir = __DIR__ . '/../assets/images/products/';
                if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                    $errors[] = 'Could not create the product image folder.';
                } else {
                    $filename = bin2hex(random_bytes(12)) . '.' . $allowed[$mime];
                    if (move_uploaded_file($tmp, $uploadDir . $filename)) {
                        $imagePath = 'assets/images/products/' . $filename;
                    } else {
                        $errors[] = 'Could not save the uploaded image.';
                    }
                }
            }
        }
    }

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO products (name, category, description, price, stock_quantity, image, is_active) VALUES (:name, :category, :description, :price, :stock, :image, 1)');
        $stmt->execute([
            'name' => $name,
            'category' => $category,
            'description' => $description,
            'price' => (float)$price,
            'stock' => (int)$stock,
            'image' => $imagePath,
        ]);
        unset($_SESSION['product_csrf']);
        header('Location: products.php?added=1');
        exit;
    }
}
admin_header('Add Product', 'add-product');
?>
<div class="page-heading"><div><h2>Add New Product</h2><p>Enter product information. Adding an image is optional.</p></div><a class="btn btn-secondary" href="products.php">← Back to Products</a></div>
<?php if ($errors): ?><div class="alert alert-error"><?php foreach ($errors as $error): ?><div>• <?= admin_h($error) ?></div><?php endforeach; ?></div><?php endif; ?>
<div class="card">
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="csrf_token" value="<?= admin_h($_SESSION['product_csrf']) ?>">
<div class="form-grid">
<div class="form-group"><label for="name">Product Name *</label><input id="name" name="name" required maxlength="150" value="<?= admin_h($name) ?>"></div>
<div class="form-group"><label for="category">Category *</label><input id="category" name="category" required maxlength="100" value="<?= admin_h($category) ?>" placeholder="e.g. Chargers, Covers"></div>
<div class="form-group"><label for="price">Price (₹) *</label><input id="price" name="price" type="number" min="0" step="0.01" required value="<?= admin_h($price) ?>"></div>
<div class="form-group"><label for="stock">Stock Quantity *</label><input id="stock" name="stock" type="number" min="0" step="1" required value="<?= admin_h($stock) ?>"></div>
<div class="form-group full"><label for="description">Description</label><textarea id="description" name="description"><?= admin_h($description) ?></textarea></div>
<div class="form-group full"><label for="image">Product Image (JPG, PNG, WebP; max 2 MB)</label><input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"><small style="color:var(--muted)">If you skip the image, the product can still be saved.</small></div>
</div>
<div class="form-actions"><button class="btn" type="submit">Save Product</button><a class="btn btn-secondary" href="products.php">Cancel</a></div>
</form></div>
<?php admin_footer(); ?>
