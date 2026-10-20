<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$stmt = $pdo->query(
    'SELECT id, customer_name, phone, device_model, status, created_at
     FROM repair_enquiries
     ORDER BY created_at DESC'
);

$enquiries = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Enquiries | MobileCare</title>
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
        th {
            background: #eaf0f8;
        }
        a {
            color: #155eef;
        }
    </style>
</head>
<body>
    <h1>Repair Enquiries</h1>

    <p>
        <a href="dashboard.php">← Back to Dashboard</a> |
        <a href="logout.php">Logout</a>
    </p>

    <p>Total Enquiries: <?= count($enquiries) ?></p>

    <?php if (empty($enquiries)): ?>
        <p>No repair enquiries found.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Device</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enquiries as $enquiry): ?>
                        <tr>
                            <td><?= (int) $enquiry['id'] ?></td>
                            <td><?= e($enquiry['customer_name']) ?></td>
                            <td><?= e($enquiry['phone']) ?></td>
                            <td><?= e($enquiry['device_model']) ?></td>
                            <td><?= e($enquiry['status']) ?></td>
                            <td><?= e($enquiry['created_at']) ?></td>
                            <td>
    <a href="enquiry-details.php?id=<?= (int) $enquiry['id'] ?>">
        View Details
    </a>
</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</body>
</html>
