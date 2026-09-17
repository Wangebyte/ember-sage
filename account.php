<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

requireLogin();

$pageTitle = 'My Account';
$pdo = getDbConnection();
$errors = [];
$success = null;
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session expired — please try again.';
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile' && !$errors) {
        $fullName = sanitizeInput($_POST['full_name'] ?? '');
        $phone    = sanitizeInput($_POST['phone'] ?? '');

        if (!$fullName) $errors[] = 'Full name is required.';

        if (!$errors) {
            $stmt = $pdo->prepare('UPDATE users SET full_name = ?, phone = ? WHERE id = ?');
            $stmt->execute([$fullName, $phone, $userId]);
            $_SESSION['user_name'] = $fullName;
            $success = 'Your details have been updated.';
        }
    }

    if ($action === 'cancel_reservation' && !$errors) {
        $reservationId = (int)($_POST['reservation_id'] ?? 0);
        $stmt = $pdo->prepare(
            "UPDATE reservations SET status = 'cancelled'
             WHERE id = ? AND user_id = ? AND status IN ('pending', 'confirmed') AND reservation_date >= CURDATE()"
        );
        $stmt->execute([$reservationId, $userId]);
        $success = $stmt->rowCount() ? 'Reservation cancelled.' : 'That reservation could not be cancelled.';
    }
}

$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();

$stmt = $pdo->prepare(
    "SELECT * FROM reservations WHERE user_id = ? AND reservation_date >= CURDATE() AND status != 'cancelled'
     ORDER BY reservation_date, reservation_time"
);
$stmt->execute([$userId]);
$upcoming = $stmt->fetchAll();

$stmt = $pdo->prepare(
    "SELECT * FROM reservations WHERE user_id = ? AND (reservation_date < CURDATE() OR status = 'cancelled')
     ORDER BY reservation_date DESC, reservation_time DESC"
);
$stmt->execute([$userId]);
$history = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="account-page container">
    <h1 class="fade-in">My Account</h1>

    <?php if ($success): ?><div class="form-success fade-in"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
    <?php if ($errors): ?>
        <div class="form-errors fade-in">
            <ul><?php foreach ($errors as $error): ?><li><?php echo htmlspecialchars($error); ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <div class="account-grid">
        <div class="account-profile fade-in">
            <h2>Profile</h2>
            <form method="POST" class="auth-form">
                <?php echo csrfField(); ?>
                <input type="hidden" name="action" value="update_profile">
                <label>Full Name
                    <input type="text" name="full_name" required value="<?php echo htmlspecialchars($user['full_name']); ?>">
                </label>
                <label>Email
                    <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                </label>
                <label>Phone
                    <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                </label>
                <button type="submit" class="btn btn-outline">Save Changes</button>
            </form>
        </div>

        <div class="account-reservations fade-in">
            <h2>Upcoming Reservations</h2>
            <?php if (!$upcoming): ?>
                <p class="empty-note">No upcoming reservations. <a href="reservation.php">Book a table</a>.</p>
            <?php else: ?>
                <?php foreach ($upcoming as $res): ?>
                <div class="reservation-row">
                    <div>
                        <strong><?php echo date('D, M j', strtotime($res['reservation_date'])); ?> &middot; <?php echo date('g:i A', strtotime($res['reservation_time'])); ?></strong>
                        <span><?php echo (int)$res['guests']; ?> guests &middot; <?php echo htmlspecialchars(seatingPreferenceLabel($res['seating_preference'])); ?></span>
                        <span class="status-<?php echo $res['status']; ?>"><?php echo ucfirst($res['status']); ?></span>
                    </div>
                    <form method="POST" onsubmit="return confirm('Cancel this reservation?');">
                        <?php echo csrfField(); ?>
                        <input type="hidden" name="action" value="cancel_reservation">
                        <input type="hidden" name="reservation_id" value="<?php echo $res['id']; ?>">
                        <button type="submit" class="btn-link cancel-btn">Cancel</button>
                    </form>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <h2>Reservation History</h2>
            <?php if (!$history): ?>
                <p class="empty-note">No past reservations yet.</p>
            <?php else: ?>
                <?php foreach ($history as $res): ?>
                <div class="reservation-row reservation-row-muted">
                    <div>
                        <strong><?php echo date('D, M j Y', strtotime($res['reservation_date'])); ?> &middot; <?php echo date('g:i A', strtotime($res['reservation_time'])); ?></strong>
                        <span><?php echo (int)$res['guests']; ?> guests</span>
                        <span class="status-<?php echo $res['status']; ?>"><?php echo ucfirst($res['status']); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>