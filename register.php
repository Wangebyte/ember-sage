<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Register';
$pdo = getDbConnection();
$errors = [];

if (isLoggedIn()) {
    redirectTo('account.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session expired — please try again.';
    }

    $fullName = sanitizeInput($_POST['full_name'] ?? '');
    $email    = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $phone    = sanitizeInput($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (!$fullName) $errors[] = 'Full name is required.';
    if (!$email) $errors[] = 'A valid email is required.';
    if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
    if ($password !== $confirm) $errors[] = 'Passwords do not match.';

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'An account with that email already exists.';
        }
    }

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (full_name, email, phone, password_hash) VALUES (?, ?, ?, ?)');
        $stmt->execute([$fullName, $email, $phone, $hash]);

        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['user_name'] = $fullName;
        redirectTo('account.php');
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="auth-page container">
    <div class="auth-card fade-in">
        <h1>Create an Account</h1>
        <p>Save your details for faster bookings and track your reservations.</p>

        <?php if ($errors): ?>
            <div class="form-errors">
                <ul><?php foreach ($errors as $error): ?><li><?php echo htmlspecialchars($error); ?></li><?php endforeach; ?></ul>
            </div>
        <?php endif; ?>

        <form method="POST" class="auth-form" novalidate>
            <?php echo csrfField(); ?>
            <label>Full Name
                <input type="text" name="full_name" required value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
            </label>
            <label>Email
                <input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </label>
            <label>Phone
                <input type="tel" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
            </label>
            <label>Password
                <input type="password" name="password" required minlength="8">
            </label>
            <label>Confirm Password
                <input type="password" name="confirm_password" required minlength="8">
            </label>
            <button type="submit" class="btn btn-book">Create Account</button>
        </form>

        <p class="auth-switch">Already have an account? <a href="login.php">Log in</a></p>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>