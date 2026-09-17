<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Login';
$pdo = getDbConnection();
$errors = [];

if (isLoggedIn()) {
    redirectTo('account.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session expired — please try again.';
    }

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $errors[] = 'Incorrect email or password.';
        }
    }

    if (!$errors) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];

        $redirect = $_SESSION['redirect_after_login'] ?? 'account.php';
        unset($_SESSION['redirect_after_login']);
        redirectTo($redirect);
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="auth-page container">
    <div class="auth-card fade-in">
        <h1>Welcome Back</h1>
        <p>Log in to view your reservations and account details.</p>

        <?php if ($errors): ?>
            <div class="form-errors">
                <ul><?php foreach ($errors as $error): ?><li><?php echo htmlspecialchars($error); ?></li><?php endforeach; ?></ul>
            </div>
        <?php endif; ?>

        <form method="POST" class="auth-form" novalidate>
            <?php echo csrfField(); ?>
            <label>Email
                <input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </label>
            <label>Password
                <input type="password" name="password" required>
            </label>
            <button type="submit" class="btn btn-book">Log In</button>
        </form>

        <p class="auth-switch">Don't have an account? <a href="register.php">Create one</a></p>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>