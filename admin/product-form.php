
<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$error = '';

if (empty($_SESSION['product_csrf'])) {
    $_SESSION['product_csrf'] = bin2hex(random_bytes(32));
}

$name = '';
$category = '';
$description = '';
$price = '';
$stock = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $stock = trim($_POST['stock_quantity'] ?? '');

    $validPrice = $price !== '' &&
        is_numeric($price) &&
        is_finite((float) $price) &&
        (float) $price >= 0 &&
        (float) $price <= 999999999;

    $validStock = $stock !== '' &&
        ctype_digit($stock) &&
        (float) $stock <= 2147483647;

    $imageName = null;
    $uploadedPath = null;

    if (!is_string($token) ||
        !hash_equals($_SESSION['product_csrf'], $token)) {
        $error = 'Invalid request. Refresh the page and try again.';
    } elseif (
        $name === '' || strlen($name) > 150 ||
        $category === '' || strlen($category) > 100 ||
        strlen($description) > 5000 ||
        !$validPrice || !$validStock
    ) {
        $error = 'Please check all product details and try again.';
    } else {
        $file = $_FILES['image'] ?? null;

        if ($file && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $error = 'Image upload failed. Please try again.';
            } elseif ($file['size'] > 2 * 1024 * 1024) {
                $error = 'Image must be 2 MB or smaller.';
            } else {
                $tmpPath = $file['tmp_name'];
                $imageInfo = @getimagesize($tmpPath);
                $mime = $imageInfo['mime'] ?? '';

                $allowedTypes = [
                    'image/jpeg' => 'jpg',
                    'image/png'  => 'png',
                    'image/webp' => 'webp',
                ];

                if (
                    $imageInfo === false ||
                    !isset($allowedTypes[$mime]) ||
                    !is_uploaded_file($tmpPath)
                ) {
                    $error = 'Only valid JPG, PNG or WebP images are allowed.';
                } else {
                    $imageName = bin2hex(random_bytes(16))
                        . '.' . $allowedTypes[$mime];

                    $imageDirectory = __DIR__
                        . '/../assets/images/products/';

                    if (
                        !is_dir($imageDirectory) &&
                        !mkdir($imageDirectory, 0755, true)
                    ) {
                        $error = 'Could not create the product image folder.';
                    } elseif (!is_writable($imageDirectory)) {
                        $error = 'Product image folder is not writable.';
                    } else {
                        $uploadedPath = $imageDirectory . $imageName;

                        if (!move_uploaded_file($tmpPath, $uploadedPath)) {
                            $error = 'Could not save the uploaded image.';
                            $uploadedPath = null;
                        }
                    }
                }
            }
        }

        if ($error === '') {
            try {
                $stmt = $pdo->prepare(
                    'INSERT INTO products
                     (name, category, description, price,
                      stock_quantity, image, is_active)
                     VALUES (?, ?, ?, ?, ?, ?, 1)'
                );

                $stmt->execute([
                    $name,
                    $category,
                    $description,
                    (float) $price,
                    (int) $stock,
                    $imageName
                ]);

                header('Location: products.php?added=1');
                exit;
            } catch (Throwable $exception) {
                if ($uploadedPath !== null && is_file($uploadedPath)) {
                    unlink($uploadedPath);
                }

                error_log($exception->getMessage());
                $error = 'Could not save product. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Product | MobileCare</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 700px;
            margin: 30px auto;
            padding: 0 16px;
            color: #172033;
            background: #f4f7fb;
        }
        form {
            background: white;
            padding: 22px;
            border-radius: 10px;
        }
        label {
            display: block;
            margin-top: 16px;
        }
        input, textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            margin-top: 6px;
        }
        button {
            margin-top: 20px;
            padding: 11px 18px;
            cursor: pointer;
        }
        .error { color: #b42318; }
    </style>
</head>
<body>
    <h1>Add New Product</h1>
    <p><a href="products.php">← Back to Products</a></p>

    <?php if ($error !== ''): ?>
        <p class="error"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token"
               value="<?= e($_SESSION['product_csrf']) ?>">

        <label for="name">Product Name</label>
        <input id="name" name="name" maxlength="150"
               value="<?= e($name) ?>" required>

        <label for="category">Category</label>
        <input id="category" name="category" maxlength="100"
               value="<?= e($category) ?>"
               placeholder="Charger, Cable, Case..." required>

        <label for="description">Description</label>
        <textarea id="description" name="description"
                  maxlength="5000" rows="4"><?= e($description) ?></textarea>

        <label for="price">Price (₹)</label>
        <input id="price" name="price" type="number"
               min="0" max="999999999" step="0.01"
               value="<?= e($price) ?>" required>

        <label for="stock_quantity">Stock Quantity</label>
        <input id="stock_quantity" name="stock_quantity"
               type="number" min="0" max="2147483647" step="1"
               value="<?= e($stock) ?>" required>

        <label for="image">Product Image (Optional)</label>
        <input id="image" name="image" type="file"
               accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        <small>JPG, PNG or WebP — maximum 2 MB.</small>

        <br>
        <button type="submit">Save Product</button>
    </form>
</body>
</html>
