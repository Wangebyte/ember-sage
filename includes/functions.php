<?php
// Shared helpers used across public pages and admin.

define('CURRENCY_SYMBOL', '$');

function sanitizeInput(string $data): string {
    return htmlspecialchars(trim(strip_tags($data)), ENT_QUOTES, 'UTF-8');
}

function generateSlug(string $text): string {
    $slug = strtolower(trim($text));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    return trim($slug, '-');
}

function formatPrice(float $price): string {
    return CURRENCY_SYMBOL . number_format($price, 2);
}

// Unique, human-readable reservation reference, e.g. ES-260908-A3F1C
function generateReservationRef(PDO $pdo): string {
    do {
        $ref = 'ES-' . date('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
        $stmt = $pdo->prepare('SELECT id FROM reservations WHERE reservation_ref = ?');
        $stmt->execute([$ref]);
    } while ($stmt->fetch());

    return $ref;
}

function redirectTo(string $url): void {
    header('Location: ' . $url);
    exit;
}