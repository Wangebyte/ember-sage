<?php
// Session + access-control helpers, shared by every page.

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool {
    return isset($_SESSION['admin_id']);
}

// Use on customer-only pages (account.php, etc). Sends guests to login.php,
// which lives in the same folder as the pages that call this.
function requireLogin(): void {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '';
        header('Location: login.php');
        exit;
    }
}

// Use at the top of every admin/*.php page (admin/login.php excluded).
function requireAdmin(): void {
    if (!isAdmin()) {
        header('Location: login.php');
        exit;
    }
}

function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(?string $token): bool {
    return !empty($token) && !empty($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

// Drop this inside any <form> that changes data.
function csrfField(): string {
    $token = generateCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}