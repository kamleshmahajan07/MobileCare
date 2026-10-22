<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/_layout.php';

$allowedStatuses = ['New', 'Contacted', 'In Progress', 'Completed', 'Cancelled'];
if (!isset($_SESSION['enquiry_csrf'])) {
    $_SESSION['enquiry_csrf'] = bin2hex(random_bytes(32));
}
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    http_response_code(400);
    exit('Invalid enquiry ID.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = (string)($_POST['csrf_token'] ?? '');
    $status = (string)($_POST['status'] ?? '');
    if (!hash_equals((string)$_SESSION['enquiry_csrf'], $token)) {
        $error = 'Security check failed. Please refresh the page and try again.';
    } elseif (!in_array($status, $allowedStatuses, true)) {
        $error = 'Please select a valid status.';
    } else {
        $update = $pdo->prepare('UPDATE repair_enquiries SET status = :status, updated_at = NOW() WHERE id = :id');
        $update->execute(['status' => $status, 'id' => $id]);
        header('Location: enquiry-details.php?id=' . $id . '&updated=1');
        exit;
    }
}

$stmt = $pdo->prepare('SELECT * FROM repair_enquiries WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $id]);
$enquiry = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$enquiry) {
    http_response_code(404);
    exit('Enquiry not found.');
}
admin_header('Enquiry Details', 'enquiries');
?>
<div class="page-heading">
    <div><h2>Request #<?= (int)$enquiry['id'] ?></h2><p>Review the submitted information and update request progress.</p></div>
    <a class="btn btn-secondary" href="enquiries.php">← Back to Enquiries</a>
</div>
<?php if (isset($_GET['updated'])): ?><div class="alert alert-success">Enquiry status updated successfully.</div><?php endif; ?>
<?php if (!empty($error)): ?><div class="alert alert-error"><?= admin_h($error) ?></div><?php endif; ?>
<div class="card" style="margin-bottom:20px">
    <div class="page-heading"><div><h2 style="font-size:18px">Customer Information</h2><p>Details received from the repair enquiry form.</p></div><span class="status status-neutral"><?= admin_h($enquiry['status'] ?? 'New') ?></span></div>
    <div class="detail-grid">
        <?php
        $fields = [
            'Customer Name' => $enquiry['customer_name'] ?? '',
            'Phone' => $enquiry['phone'] ?? '',
            'Email' => $enquiry['email'] ?? '',
            'Device Model' => $enquiry['device_model'] ?? '',
            'Repair Service' => $enquiry['service_type'] ?? '',
            'Created At' => $enquiry['created_at'] ?? '',
            'Description' => $enquiry['message'] ?? ($enquiry['issue_description'] ?? ''),
        ];
        foreach ($fields as $label => $value):
        ?>
        <div class="detail-item"><small><?= admin_h($label) ?></small><div><?= nl2br(admin_h($value !== '' ? $value : 'Not provided')) ?></div></div>
        <?php endforeach; ?>
    </div>
</div>
<div class="card">
    <h2 style="font-size:18px;margin:0 0 7px">Update Status</h2>
    <p style="color:var(--muted);font-size:13px;margin:0 0 18px">Choose the latest status for this repair request.</p>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= admin_h($_SESSION['enquiry_csrf']) ?>">
        <div class="form-group" style="max-width:420px">
            <label for="status">Enquiry Status</label>
            <select name="status" id="status" required>
                <?php foreach ($allowedStatuses as $statusOption): ?>
                    <option value="<?= admin_h($statusOption) ?>" <?= (($enquiry['status'] ?? 'New') === $statusOption) ? 'selected' : '' ?>><?= admin_h($statusOption) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-actions"><button class="btn" type="submit">Save Status</button><a class="btn btn-secondary" href="enquiries.php">Cancel</a></div>
    </form>
</div>
<?php admin_footer(); ?>
