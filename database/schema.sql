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