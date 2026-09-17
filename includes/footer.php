    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-brand">
                <span class="logo-mark">ES</span>
                <p>Wood fire, seasonal ingredients, and a room built for slow evenings.</p>
            </div>

            <div class="footer-links">
                <h4>Explore</h4>
                <a href="menu.php">Menu</a>
                <a href="experiences.php">Experiences</a>
                <a href="gallery.php">Gallery</a>
                <a href="story.php">Our Story</a>
            </div>

            <div class="footer-hours">
                <h4>Hours</h4>
                <p>Tue &ndash; Sun, 5:00 PM &ndash; 11:00 PM</p>
                <p>Closed Mondays</p>
            </div>

            <div class="footer-contact">
                <h4>Visit</h4>
                <p>Contact details go here.</p>
                <div class="social-links">
                    <a href="#" aria-label="Instagram">Instagram</a>
                    <a href="#" aria-label="Facebook">Facebook</a>
                </div>
            </div>

            <div class="footer-newsletter">
                <h4>Stay in the loop</h4>
                <form class="newsletter-form" id="newsletterForm">
                    <?php echo csrfField(); ?>
                    <input type="email" name="email" placeholder="Your email" required>
                    <button type="submit">Subscribe</button>
                </form>
                <!-- submit handler wired up in a later phase -->
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Ember &amp; Sage. All rights reserved.</p>
        </div>
    </footer>

    <script src="<?php echo BASE_URL; ?>/assets/js/script.js"></script>
</body>
</html>