CREATE DATABASE IF NOT EXISTS ember_sage_restaurant
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE ember_sage_restaurant;

CREATE TABLE menu_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(60) NOT NULL,
    slug VARCHAR(60) NOT NULL UNIQUE,
    display_order INT DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    description TEXT,
    price DECIMAL(8,2) NOT NULL,
    image VARCHAR(255),
    is_vegetarian TINYINT(1) DEFAULT 0,
    is_chefs_special TINYINT(1) DEFAULT 0,
    is_available TINYINT(1) DEFAULT 1,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES menu_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_ref VARCHAR(20) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    reservation_date DATE NOT NULL,
    reservation_time TIME NOT NULL,
    guests INT NOT NULL,
    seating_preference ENUM('main_dining', 'window', 'bar', 'outdoor', 'private_room') DEFAULT 'main_dining',
    special_requests TEXT,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_date_time (reservation_date, reservation_time)
) ENGINE=InnoDB;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(20),
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

ALTER TABLE reservations ADD COLUMN user_id INT NULL AFTER reservation_ref;
ALTER TABLE reservations ADD CONSTRAINT fk_reservations_user
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL;

INSERT INTO menu_categories (name, slug, display_order) VALUES
('Starters', 'starters', 1),
('Grill & Fire', 'grill-fire', 2),
('Vegetarian', 'vegetarian', 3);

INSERT INTO menu_items (category_id, name, slug, description, price, is_vegetarian, is_chefs_special, display_order) VALUES
(2, 'Charred Ribeye', 'charred-ribeye', 'Wood-fired ribeye finished over embers with rosemary butter.', 32.00, 0, 1, 1),
(1, 'Smoked Beet Carpaccio', 'smoked-beet-carpaccio', 'Thin-sliced smoked beets, whipped goat cheese, candied walnut.', 14.00, 1, 0, 1),
(3, 'Charred Cauliflower Steak', 'charred-cauliflower-steak', 'Whole roasted cauliflower, tahini, sage brown butter.', 19.00, 1, 1, 1),
(2, 'Ember-Roasted Half Chicken', 'ember-roasted-half-chicken', 'Spatchcocked chicken, smoked paprika, charred lemon.', 24.00, 0, 0, 2),
(1, 'Grilled Octopus', 'grilled-octopus', 'Fire-kissed octopus, white bean puree, chili oil.', 18.00, 0, 1, 2),
(3, 'Smoked Mushroom Tartine', 'smoked-mushroom-tartine', 'Wild mushrooms, whipped ricotta, sourdough.', 16.00, 1, 0, 2);


UPDATE menu_categories SET display_order = 1 WHERE slug = 'starters';
UPDATE menu_categories SET display_order = 4 WHERE slug = 'grill-fire';
UPDATE menu_categories SET display_order = 6 WHERE slug = 'vegetarian';

INSERT INTO menu_categories (name, slug, display_order) VALUES
('Soups & Salads', 'soups-salads', 2),
('Main Courses', 'main-courses', 3),
('Seafood', 'seafood', 5),
('Desserts', 'desserts', 7),
('Drinks', 'drinks', 8);

INSERT INTO menu_items (category_id, name, slug, description, price, is_vegetarian, is_chefs_special, display_order) VALUES
((SELECT id FROM menu_categories WHERE slug='soups-salads'), 'Roasted Tomato Soup', 'roasted-tomato-soup', 'Slow-roasted tomatoes, basil oil, sourdough crouton.', 9.00, 1, 0, 1),
((SELECT id FROM menu_categories WHERE slug='soups-salads'), 'Charred Caesar', 'charred-caesar', 'Grilled romaine, anchovy dressing, parmesan crisp.', 12.00, 0, 0, 2),
((SELECT id FROM menu_categories WHERE slug='main-courses'), 'Slow-Roasted Lamb Shoulder', 'slow-roasted-lamb-shoulder', 'Ember-roasted lamb, red wine jus, root vegetables.', 29.00, 0, 1, 1),
((SELECT id FROM menu_categories WHERE slug='main-courses'), 'Fire-Roasted Duck Breast', 'fire-roasted-duck-breast', 'Seared duck, cherry gastrique, charred greens.', 27.00, 0, 0, 2),
((SELECT id FROM menu_categories WHERE slug='seafood'), 'Grilled Sea Bass', 'grilled-sea-bass', 'Whole sea bass over coals, citrus butter.', 26.00, 0, 1, 1),
((SELECT id FROM menu_categories WHERE slug='seafood'), 'Smoked Prawns', 'smoked-prawns', 'Hardwood-smoked prawns, garlic chili oil.', 21.00, 0, 0, 2),
((SELECT id FROM menu_categories WHERE slug='desserts'), 'Charred Pineapple', 'charred-pineapple', 'Fire-caramelized pineapple, coconut sorbet.', 10.00, 1, 0, 1),
((SELECT id FROM menu_categories WHERE slug='desserts'), 'Smoked Chocolate Tart', 'smoked-chocolate-tart', 'Dark chocolate, smoked sea salt, hazelnut crumb.', 11.00, 1, 1, 2),
((SELECT id FROM menu_categories WHERE slug='drinks'), 'Smoked Old Fashioned', 'smoked-old-fashioned', 'Bourbon, applewood smoke, orange bitters.', 14.00, 1, 0, 1),
((SELECT id FROM menu_categories WHERE slug='drinks'), 'Sage & Citrus Spritz', 'sage-citrus-spritz', 'Gin, fresh sage, grapefruit, soda.', 12.00, 1, 0, 2);

CREATE TABLE experiences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    price_from DECIMAL(8,2),
    image VARCHAR(255),
    display_order INT DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO experiences (name, slug, description, price_from, display_order) VALUES
('Chef''s Table', 'chefs-table', 'Six seats at the pass, watching every plate leave the fire. A five-course tasting menu narrated by the chef.', 95.00, 1),
('Private Dining Room', 'private-dining-room', 'A closed room for up to twelve, built around a set menu tailored to your event.', 75.00, 2),
('Seasonal Tasting Menu', 'seasonal-tasting-menu', 'Seven courses built around what came off the grill that week. Wine pairing optional.', 65.00, 3);

CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(120),
    category ENUM('food', 'interior', 'chef', 'guests', 'events', 'outdoor') NOT NULL,
    image VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO gallery (title, category, image, display_order) VALUES
('Charred Ribeye off the Grill', 'food', 'placeholder', 1),
('Smoked Beet Carpaccio', 'food', 'placeholder', 2),
('The Dining Room at Dusk', 'interior', 'placeholder', 3),
('The Open Kitchen', 'interior', 'placeholder', 4),
('Chef at the Fire', 'chef', 'placeholder', 5),
('Plating the Tasting Menu', 'chef', 'placeholder', 6),
('A Full Table', 'guests', 'placeholder', 7),
('Raising a Toast', 'guests', 'placeholder', 8),
('Private Dinner Setup', 'events', 'placeholder', 9),
('Chef''s Table in Session', 'events', 'placeholder', 10),
('Terrace Seating', 'outdoor', 'placeholder', 11),
('Evening on the Patio', 'outdoor', 'placeholder', 12);

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(150),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


