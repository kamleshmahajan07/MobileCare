
<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$allowedStatuses = [
    'New',
    'Contacted',
    'In Progress',
    'Completed',
    'Cancelled'
];

if (empty($_SESSION['enquiry_csrf'])) {
    $_SESSION['enquiry_csrf'] = bin2hex(random_bytes(32));
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    exit('Invalid enquiry ID.');
}

$stmt = $pdo->prepare(
    'SELECT * FROM repair_enquiries WHERE id = ?'
);
$stmt->execute([$id]);
$enquiry = $stmt->fetch();

if (!$enquiry) {
    http_response_code(404);
    exit('Enquiry not found.');
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    $status = $_POST['status'] ?? '';

    if (
        !is_string($token) ||
        !hash_equals($_SESSION['enquiry_csrf'], $token)
    ) {
        $message = 'Invalid request. Refresh the page and try again.';
    } elseif (!in_array($status, $allowedStatuses, true)) {
        $message = 'Invalid status selected.';
    } else {
        $update = $pdo->prepare(
            'UPDATE repair_enquiries
             SET status = ?, updated_at = CURRENT_TIMESTAMP
             WHERE id = ?'
        );
        $update->execute([$status, $id]);

        header('Location: enquiry-details.php?id=' . $id . '&updated=1');
        exit;
    }
}

$stmt = $pdo->prepare(
    'SELECT * FROM repair_enquiries WHERE id = ?'
);
$stmt->execute([$id]);
$enquiry = $stmt->fetch();

if (isset($_GET['updated'])) {
    $message = 'Enquiry status updated successfully.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Enquiry Details | MobileCare</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 30px auto;
            padding: 0 16px;
            color: #172033;
        }
        .panel {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 22px;
        }
        .field { margin: 16px 0; }
        label { font-weight: bold; display: block; margin-bottom: 6px; }
        select, button { padding: 10px; }
        button { cursor: pointer; }
    </style>
</head>
<body>
    <h1>Repair Enquiry Details</h1>
    <p><a href="enquiries.php">← Back to All Enquiries</a></p>

    <?php if ($message !== ''): ?>
        <p><?= e($message) ?></p>
    <?php endif; ?>

    <div class="panel">
        <div class="field">
            <label>Enquiry ID</label>
            <?= (int) $enquiry['id'] ?>
        </div>

        <div class="field">
            <label>Customer Name</label>
            <?= e($enquiry['customer_name']) ?>
        </div>

        <div class="field">
            <label>Phone Number</label>
            <?= e($enquiry['phone']) ?>
        </div>

        <div class="field">
            <label>Device Model</label>
            <?= e($enquiry['device_model']) ?>
        </div>

        <div class="field">
            <label>Problem Description</label>
            <?= nl2br(e($enquiry['problem_description'])) ?>
        </div>

        <div class="field">
            <label>Current Status</label>
            <?= e($enquiry['status']) ?>
        </div>

        <div class="field">
            <label>Created At</label>
            <?= e($enquiry['created_at']) ?>
        </div>

        <form method="POST">
            <input type="hidden" name="csrf_token"
                   value="<?= e($_SESSION['enquiry_csrf']) ?>">

            <div class="field">
                <label for="status">Update Status</label>
                <select name="status" id="status" required>
                    <?php foreach ($allowedStatuses as $status): ?>
                        <option value="<?= e($status) ?>"
                            <?= $enquiry['status'] === $status ? 'selected' : '' ?>>
                            <?= e($status) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit">Save Status</button>
        </form>
    </div>
</body>
</html>
