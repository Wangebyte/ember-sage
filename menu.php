<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Menu';
$pdo = getDbConnection();

$categories = $pdo->query('SELECT * FROM menu_categories ORDER BY display_order')->fetchAll();

$items = $pdo->query(
    'SELECT mi.*, mc.slug AS category_slug, mc.name AS category_name
     FROM menu_items mi
     JOIN menu_categories mc ON mi.category_id = mc.id
     WHERE mi.is_available = 1
     ORDER BY mc.display_order, mi.display_order'
)->fetchAll();

$featured = array_filter($items, fn($item) => $item['is_chefs_special']);

require_once __DIR__ . '/includes/header.php';
?>

<section class="menu-hero container">
    <h1>The Menu</h1>
    <p>Everything here has touched fire, smoke, or embers before it reaches the table.</p>
</section>

<?php if ($featured): ?>
<section class="section container featured-dishes">
    <h2 class="fade-in">Chef's Selects</h2>
    <div class="dish-grid">
        <?php foreach ($featured as $dish): ?>
        <div class="card fade-in">
            <div class="img-placeholder"><?php echo htmlspecialchars($dish['name']); ?></div>
            <div class="card-body">
                <span class="badge">Chef's Special</span>
                <?php if ($dish['is_vegetarian']): ?><span class="badge badge-veg">Vegetarian</span><?php endif; ?>
                <h3><?php echo htmlspecialchars($dish['name']); ?></h3>
                <p><?php echo htmlspecialchars($dish['description']); ?></p>
                <div class="card-footer">
                    <span class="price"><?php echo formatPrice($dish['price']); ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section class="section container">
    <div class="menu-controls">
        <div class="category-filters" id="categoryFilters">
            <button class="filter-btn active" data-category="all">All</button>
            <?php foreach ($categories as $cat): ?>
            <button class="filter-btn" data-category="<?php echo htmlspecialchars($cat['slug']); ?>">
                <?php echo htmlspecialchars($cat['name']); ?>
            </button>
            <?php endforeach; ?>
        </div>
        <input type="text" id="menuSearch" class="menu-search" placeholder="Search the menu...">
    </div>

    <div class="dish-grid" id="menuGrid">
        <?php foreach ($items as $dish): ?>
        <div class="card menu-card"
             data-category="<?php echo htmlspecialchars($dish['category_slug']); ?>"
             data-name="<?php echo htmlspecialchars(strtolower($dish['name'])); ?>">
            <div class="img-placeholder"><?php echo htmlspecialchars($dish['name']); ?></div>
            <div class="card-body">
                <?php if ($dish['is_chefs_special']): ?><span class="badge">Chef's Special</span><?php endif; ?>
                <?php if ($dish['is_vegetarian']): ?><span class="badge badge-veg">Vegetarian</span><?php endif; ?>
                <span class="category-tag"><?php echo htmlspecialchars($dish['category_name']); ?></span>
                <h3><?php echo htmlspecialchars($dish['name']); ?></h3>
                <p><?php echo htmlspecialchars($dish['description']); ?></p>
                <div class="card-footer">
                    <span class="price"><?php echo formatPrice($dish['price']); ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <p id="noResults" class="no-results" style="display:none;">No dishes match that search.</p>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>