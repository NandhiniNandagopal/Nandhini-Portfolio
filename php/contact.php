<?php
/**
 * Handles the Contact page form: validates input server-side,
 * then stores the message in the `messages` table via PDO.
 * Responds with JSON so assets/js/script.js can show inline feedback
 * without a page reload.
 */

header('Content-Type: application/json');
require_once __DIR__ . '/config.php';

function respond($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Invalid request method.');
}

$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Server-side validation (mirrors the client-side checks in script.js,
// but this is the copy that actually protects the database).
$errors = [];
if (mb_strlen($name) < 2)               $errors[] = 'name';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'email';
if (mb_strlen($subject) < 2)            $errors[] = 'subject';
if (mb_strlen($message) < 10)           $errors[] = 'message';

if (!empty($errors)) {
    respond(false, 'Please check the following field(s): ' . implode(', ', $errors));
}

$pdo = get_db_connection();
if ($pdo === null) {
    respond(false, 'Could not connect to the database. Please try again later.');
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO messages (name, email, subject, message) VALUES (:name, :email, :subject, :message)'
    );
    $stmt->execute([
        ':name'    => $name,
        ':email'   => $email,
        ':subject' => $subject,
        ':message' => $message,
    ]);
    respond(true, 'Thanks, ' . htmlspecialchars($name) . '! Your message has been received.');
} catch (PDOException $e) {
    error_log('Insert failed: ' . $e->getMessage());
    respond(false, 'Something went wrong while saving your message. Please try again.');
}
