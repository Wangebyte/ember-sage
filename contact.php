<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Contact';
$pdo = getDbConnection();
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session expired — please try again.';
    }

    $name    = sanitizeInput($_POST['name'] ?? '');
    $email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $phone   = sanitizeInput($_POST['phone'] ?? '');
    $subject = sanitizeInput($_POST['subject'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');

    if (!$name) $errors[] = 'Name is required.';
    if (!$email) $errors[] = 'A valid email is required.';
    if (!$message) $errors[] = 'Message cannot be empty.';

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$name, $email, $phone, $subject, $message]);
        $success = true;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="menu-hero container">
    <h1>Get in Touch</h1>
    <p>Questions, private events, or just want to say hello.</p>
</section>

<section class="section container">
    <div class="contact-grid">
        <div class="contact-info fade-in">
            <h2>Visit</h2>
            <p>123 Ember Lane<br>Nairobi, Kenya</p>
            <h2>Hours</h2>
            <p>Tue &ndash; Sun, 5:00 PM &ndash; 11:00 PM<br>Closed Mondays</p>
            <h2>Contact</h2>
            <p>+254 700 000 000<br>hello@embersage.com</p>
            <h2>Follow</h2>
            <div class="social-links dark">
                <a href="#">Instagram</a>
                <a href="#">Facebook</a>
            </div>
            <div class="img-placeholder map-placeholder">Map</div>
        </div>

        <div class="contact-form-wrap fade-in">
            <?php if ($success): ?>
                <div class="form-success">Thanks — we've received your message and will get back to you shortly.</div>
            <?php endif; ?>
            <?php if ($errors): ?>
                <div class="form-errors">
                    <ul><?php foreach ($errors as $error): ?><li><?php echo htmlspecialchars($error); ?></li><?php endforeach; ?></ul>
                </div>
            <?php endif; ?>

            <form method="POST" class="reservation-form" novalidate>
                <?php echo csrfField(); ?>
                <div class="form-row">
                    <label>Name
                        <input type="text" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                    </label>
                    <label>Email
                        <input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </label>
                </div>
                <div class="form-row">
                    <label>Phone
                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                    </label>
                    <label>Subject
                        <input type="text" name="subject" value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
                    </label>
                </div>
                <label class="form-full">Message
                    <textarea name="message" rows="5" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                </label>
                <button type="submit" class="btn btn-book">Send Message</button>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>