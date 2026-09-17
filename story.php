<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Our Story';
require_once __DIR__ . '/includes/header.php';
?>

<section class="story-hero container">
    <h1 class="fade-in">Cooking Close to the Flame</h1>
    <p class="fade-in">Ember & Sage started with one idea: food tastes better the closer it stays to fire.</p>
</section>

<section class="story-split container">
    <div class="img-placeholder story-split-image fade-in">Founding Kitchen</div>
    <div class="story-split-text fade-in">
        <h2>Where It Began</h2>
        <p>What started as a single wood-fired grill in a borrowed kitchen has grown into a full dining room — but the fire never got smaller. Every dish that leaves our kitchen has been grilled, smoked, or charred over hardwood embers. It's not a technique we reach for occasionally; it's the only way we cook.</p>
    </div>
</section>

<section class="section container">
    <h2 class="fade-in">Our Philosophy</h2>
    <div class="philosophy-grid">
        <div class="philosophy-item fade-in">
            <h3>Fire First</h3>
            <p>No shortcuts, no gas burners hiding behind the scenes. If it's on the menu, it's touched real flame.</p>
        </div>
        <div class="philosophy-item fade-in">
            <h3>Seasonal, Always</h3>
            <p>The menu shifts with what's actually good right now — not what's convenient to keep in stock.</p>
        </div>
        <div class="philosophy-item fade-in">
            <h3>Slow Evenings</h3>
            <p>We don't turn tables fast. A meal here is meant to take the whole evening.</p>
        </div>
    </div>
</section>

<section class="story-split container reverse">
    <div class="story-split-text fade-in">
        <h2>Meet the Chef</h2>
        <p>Our head chef trained in kitchens across three continents before settling on one conviction: the best flavor comes from patience and open flame, not from a longer ingredient list. Every dish on this menu passed through their hands before it earned a place on it.</p>
        <a href="experiences.php" class="btn btn-outline">Meet Them at the Chef's Table</a>
    </div>
    <div class="img-placeholder story-split-image fade-in">Chef Portrait</div>
</section>

<section class="section container">
    <h2 class="fade-in">Our Timeline</h2>
    <div class="timeline">
        <div class="timeline-item fade-in">
            <span class="timeline-year">2019</span>
            <p>Ember & Sage opens with a single wood-fired grill and twelve seats.</p>
        </div>
        <div class="timeline-item fade-in">
            <span class="timeline-year">2021</span>
            <p>The Chef's Table is introduced — six seats, front row to the fire.</p>
        </div>
        <div class="timeline-item fade-in">
            <span class="timeline-year">2023</span>
            <p>Full dining room and private room added to meet demand for the tasting menu.</p>
        </div>
        <div class="timeline-item fade-in">
            <span class="timeline-year">Today</span>
            <p>Still cooking everything over hardwood embers, one seasonal menu at a time.</p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>