<?php require_once __DIR__ . '/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — Ember & Sage' : 'Ember & Sage — Fire, Flavor & Fine Moments'; ?></title>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>

<div class="announcement-bar">
    Wood-fired tasting menu now booking for the weekend — <a href="reservation.php">reserve a table</a>
</div>

<header class="site-header">
    <div class="header-inner">
        <a href="index.php" class="logo">
            <span class="logo-mark">ES</span>
            <span class="logo-word">EMBER &amp; SAGE</span>
        </a>

        <nav class="main-nav" id="mainNav">
            <?php
            $navItems = [
                'index.php'       => 'Home',
                'menu.php'        => 'Menu',
                'story.php'       => 'Our Story',
                'experiences.php' => 'Experiences',
                'gallery.php'     => 'Gallery',
                'contact.php'     => 'Contact',
            ];
            $current = basename($_SERVER['PHP_SELF']);
            foreach ($navItems as $file => $label):
                $active = ($current === $file) ? ' class="active"' : '';
                echo "<a href=\"{$file}\"{$active}>{$label}</a>";
            endforeach;
            ?>

            <?php if (isLoggedIn()): ?>
                <a href="account.php">My Account</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>

        <a href="reservation.php" class="btn btn-book">Book a Table</a>

        <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>