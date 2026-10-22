<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/_layout.php';

$stmt = $pdo->query('SELECT id, customer_name, phone, device_model, status, created_at FROM repair_enquiries ORDER BY created_at DESC');
$enquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);

function enquiry_status_class(string $status): string {
    $status = strtolower(str_replace(' ', '-', trim($status)));
    $allowed = ['new', 'contacted', 'in-progress', 'completed', 'cancelled'];
    return in_array($status, $allowed, true) ? 'status-' . $status : 'status-neutral';
}
admin_header('Repair Enquiries', 'enquiries');
?>
<div class="page-heading">
    <div><h2>Repair Enquiries</h2><p>Review customer requests and open a request to update its status.</p></div>
    <span class="status status-neutral"><?= count($enquiries) ?> total</span>
</div>
<div class="card" style="padding:0">
    <?php if (!$enquiries): ?>
        <div class="empty-state"><strong>No repair enquiries yet</strong>Customer requests will appear here when submitted.</div>
    <?php else: ?>
    <div class="table-wrap" style="border:0;border-radius:16px">
        <table>
            <thead><tr><th>Request</th><th>Customer</th><th>Phone</th><th>Device</th><th>Status</th><th>Received</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($enquiries as $enquiry): ?>
                <tr>
                    <td>#<?= (int)$enquiry['id'] ?></td>
                    <td><strong><?= admin_h($enquiry['customer_name']) ?></strong></td>
                    <td><?= admin_h($enquiry['phone']) ?></td>
                    <td><?= admin_h($enquiry['device_model'] ?: 'Not specified') ?></td>
                    <td><span class="status <?= enquiry_status_class((string)$enquiry['status']) ?>"><?= admin_h($enquiry['status']) ?></span></td>
                    <td><?= admin_h($enquiry['created_at']) ?></td>
                    <td><a class="btn btn-secondary" href="enquiry-details.php?id=<?= (int)$enquiry['id'] ?>">View Details →</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php admin_footer(); ?>
