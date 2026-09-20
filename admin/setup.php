<?php
/**
 * ONE-TIME SETUP: creates the first admin account.
 * Visit this page once in your browser after importing database.sql,
 * then DELETE this file (or move it out of the web root) —
 * it will also refuse to run again once an admin already exists.
 */
require_once __DIR__ . '/../php/config.php';

$pdo = get_db_connection();
$message = '';
$done = false;

if ($pdo !== null) {
    $count = $pdo->query('SELECT COUNT(*) AS c FROM admins')->fetch()['c'];
    if ($count > 0) {
        $message = 'An admin account already exists. This setup page is now locked. Please delete admin/setup.php.';
        $done = true;
    }
}

if (!$done && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (strlen($username) < 3 || strlen($password) < 6) {
        $message = 'Username must be at least 3 characters and password at least 6 characters.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO admins (username, password_hash) VALUES (:u, :p)');
        $stmt->execute([':u' => $username, ':p' => $hash]);
        $message = 'Admin account created! You can now delete admin/setup.php and log in at admin/login.php.';
        $done = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Setup</title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600&family=Manrope:wght@500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
<style>body{display:flex;align-items:center;justify-content:center;min-height:100vh;} .box{max-width:420px;width:100%;}</style>
</head>
<body>
  <div class="box card">
    <h2>Admin account setup</h2>
    <?php if ($message): ?>
      <p style="color:#6E9A83;font-weight:600;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if (!$done): ?>
    <form method="post">
      <div class="form-field">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-primary">Create admin account</button>
    </form>
    <?php endif; ?>
  </div>
</body>
</html>
