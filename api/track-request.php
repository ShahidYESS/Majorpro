<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    json_response(false, 'Invalid method.', [], 405);
}

$ticketId = sanitize_input($_GET['ticket_id'] ?? '');
if ($ticketId === '') {
    json_response(false, 'Ticket ID required.', [], 422);
}

$stmt = $pdo->prepare('SELECT ticket_id, request_type, product_category, product_model, status, admin_notes, created_at FROM requests WHERE ticket_id = :ticket_id LIMIT 1');
$stmt->execute([':ticket_id' => $ticketId]);
$row = $stmt->fetch();

if (!$row) {
    json_response(false, 'Ticket not found.', [], 404);
}

$row['status_color'] = status_color($row['status']);
json_response(true, 'Ticket fetched.', $row);
