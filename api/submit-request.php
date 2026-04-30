<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

if (!user_logged_in()) {
    json_response(false, 'Please login to submit a request.', [], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(false, 'Invalid method.', [], 405);
}

if (!verify_csrf($_POST['csrf_token'] ?? '')) {
    json_response(false, 'Invalid CSRF token.', [], 403);
}

$requestType = sanitize_input($_POST['request_type'] ?? '');
$fullName = sanitize_input($_POST['full_name'] ?? '');
$email = sanitize_input($_POST['email'] ?? '');
$phone = sanitize_input($_POST['phone'] ?? '');
$country = sanitize_input($_POST['country'] ?? '');
$productCategory = sanitize_input($_POST['product_category'] ?? '');
$productModel = sanitize_input($_POST['product_model'] ?? '');
$serialNumber = sanitize_input($_POST['serial_number'] ?? '');
$subject = sanitize_input($_POST['subject'] ?? '');
$description = sanitize_input($_POST['description'] ?? '');
$priority = sanitize_input($_POST['priority'] ?? 'medium');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_response(false, 'Email is invalid.', [], 422);
}

if ($fullName === '' || $subject === '' || $description === '') {
    json_response(false, 'Required fields are missing.', [], 422);
}

$filePath = null;
if (!empty($_FILES['attachment']['name'])) {
    $allowed = ['image/png', 'image/jpeg', 'application/pdf'];
    if ($_FILES['attachment']['size'] > 5 * 1024 * 1024) {
        json_response(false, 'Attachment exceeds 5MB.', [], 422);
    }
    if (!in_array($_FILES['attachment']['type'], $allowed, true)) {
        json_response(false, 'Invalid attachment type.', [], 422);
    }
    $ext = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
    $name = hash('sha256', uniqid('', true)) . '.' . $ext;
    $uploadDir = __DIR__ . '/../uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $target = $uploadDir . '/' . $name;
    if (!move_uploaded_file($_FILES['attachment']['tmp_name'], $target)) {
        json_response(false, 'Upload failed.', [], 500);
    }
    $filePath = 'uploads/' . $name;
}

$ticketId = generate_ticket_id();
$user = current_user();

$stmt = $pdo->prepare('INSERT INTO requests
  (user_id, ticket_id, request_type, full_name, email, phone, country, product_category, product_model, serial_number, subject, description, priority, file_path)
  VALUES
  (:user_id, :ticket_id, :request_type, :full_name, :email, :phone, :country, :product_category, :product_model, :serial_number, :subject, :description, :priority, :file_path)');

$stmt->execute([
    ':user_id' => $user['id'],
    ':ticket_id' => $ticketId,
    ':request_type' => $requestType,
    ':full_name' => $fullName,
    ':email' => $email,
    ':phone' => $phone,
    ':country' => $country,
    ':product_category' => $productCategory,
    ':product_model' => $productModel,
    ':serial_number' => $serialNumber ?: null,
    ':subject' => $subject,
    ':description' => $description,
    ':priority' => $priority,
    ':file_path' => $filePath
]);

json_response(true, 'Request submitted successfully.', ['ticket_id' => $ticketId]);
