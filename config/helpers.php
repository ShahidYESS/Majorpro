<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function sanitize_input(?string $value): string
{
    return htmlspecialchars(trim((string) $value), ENT_QUOTES, 'UTF-8');
}

function json_response(bool $success, string $message, array $data = [], int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

function generate_ticket_id(): string
{
    return 'RSD-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(?string $token): bool
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string) $token);
}

function admin_guard(): void
{
    if (empty($_SESSION['admin_logged_in'])) {
        header('Location: /admin/login.php');
        exit;
    }
}

function user_logged_in(): bool
{
    return !empty($_SESSION['user_logged_in']) && !empty($_SESSION['user_id']);
}

function current_user(): ?array
{
    if (!user_logged_in()) {
        return null;
    }

    return [
        'id' => (int) ($_SESSION['user_id'] ?? 0),
        'name' => (string) ($_SESSION['user_name'] ?? ''),
        'email' => (string) ($_SESSION['user_email'] ?? '')
    ];
}

function user_guard(): void
{
    if (!user_logged_in()) {
        header('Location: /login.php');
        exit;
    }
}

function status_color(string $status): string
{
    return match ($status) {
        'pending' => '#888888',
        'in-review' => '#C9A84C',
        'in-progress' => '#00A8E8',
        'resolved' => '#34c759',
        'closed' => '#555555',
        default => '#888888'
    };
}
?>
