<?php
require_once '../includes/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    // fallback to POST
    $data = $_POST;
}

$name    = sanitize($data['name'] ?? '');
$email   = filter_var(trim($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$subject = sanitize($data['subject'] ?? '');
$message = sanitize($data['message'] ?? '');

if (empty($name) || empty($email) || empty($message)) {
    jsonResponse(['success' => false, 'message' => 'Name, email, and message are required.']);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(['success' => false, 'message' => 'Invalid email address.']);
}
if (strlen($message) < 10) {
    jsonResponse(['success' => false, 'message' => 'Message too short.']);
}
if (strlen($message) > 2000) {
    jsonResponse(['success' => false, 'message' => 'Message too long.']);
}

// Rate limiting (basic IP-based)
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
try {
    $db = getDB();
    $recent = $db->prepare("SELECT COUNT(*) FROM messages WHERE ip_address=? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
    $recent->execute([$ip]);
    if ($recent->fetchColumn() >= 5) {
        jsonResponse(['success' => false, 'message' => 'Too many messages. Please wait before sending again.']);
    }

    $stmt = $db->prepare("INSERT INTO messages (name, email, subject, message, ip_address) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $subject, $message, $ip]);

    jsonResponse(['success' => true, 'message' => 'Message sent successfully!']);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'Failed to save message. Please try again.'], 500);
}
?>
