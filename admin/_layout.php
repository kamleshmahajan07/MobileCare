<?php
// Shared visual layout for admin pages. Keep database/auth logic in each page.
if (!function_exists('admin_h')) {
    function admin_h($value): string {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

function admin_header(string $pageTitle, string $active = ''): void {
    $adminName = $_SESSION['admin_name'] ?? 'Administrator';
    $nav = [
        'dashboard' => ['Dashboard', 'dashboard.php', '⌂'],
        'enquiries' => ['Repair Enquiries', 'enquiries.php', '▤'],
        'products' => ['Manage Products', 'products.php', '▣'],
        'add-product' => ['Add Product', 'product-form.php', '+'],
    ];
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= admin_h($pageTitle) ?> | MobileCare Admin</title>
        <style>
            :root{--navy:#14213d;--navy2:#1f3157;--blue:#2563eb;--bg:#f4f7fb;--text:#172033;--muted:#64748b;--line:#e2e8f0;--white:#fff;--green:#15803d;--amber:#a16207;--red:#b91c1c}
            *{box-sizing:border-box}
            body{margin:0;background:var(--bg);color:var(--text);font-family:Arial,Helvetica,sans-serif}
            a{color:var(--blue);text-decoration:none}
            .admin-shell{display:flex;min-height:100vh}
            .admin-sidebar{width:245px;background:linear-gradient(180deg,var(--navy),#0b1327);color:#fff;padding:24px 16px;display:flex;flex-direction:column;flex-shrink:0}
            .brand{display:flex;gap:12px;align-items:center;padding:0 8px 25px;border-bottom:1px solid #ffffff20;margin-bottom:22px}
            .brand-mark{width:42px;height:42px;border-radius:13px;background:#2563eb;display:grid;place-items:center;font-size:22px;font-weight:bold}
            .brand strong{display:block;font-size:18px}.brand small{display:block;color:#b9c5dc;margin-top:4px}
            .nav-label{font-size:11px;text-transform:uppercase;letter-spacing:1.4px;color:#91a2c2;padding:0 10px;margin:7px 0 10px}
            .admin-nav{display:grid;gap:7px}
            .admin-nav a{display:flex;align-items:center;gap:12px;padding:12px 13px;border-radius:10px;color:#dce5f5;font-size:14px}
            .admin-nav a:hover,.admin-nav a.active{background:#ffffff18;color:#fff}
            .nav-icon{width:22px;text-align:center;font-size:18px}
            .sidebar-bottom{margin-top:auto;border-top:1px solid #ffffff20;padding:18px 8px 0;color:#b9c5dc;font-size:12px;line-height:1.6}
            .admin-main{flex:1;min-width:0}
            .topbar{background:#fff;border-bottom:1px solid var(--line);padding:18px 30px;display:flex;align-items:center;justify-content:space-between;gap:16px}
            .topbar h1{font-size:21px;margin:0}.topbar p{margin:5px 0 0;color:var(--muted);font-size:13px}
            .admin-user{display:flex;align-items:center;gap:10px;font-size:13px;color:#475569}
            .avatar{width:36px;height:36px;border-radius:50%;display:grid;place-items:center;background:#dbeafe;color:#1d4ed8;font-weight:bold}
            .admin-content{padding:28px 30px;max-width:1500px;margin:auto}
            .page-heading{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:22px;flex-wrap:wrap}
            .page-heading h2{font-size:24px;margin:0 0 7px}.page-heading p{color:var(--muted);margin:0;font-size:14px;line-height:1.5}
            .card{background:#fff;border:1px solid var(--line);border-radius:16px;padding:22px;box-shadow:0 4px 18px #0f172a08}
            .stats-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px;margin-bottom:22px}
            .stat-card{background:#fff;border:1px solid var(--line);border-radius:15px;padding:21px;display:flex;justify-content:space-between;gap:12px;align-items:center}
            .stat-label{font-size:13px;color:var(--muted);margin-bottom:9px}.stat-number{font-size:30px;font-weight:750;letter-spacing:-1px}
            .stat-icon{width:48px;height:48px;border-radius:14px;display:grid;place-items:center;background:#dbeafe;color:#1d4ed8;font-size:23px}
            .quick-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}
            .quick-link{display:block;padding:19px;border:1px solid var(--line);border-radius:13px;background:#fff;color:var(--text)}
            .quick-link:hover{border-color:#93c5fd;box-shadow:0 8px 20px #2563eb12}
            .quick-link strong{display:block;margin:10px 0 6px}.quick-link span{font-size:13px;color:var(--muted);line-height:1.5}
            .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;border:0;border-radius:9px;padding:11px 15px;font-weight:700;font-size:13px;cursor:pointer;background:var(--blue);color:#fff}
            .btn:hover{background:#1d4ed8}.btn-secondary{background:#eaf0f8;color:#24334d}.btn-secondary:hover{background:#dce6f3}
            .btn-danger{background:#fee2e2;color:#991b1b}.btn-danger:hover{background:#fecaca}
            .table-wrap{overflow-x:auto;border:1px solid var(--line);border-radius:12px;background:#fff}
            table{border-collapse:collapse;width:100%;min-width:760px}
            th,td{text-align:left;padding:14px 16px;border-bottom:1px solid #edf1f6;font-size:13px;vertical-align:middle}
            th{background:#f8fafc;color:#64748b;font-size:11px;text-transform:uppercase;letter-spacing:.7px;white-space:nowrap}
            tr:last-child td{border-bottom:0}tbody tr:hover{background:#fbfdff}
            .status{display:inline-block;border-radius:999px;padding:6px 10px;font-size:11px;font-weight:700;white-space:nowrap}
            .status-new{background:#dbeafe;color:#1d4ed8}.status-contacted{background:#fef3c7;color:#92400e}.status-in-progress{background:#ede9fe;color:#6d28d9}.status-completed,.status-active{background:#dcfce7;color:#166534}.status-cancelled,.status-inactive{background:#fee2e2;color:#991b1b}.status-neutral{background:#e2e8f0;color:#334155}
            .form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}
            .form-group{display:flex;flex-direction:column;gap:7px}.form-group.full{grid-column:1/-1}
            label{font-size:13px;font-weight:700;color:#334155}
            input,select,textarea{width:100%;border:1px solid #cbd5e1;border-radius:9px;padding:12px 13px;font:inherit;font-size:14px;background:#fff;color:#172033}
            input:focus,select:focus,textarea:focus{outline:3px solid #dbeafe;border-color:#60a5fa}
            textarea{min-height:115px;resize:vertical}
            .form-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:20px}
            .alert{padding:13px 15px;border-radius:10px;margin-bottom:18px;font-size:13px;line-height:1.5}
            .alert-success{background:#dcfce7;color:#166534;border:1px solid #bbf7d0}.alert-error{background:#fee2e2;color:#991b1b;border:1px solid #fecaca}.alert-info{background:#dbeafe;color:#1e40af;border:1px solid #bfdbfe}
            .empty-state{text-align:center;padding:38px 18px;color:var(--muted)}.empty-state strong{display:block;color:var(--text);font-size:16px;margin-bottom:7px}
            .detail-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:15px}
            .detail-item{padding:15px;background:#f8fafc;border:1px solid #edf1f6;border-radius:10px;min-width:0}
            .detail-item small{display:block;color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:.6px;margin-bottom:7px}
            .detail-item div{font-size:14px;overflow-wrap:anywhere;line-height:1.5}
            .product-thumb{width:52px;height:52px;object-fit:contain;background:#f8fafc;border:1px solid var(--line);border-radius:9px;padding:4px}
            .login-page{min-height:100vh;display:grid;place-items:center;padding:24px;background:radial-gradient(circle at top left,#dbeafe,transparent 40%),var(--bg)}
            .login-card{width:100%;max-width:430px;background:#fff;border:1px solid var(--line);border-radius:20px;padding:32px;box-shadow:0 20px 60px #0f172a12}
            .login-logo{display:flex;align-items:center;gap:12px;margin-bottom:25px}.login-card h1{font-size:25px;margin:0 0 8px}.login-card .muted{color:var(--muted);font-size:13px;line-height:1.6;margin:0 0 23px}
            .login-card .form-group{margin-bottom:16px}
            .footer-note{font-size:12px;color:var(--muted);margin-top:22px;text-align:center}
            @media(max-width:900px){.admin-sidebar{width:205px}.admin-content{padding:22px 18px}.topbar{padding:16px 18px}.stats-grid,.quick-grid{grid-template-columns:1fr 1fr}}
            @media(max-width:650px){.admin-shell{display:block}.admin-sidebar{width:100%;padding:15px}.brand{padding-bottom:14px;margin-bottom:12px}.admin-nav{grid-template-columns:1fr 1fr}.nav-label,.sidebar-bottom{display:none}.admin-nav a{padding:10px;font-size:12px}.topbar{padding:15px}.topbar h1{font-size:18px}.admin-user .user-name{display:none}.admin-content{padding:18px 13px}.stats-grid,.quick-grid,.form-grid,.detail-grid{grid-template-columns:1fr}.page-heading h2{font-size:21px}.card{padding:16px}}
        </style>
    </head>
    <body>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="brand">
                <div class="brand-mark">M</div>
                <div><strong>MobileCare</strong><small>Administration</small></div>
            </div>
            <div class="nav-label">Workspace</div>
            <nav class="admin-nav">
                <?php foreach ($nav as $key => $item): ?>
                    <a class="<?= $active === $key ? 'active' : '' ?>" href="<?= admin_h($item[1]) ?>">
                        <span class="nav-icon"><?= admin_h($item[2]) ?></span><span><?= admin_h($item[0]) ?></span>
                    </a>
                <?php endforeach; ?>
                <a href="logout.php"><span class="nav-icon">↪</span><span>Logout</span></a>
            </nav>
            <div class="sidebar-bottom">MobileCare Admin Panel<br>Manage your shop in one place.</div>
        </aside>
        <main class="admin-main">
            <header class="topbar">
                <div><h1><?= admin_h($pageTitle) ?></h1><p>Manage your shop and customer requests</p></div>
                <div class="admin-user"><span class="avatar"><?= admin_h(strtoupper(substr((string)$adminName, 0, 1))) ?></span><span class="user-name"><?= admin_h($adminName) ?></span></div>
            </header>
            <div class="admin-content">
    <?php
}

function admin_footer(): void {
    ?>
            </div>
        </main>
    </div>
    </body>
    </html>
    <?php
}
