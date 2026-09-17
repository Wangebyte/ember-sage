<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Reservations';
$pdo = getDbConnection();
$errors = [];
$confirmedReservation = null;
$currentUser = null;

if (isLoggedIn()) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $currentUser = $stmt->fetch();
}

$allowedSeating = ['main_dining', 'window', 'bar', 'outdoor', 'private_room'];

$allowedTimes = [];
for ($t = strtotime('17:00'); $t <= strtotime('21:30'); $t += RESERVATION_SLOT_MINUTES * 60) {
    $allowedTimes[] = date('H:i:s', $t);
}

if (isset($_GET['ref'])) {
    $stmt = $pdo->prepare('SELECT * FROM reservations WHERE reservation_ref = ?');
    $stmt->execute([$_GET['ref']]);
    $confirmedReservation = $stmt->fetch() ?: null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session expired — please try again.';
    }

    $fullName = sanitizeInput($_POST['full_name'] ?? '');
    $email    = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $phone    = sanitizeInput($_POST['phone'] ?? '');
    $date     = $_POST['reservation_date'] ?? '';
    $time     = $_POST['reservation_time'] ?? '';
    $guests   = (int)($_POST['guests'] ?? 0);
    $seating  = $_POST['seating_preference'] ?? 'main_dining';
    $requests = sanitizeInput($_POST['special_requests'] ?? '');

    if (!$fullName) $errors[] = 'Full name is required.';
    if (!$email) $errors[] = 'A valid email is required.';
    if (!$phone) $errors[] = 'Phone number is required.';
    if ($guests < 1 || $guests > 12) $errors[] = 'Guests must be between 1 and 12 (call us for larger parties).';
    if (!in_array($seating, $allowedSeating, true)) $errors[] = 'Invalid seating preference.';
    if (!in_array($time, $allowedTimes, true)) $errors[] = 'Please choose a valid time slot.';

    $dateObj = DateTime::createFromFormat('Y-m-d', $date);
    $today = new DateTime('today');
    if (!$dateObj || $dateObj < $today) {
        $errors[] = 'Please choose a valid, upcoming date.';
    } elseif ((int)$dateObj->format('N') === 1) {
        $errors[] = "We're closed Mondays — please pick another date.";
    } elseif ($dateObj > (clone $today)->modify('+60 days')) {
        $errors[] = 'Reservations open up to 60 days ahead.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare(
            "SELECT COALESCE(SUM(guests), 0) AS booked FROM reservations
             WHERE reservation_date = ? AND reservation_time = ? AND status != 'cancelled'"
        );
        $stmt->execute([$date, $time]);
        $booked = (int)$stmt->fetch()['booked'];

        if ($booked + $guests > RESTAURANT_CAPACITY_PER_SLOT) {
            $errors[] = 'That time is fully booked. Please try a different time or date.';
        }
    }

    if (!$errors) {
        $ref = generateReservationRef($pdo);
        $stmt = $pdo->prepare(
            'INSERT INTO reservations
             (reservation_ref, user_id, full_name, email, phone, reservation_date, reservation_time, guests, seating_preference, special_requests)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$ref, $currentUser['id'] ?? null, $fullName, $email, $phone, $date, $time, $guests, $seating, $requests]);
        redirectTo('reservation.php?ref=' . urlencode($ref));
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="reservation-page container">
    <?php if ($confirmedReservation): ?>
        <div class="confirmation-panel fade-in">
            <span class="badge">Reservation Confirmed</span>
            <h1>See You Soon, <?php echo htmlspecialchars($confirmedReservation['full_name']); ?></h1>
            <p class="ref-code">Reference: <strong><?php echo htmlspecialchars($confirmedReservation['reservation_ref']); ?></strong></p>

            <div class="confirmation-details">
                <div><span>Date</span><strong><?php echo date('l, F j, Y', strtotime($confirmedReservation['reservation_date'])); ?></strong></div>
                <div><span>Time</span><strong><?php echo date('g:i A', strtotime($confirmedReservation['reservation_time'])); ?></strong></div>
                <div><span>Guests</span><strong><?php echo (int)$confirmedReservation['guests']; ?></strong></div>
                <div><span>Seating</span><strong><?php echo htmlspecialchars(seatingPreferenceLabel($confirmedReservation['seating_preference'])); ?></strong></div>
                <div><span>Status</span><strong class="status-<?php echo $confirmedReservation['status']; ?>"><?php echo ucfirst($confirmedReservation['status']); ?></strong></div>
            </div>

            <?php if ($confirmedReservation['special_requests']): ?>
                <p class="special-requests"><strong>Special requests:</strong> <?php echo htmlspecialchars($confirmedReservation['special_requests']); ?></p>
            <?php endif; ?>

            <p class="confirmation-note">Noted under <?php echo htmlspecialchars($confirmedReservation['email']); ?>. Save your reference in case you need to change or cancel.</p>
            <a href="index.php" class="btn btn-book">Back to Home</a>
        </div>

    <?php else: ?>
        <div class="reservation-intro fade-in">
            <h1>Reserve a Table</h1>
            <p>Tue&ndash;Sun, 5:00 PM&ndash;11:00 PM. Last seating 9:30 PM. Closed Mondays.</p>
        </div>

        <?php if ($errors): ?>
            <div class="form-errors fade-in">
                <ul>
                    <?php foreach ($errors as $error): ?><li><?php echo htmlspecialchars($error); ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" class="reservation-form fade-in" novalidate>
            <?php echo csrfField(); ?>

            <div class="form-row">
                <label>Full Name
                    <input type="text" name="full_name" required value="<?php echo htmlspecialchars($_POST['full_name'] ?? ($currentUser['full_name'] ?? '')); ?>">
                </label>
                <label>Email
                    <input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ($currentUser['email'] ?? '')); ?>">
                </label>
            </div>

            <div class="form-row">
                <label>Phone
                    <input type="tel" name="phone" required value="<?php echo htmlspecialchars($_POST['phone'] ?? ($currentUser['phone'] ?? '')); ?>">
                </label>
                <label>Guests
                    <select name="guests" required>
                        <?php for ($g = 1; $g <= 12; $g++): ?>
                            <option value="<?php echo $g; ?>" <?php echo (isset($_POST['guests']) && (int)$_POST['guests'] === $g) ? 'selected' : ''; ?>>
                                <?php echo $g; ?> guest<?php echo $g > 1 ? 's' : ''; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </label>
            </div>

            <div class="form-row">
                <label>Date
                    <input type="date" name="reservation_date" required
                           min="<?php echo date('Y-m-d'); ?>"
                           max="<?php echo date('Y-m-d', strtotime('+60 days')); ?>"
                           value="<?php echo htmlspecialchars($_POST['reservation_date'] ?? ''); ?>">
                </label>
                <label>Time
                    <select name="reservation_time" required>
                        <option value="">Select a time</option>
                        <?php foreach ($allowedTimes as $slot): ?>
                            <option value="<?php echo $slot; ?>" <?php echo (($_POST['reservation_time'] ?? '') === $slot) ? 'selected' : ''; ?>>
                                <?php echo date('g:i A', strtotime($slot)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>

            <label class="form-full">Seating Preference
                <select name="seating_preference">
                    <?php foreach ($allowedSeating as $key): ?>
                        <option value="<?php echo $key; ?>" <?php echo (($_POST['seating_preference'] ?? 'main_dining') === $key) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars(seatingPreferenceLabel($key)); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label class="form-full">Special Requests
                <textarea name="special_requests" rows="3" placeholder="Allergies, celebrations, anything we should know."><?php echo htmlspecialchars($_POST['special_requests'] ?? ''); ?></textarea>
            </label>

            <button type="submit" class="btn btn-book">Confirm Reservation</button>
        </form>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>