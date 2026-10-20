
<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    exit('Invalid product ID.');
}

$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    exit('Product not found.');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token) ||
        !isset($_SESSION['csrf_token']) ||
        !is_string($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $token)
    ) {
        $error = 'Invalid request. Please refresh and try again.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $priceInput = $_POST['price'] ?? '';
        $stockInput = $_POST['stock_quantity'] ?? '';

        if (
            $name === '' ||
            $category === '' ||
            !is_numeric($priceInput) ||
            !is_numeric($stockInput) ||
            (float) $priceInput < 0 ||
            (float) $priceInput > 99999999999999 ||
            filter_var($stockInput, FILTER_VALIDATE_INT) === false ||
            (int) $stockInput < 0
        ) {
            $error = 'Please enter valid product details.';
        } else {
            $price = (float) $priceInput;
            $stock = (int) $stockInput;

            $update = $pdo->prepare(
                'UPDATE products
                 SET name = ?, category = ?, description = ?,
                     price = ?, stock_quantity = ?,
                     updated_at = CURRENT_TIMESTAMP
                 WHERE id = ?'
            );

            $update->execute([
                $name,
                $category,
                $description,
                $price,
                $stock,
                $id
            ]);

            header('Location: products.php?updated=1');
            exit;
        }
    }
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Product | MobileCare</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 650px;
            margin: 30px auto;
            padding: 0 15px;
            background: #f4f7fb;
            color: #172033;
        }
        form {
            background: white;
            padding: 22px;
            border-radius: 10px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input, textarea {
            box-sizing: border-box;
            width: 100%;
            padding: 10px;
            margin-top: 6px;
        }
        button {
            margin-top: 20px;
            padding: 11px 18px;
            background: #155eef;
            color: white;
            border: 0;
            border-radius: 5px;
            cursor: pointer;
        }
        .error { color: #b42318; }
    </style>
</head>
<body>
    <h1>Edit Product</h1>
    <p><a href="products.php">Back to Products</a></p>

    <?php if ($error !== ''): ?>
        <p class="error"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <input
            type="hidden"
            name="csrf_token"
            value="<?= e($_SESSION['csrf_token']) ?>"
        >

        <label for="name">Product Name</label>
        <input
            id="name"
            name="name"
            required
            maxlength="150"
            value="<?= e($_POST['name'] ?? $product['name']) ?>"
        >

        <label for="category">Category</label>
        <input
            id="category"
            name="category"
            required
            maxlength="100"
            value="<?= e($_POST['category'] ?? $product['category']) ?>"
        >

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4"><?= e($_POST['description'] ?? $product['description']) ?></textarea>

        <label for="price">Price (₹)</label>
        <input
            id="price"
            type="number"
            name="price"
            min="0"
            max="99999999999999"
            step="0.01"
            required
            value="<?= e($_POST['price'] ?? $product['price']) ?>"
        >

        <label for="stock_quantity">Stock Quantity</label>
        <input
            id="stock_quantity"
            type="number"
            name="stock_quantity"
            min="0"
            step="1"
            required
            value="<?= e($_POST['stock_quantity'] ?? $product['stock_quantity']) ?>"
        >

        <button type="submit">Save Changes</button>
    </form>
</body>
</html>
