<?php
session_start();
require_once __DIR__ . '/../php/config.php';

if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = get_db_connection();
$messages = [];
$dbError = '';

if ($pdo !== null) {
    try {
        $messages = $pdo->query('SELECT * FROM messages ORDER BY created_at DESC')->fetchAll();
    } catch (PDOException $e) {
        $dbError = 'Could not load messages: ' . $e->getMessage();
    }
} else {
    $dbError = 'Could not connect to the database.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard · Nandhini Nandagopal</title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600&family=Manrope:wght@500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <header class="site-header">
    <nav class="nav">
      <a href="../index.html" class="nav-mark">Nandhini<span>.</span></a>
      <div>
        <span style="margin-right:16px;color:var(--ink-soft);">Signed in as <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong></span>
        <a href="logout.php" class="btn btn-ghost" style="padding:8px 18px;">Log out</a>
      </div>
    </nav>
  </header>

  <section class="section">
    <div class="container">
      <div class="divider"></div>
      <h1>Contact messages</h1>
      <p>Every message submitted through the portfolio's contact form, newest first.</p>

      <?php if ($dbError): ?>
        <div class="form-status show error"><?php echo htmlspecialchars($dbError); ?></div>
      <?php elseif (empty($messages)): ?>
        <p>No messages yet.</p>
      <?php else: ?>
        <div class="table-wrap">
          <table class="academic">
            <thead>
              <tr><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Received</th></tr>
            </thead>
            <tbody>
              <?php foreach ($messages as $m): ?>
                <tr>
                  <td><?php echo htmlspecialchars($m['name']); ?></td>
                  <td><a href="mailto:<?php echo htmlspecialchars($m['email']); ?>"><?php echo htmlspecialchars($m['email']); ?></a></td>
                  <td><?php echo htmlspecialchars($m['subject']); ?></td>
                  <td style="max-width:320px;"><?php echo nl2br(htmlspecialchars($m['message'])); ?></td>
                  <td><?php echo htmlspecialchars($m['created_at']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </section>
</body>
</html>
