<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Gallery';
$pdo = getDbConnection();
$galleryItems = $pdo->query('SELECT * FROM gallery ORDER BY display_order')->fetchAll();

$categoryLabels = [
    'food'     => 'Food',
    'interior' => 'Interior',
    'chef'     => 'Chef',
    'guests'   => 'Guests',
    'events'   => 'Events',
    'outdoor'  => 'Outdoor',
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="menu-hero container">
    <h1>A Look Inside</h1>
    <p>Fire, food, and the room they both live in.</p>
</section>

<section class="section container">
    <div class="category-filters" id="galleryFilters">
        <button class="filter-btn gallery-filter-btn active" data-category="all">All</button>
        <?php foreach ($categoryLabels as $slug => $label): ?>
        <button class="filter-btn gallery-filter-btn" data-category="<?php echo $slug; ?>"><?php echo $label; ?></button>
        <?php endforeach; ?>
    </div>

    <div class="masonry-gallery" id="galleryGrid">
        <?php foreach ($galleryItems as $item): ?>
        <div class="img-placeholder gallery-item" data-category="<?php echo htmlspecialchars($item['category']); ?>">
            <?php echo htmlspecialchars($item['title']); ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>