<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';
admin_guard();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(false, 'Invalid method.', [], 405);
}

if (!verify_csrf($_POST['csrf_token'] ?? '')) {
    json_response(false, 'Invalid CSRF token.', [], 403);
}

$ticketId = sanitize_input($_POST['ticket_id'] ?? '');
$status = sanitize_input($_POST['status'] ?? '');
$notes = sanitize_input($_POST['admin_notes'] ?? '');

$stmt = $pdo->prepare('UPDATE requests SET status = :status, admin_notes = :notes WHERE ticket_id = :ticket_id');
$stmt->execute([
    ':status' => $status,
    ':notes' => $notes ?: null,
    ':ticket_id' => $ticketId
]);

json_response(true, 'Request updated.');
