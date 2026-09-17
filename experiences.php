<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Experiences';
$pdo = getDbConnection();
$experiences = $pdo->query('SELECT * FROM experiences ORDER BY display_order')->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="menu-hero container">
    <h1>The Ember Experience</h1>
    <p>A few ways to spend an evening with us, beyond a regular table.</p>
</section>

<section class="section container">
    <div class="experience-detail-grid">
        <?php foreach ($experiences as $exp): ?>
        <div class="card experience-detail-card fade-in">
            <div class="img-placeholder"><?php echo htmlspecialchars($exp['name']); ?></div>
            <div class="card-body">
                <h3><?php echo htmlspecialchars($exp['name']); ?></h3>
                <p><?php echo htmlspecialchars($exp['description']); ?></p>
                <div class="card-footer">
                    <span class="price">From <?php echo formatPrice($exp['price_from']); ?></span>
                    <a href="reservation.php" class="btn btn-book">Book Experience</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>