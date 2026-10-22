<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}
if (empty($_SESSION['login_csrf'])) {
    $_SESSION['login_csrf'] = bin2hex(random_bytes(32));
}
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = (string)($_POST['csrf_token'] ?? '');
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if (!hash_equals((string)$_SESSION['login_csrf'], $token)) {
        $error = 'Security check failed. Please refresh and try again.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Please enter a valid email and password.';
    } else {
        $stmt = $pdo->prepare('SELECT id, name, email, password_hash FROM admins WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int)$admin['id'];
            $_SESSION['admin_name'] = $admin['name'] ?? 'Administrator';
            unset($_SESSION['login_csrf']);
            header('Location: dashboard.php');
            exit;
        }
        $error = 'Email or password is incorrect.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login | MobileCare</title>
<style>
:root{--navy:#14213d;--blue:#2563eb;--bg:#f4f7fb;--line:#e2e8f0;--muted:#64748b}
*{box-sizing:border-box}body{margin:0;font-family:Arial,Helvetica,sans-serif;color:#172033}
.login-page{min-height:100vh;display:grid;place-items:center;padding:24px;background:radial-gradient(circle at top left,#dbeafe,transparent 40%),var(--bg)}
.login-card{width:100%;max-width:430px;background:#fff;border:1px solid var(--line);border-radius:20px;padding:32px;box-shadow:0 20px 60px #0f172a12}
.brand{display:flex;align-items:center;gap:12px;margin-bottom:25px}.brand-mark{width:46px;height:46px;border-radius:14px;background:var(--blue);color:white;display:grid;place-items:center;font-size:24px;font-weight:bold}.brand strong{display:block;font-size:19px}.brand small{display:block;color:var(--muted);margin-top:4px}
h1{font-size:25px;margin:0 0 8px}.muted{color:var(--muted);font-size:13px;line-height:1.6;margin:0 0 23px}
.form-group{display:flex;flex-direction:column;gap:7px;margin-bottom:16px}label{font-size:13px;font-weight:700;color:#334155}
input{width:100%;border:1px solid #cbd5e1;border-radius:9px;padding:12px 13px;font:inherit;font-size:14px}input:focus{outline:3px solid #dbeafe;border-color:#60a5fa}
button{width:100%;border:0;border-radius:9px;padding:13px;background:var(--blue);color:white;font-weight:700;cursor:pointer;margin-top:5px}button:hover{background:#1d4ed8}
.alert{padding:13px 15px;border-radius:10px;margin-bottom:18px;font-size:13px;background:#fee2e2;color:#991b1b;border:1px solid #fecaca}
.footer-note{font-size:12px;color:var(--muted);margin-top:22px;text-align:center}
</style></head>
<body><main class="login-page"><section class="login-card">
<div class="brand"><div class="brand-mark">M</div><div><strong>MobileCare</strong><small>Admin Portal</small></div></div>
<h1>Welcome back</h1><p class="muted">Sign in to manage repair enquiries and shop products.</p>
<?php if ($error !== ''): ?><div class="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<form method="post" action="login.php">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['login_csrf'], ENT_QUOTES, 'UTF-8') ?>">
<div class="form-group"><label for="email">Admin Email</label><input id="email" type="email" name="email" autocomplete="username" required value="<?= htmlspecialchars((string)($_POST['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></div>
<div class="form-group"><label for="password">Password</label><input id="password" type="password" name="password" autocomplete="current-password" required></div>
<button type="submit">Sign In →</button>
</form><div class="footer-note">Authorised administrators only.</div>
</section></main></body></html>
