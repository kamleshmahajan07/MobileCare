
<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: products.php');
    exit;
}

$token = $_POST['csrf_token'] ?? '';

if (
    !is_string($token) ||
    !isset($_SESSION['csrf_token']) ||
    !is_string($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $token)
) {
    http_response_code(403);
    exit('Invalid request. Please return and try again.');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    exit('Invalid product ID.');
}

$stmt = $pdo->prepare(
    'UPDATE products
     SET is_active = CASE WHEN is_active = 1 THEN 0 ELSE 1 END,
         updated_at = CURRENT_TIMESTAMP
     WHERE id = ?'
);

$stmt->execute([$id]);

header('Location: products.php?status_changed=1');
exit;
