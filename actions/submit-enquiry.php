<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../repair-booking.php');
    exit;
}

$name = trim($_POST['customer_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$device = trim($_POST['device_model'] ?? '');
$description = trim($_POST['problem_description'] ?? '');
$serviceInput = $_POST['service_id'] ?? '';

$serviceId = null;

if ($serviceInput !== '') {
    $validatedServiceId = filter_var(
        $serviceInput,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    if ($validatedServiceId === false) {
        header('Location: ../repair-booking.php?error=1');
        exit;
    }

    $serviceId = $validatedServiceId;
}

if (
    $name === '' || strlen($name) > 100 ||
    $phone === '' || strlen($phone) > 20 ||
    $device === '' || strlen($device) > 100 ||
    $description === '' || strlen($description) > 2000
) {
    header('Location: ../repair-booking.php?error=1');
    exit;
}

try {
    if ($serviceId !== null) {
        $check = $pdo->prepare(
            'SELECT id FROM repair_services
             WHERE id = ? AND is_active = 1'
        );
        $check->execute([$serviceId]);

        if (!$check->fetch()) {
            header('Location: ../repair-booking.php?error=1');
            exit;
        }
    }

    $stmt = $pdo->prepare(
        'INSERT INTO repair_enquiries
         (customer_name, phone, device_model, problem_description, service_id)
         VALUES (?, ?, ?, ?, ?)'
    );

    $stmt->execute([
        $name,
        $phone,
        $device,
        $description,
        $serviceId
    ]);

    header('Location: ../repair-booking.php?success=1');
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: ../repair-booking.php?error=1');
    exit;
}
