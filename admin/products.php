
<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$stmt = $pdo->query(
    'SELECT id, name, category, price, stock_quantity, is_active
     FROM products
     ORDER BY id DESC'
);

$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Products | MobileCare</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f4f7fb;
            color: #172033;
        }
        .table-wrap {
            overflow-x: auto;
            background: white;
            padding: 15px;
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        a { color: #155eef; }
        .action { white-space: nowrap; }
        .status { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Manage Products</h1>

    <p>
        <a href="dashboard.php">Dashboard</a> |
        <a href="product-form.php">+ Add New Product</a> |
        <a href="logout.php">Logout</a>
    </p>

    <?php if (isset($_GET['added'])): ?>
        <p>Product added successfully!</p>
    <?php endif; ?>

    <?php if (isset($_GET['updated'])): ?>
        <p>Product updated successfully!</p>
    <?php endif; ?>

    <?php if (isset($_GET['status_changed'])): ?>
        <p>Product status changed successfully!</p>
    <?php endif; ?>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= (int) $product['id'] ?></td>
                        <td><?= e($product['name']) ?></td>
                        <td><?= e($product['category']) ?></td>
                        <td><?= e(formatPrice($product['price'])) ?></td>
                        <td><?= (int) $product['stock_quantity'] ?></td>
                        <td class="status">
                            <?= (int) $product['is_active'] === 1
                                ? 'Yes' : 'No' ?>
                        </td>
                        <td class="action">
                            <a href="product-edit.php?id=<?= (int) $product['id'] ?>">
                                Edit
                            </a>
                            |
                           <form method="post" action="product-toggle.php"
      style="display:inline">
    <input type="hidden" name="id"
           value="<?= (int) $product['id'] ?>">
    <input type="hidden" name="csrf_token"
           value="<?= e($_SESSION['csrf_token']) ?>">

    <button type="submit">
        <?= (int) $product['is_active'] === 1
            ? 'Deactivate' : 'Activate' ?>
    </button>
</form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if (!$products): ?>
        <p>No products found.</p>
    <?php endif; ?>
</body>
</html>
