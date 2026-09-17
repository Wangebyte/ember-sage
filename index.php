<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Home';
$pdo = getDbConnection();

$stmt = $pdo->query('SELECT * FROM menu_items WHERE is_available = 1 ORDER BY display_order LIMIT 6');
$signatureDishes = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="hero-content container">
        <h1>Where Fire Meets Flavor</h1>
        <p>Wood-fired cooking, seasonal ingredients, and a dining room built for slow, unhurried evenings.</p>
        <div class="hero-ctas">
            <a href="reservation.php" class="btn btn-book">Reserve a Table</a>
            <a href="menu.php" class="btn btn-outline">Explore Menu</a>
        </div>
    </div>
    <div class="hero-float-card">
        <h4>Open Tonight</h4>
        <p>5:00 PM &ndash; 11:00 PM</p>
        <a href="reservation.php">Book now &rarr;</a>
    </div>
</section>

<section class="section container">
    <h2 class="fade-in">Our Signature</h2>
    <p class="section-lead fade-in">A few dishes that never leave the fire for long.</p>

    <div class="dish-grid">
        <?php foreach ($signatureDishes as $dish): ?>
        <div class="card fade-in">
            <div class="img-placeholder"><?php echo htmlspecialchars($dish['name']); ?></div>
            <div class="card-body">
                <?php if ($dish['is_chefs_special']): ?><span class="badge">Chef's Special</span><?php endif; ?>
                <?php if ($dish['is_vegetarian']): ?><span class="badge badge-veg">Vegetarian</span><?php endif; ?>
                <h3><?php echo htmlspecialchars($dish['name']); ?></h3>
                <p><?php echo htmlspecialchars($dish['description']); ?></p>
                <div class="card-footer">
                    <span class="price"><?php echo formatPrice($dish['price']); ?></span>
                    <a href="menu.php" class="btn-link">View &rarr;</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="chef-feature">
    <div class="img-placeholder chef-image">Chef Portrait</div>
    <div class="chef-content">
        <h2 class="fade-in">Cooked Over Open Flame, Always</h2>
        <p class="fade-in">Every dish that leaves our kitchen has touched the fire — grilled, smoked, or charred over hardwood embers. It's a discipline, not a garnish.</p>
        <a href="story.php" class="btn btn-outline fade-in">Meet the Chef</a>
    </div>
</section>

<section class="section container">
    <h2 class="fade-in">The Ember Experience</h2>
    <div class="experience-grid">
        <div class="card fade-in">
            <div class="img-placeholder">Chef's Table</div>
            <div class="card-body">
                <h3>Chef's Table</h3>
                <p>Six seats at the pass, watching every plate leave the fire.</p>
                <a href="experiences.php" class="btn-link">Learn more &rarr;</a>
            </div>
        </div>
        <div class="card fade-in">
            <div class="img-placeholder">Private Dining</div>
            <div class="card-body">
                <h3>Private Dining Room</h3>
                <p>A closed room for up to twelve, built around a set menu.</p>
                <a href="experiences.php" class="btn-link">Learn more &rarr;</a>
            </div>
        </div>
        <div class="card fade-in">
            <div class="img-placeholder">Tasting Menu</div>
            <div class="card-body">
                <h3>Seasonal Tasting Menu</h3>
                <p>Seven courses built around what came off the grill that week.</p>
                <a href="experiences.php" class="btn-link">Learn more &rarr;</a>
            </div>
        </div>
    </div>
</section>

<section class="story-teaser">
    <div class="story-content">
        <h2 class="fade-in">Our Story</h2>
        <p class="fade-in">Ember & Sage started with one idea: cooking is better close to the flame. What began as a single wood-fired grill has become a full kitchen built around fire, smoke, and patience.</p>
        <a href="story.php" class="btn btn-outline fade-in">Read Our Story</a>
    </div>
    <div class="img-placeholder story-image">Dining Room</div>
</section>

<section class="section container">
    <h2 class="fade-in">What Guests Are Saying</h2>
    <div class="testimonial-grid">
        <blockquote class="card fade-in">
            <p>"Best ribeye I've had in this city. The smoke actually means something here."</p>
            — Wanjiru K.
        </blockquote>
        <blockquote class="card fade-in">
            <p>"The Chef's Table is worth every shilling. Watching the fire work is half the meal."</p>
            — David M.
        </blockquote>
        <blockquote class="card fade-in">
            <p>"Warm, unpretentious, and the cauliflower steak converted me."</p>
            — Amara O.
        </blockquote>
    </div>
</section>

<section class="section container">
    <h2 class="fade-in">A Look Inside</h2>
    <div class="gallery-grid">
        <div class="img-placeholder fade-in">Fire &amp; Grill</div>
        <div class="img-placeholder fade-in">Dining Room</div>
        <div class="img-placeholder fade-in">Plated Dish</div>
        <div class="img-placeholder fade-in">Outdoor Seating</div>
    </div>
    <a href="gallery.php" class="btn btn-outline fade-in">View Full Gallery</a>
</section>

<section class="hours-section container">
    <div class="fade-in">
        <h2>Hours &amp; Location</h2>
        <p>Tue &ndash; Sun, 5:00 PM &ndash; 11:00 PM. Closed Mondays.</p>
        <p>Location details go here.</p>
    </div>
</section>

<section class="reservation-cta fade-in">
    <h2>Ready for the Fire?</h2>
    <p>Tables fill fast on weekends — reserve ahead.</p>
    <a href="reservation.php" class="btn btn-book">Reserve a Table</a>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>