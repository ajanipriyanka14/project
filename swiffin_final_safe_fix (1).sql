-- SWIFFIN CAKE SHOP - SAFE DATABASE FIX
-- This script is for the EXISTING `cakeshop` database.
-- It does NOT DROP/TRUNCATE/DELETE any table or row.
-- It only repairs keys/relations and schema needed for a stable website.

USE `cakeshop`;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET FOREIGN_KEY_CHECKS = 0;

-- =========================================================
-- 1. CATEGORY: repair the duplicate id=0 rows already present
-- =========================================================
-- These are the six category rows shown in the current database.
UPDATE category SET id = 3 WHERE id = 0 AND category_name = 'birthday';
UPDATE category SET id = 5 WHERE id = 0 AND category_name = 'anniversary cake';
UPDATE category SET id = 6 WHERE id = 0 AND category_name = 'disigner cake';
UPDATE category SET id = 7 WHERE id = 0 AND category_name = 'regular cake';
UPDATE category SET id = 8 WHERE id = 0 AND category_name = 'eggless cake';
UPDATE category SET id = 9 WHERE id = 0 AND category_name = 'premium cake';

-- Category is now a proper parent table.
ALTER TABLE category
  MODIFY id INT(100) NOT NULL AUTO_INCREMENT;
ALTER TABLE category AUTO_INCREMENT = 10;

-- =========================================================
-- 2. CAKE: keep all rows; make category_id match category names
-- =========================================================
UPDATE cake c
JOIN category cat
  ON LOWER(TRIM(c.category)) = LOWER(TRIM(cat.category_name))
SET c.category_id = cat.id;

-- Existing cake structure already has category_id and flavor_id.
-- Make sure the indexes exist (ignore if already present).
SET @x = (SELECT COUNT(*) FROM information_schema.STATISTICS
          WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='cake'
          AND INDEX_NAME='idx_cake_category_id');
SET @sql = IF(@x=0,
  'ALTER TABLE cake ADD KEY idx_cake_category_id (category_id)',
  'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @x = (SELECT COUNT(*) FROM information_schema.STATISTICS
          WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='cake'
          AND INDEX_NAME='idx_cake_flavor_id');
SET @sql = IF(@x=0,
  'ALTER TABLE cake ADD KEY idx_cake_flavor_id (flavor_id)',
  'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- =========================================================
-- 3. PRIMARY KEYS: add only if missing
-- =========================================================
-- category and cake are already PKs in the current database.
-- The following dynamic statements are safe if another PK is already there.
SET @sql = IF((SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
               WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='admin'
               AND CONSTRAINT_TYPE='PRIMARY KEY')=0,
              'ALTER TABLE admin ADD PRIMARY KEY (id)','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql = IF((SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
               WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='carts'
               AND CONSTRAINT_TYPE='PRIMARY KEY')=0,
              'ALTER TABLE carts ADD PRIMARY KEY (id)','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql = IF((SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
               WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='customer'
               AND CONSTRAINT_TYPE='PRIMARY KEY')=0,
              'ALTER TABLE customer ADD PRIMARY KEY (id)','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql = IF((SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
               WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='delivery'
               AND CONSTRAINT_TYPE='PRIMARY KEY')=0,
              'ALTER TABLE delivery ADD PRIMARY KEY (id)','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql = IF((SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
               WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='enquiry'
               AND CONSTRAINT_TYPE='PRIMARY KEY')=0,
              'ALTER TABLE enquiry ADD PRIMARY KEY (id)','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql = IF((SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
               WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='feedback'
               AND CONSTRAINT_TYPE='PRIMARY KEY')=0,
              'ALTER TABLE feedback ADD PRIMARY KEY (id)','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql = IF((SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
               WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='login'
               AND CONSTRAINT_TYPE='PRIMARY KEY')=0,
              'ALTER TABLE login ADD PRIMARY KEY (login_id)','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql = IF((SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
               WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='offers'
               AND CONSTRAINT_TYPE='PRIMARY KEY')=0,
              'ALTER TABLE offers ADD PRIMARY KEY (id)','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql = IF((SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
               WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='orders'
               AND CONSTRAINT_TYPE='PRIMARY KEY')=0,
              'ALTER TABLE orders ADD PRIMARY KEY (id)','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql = IF((SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
               WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='payment'
               AND CONSTRAINT_TYPE='PRIMARY KEY')=0,
              'ALTER TABLE payment ADD PRIMARY KEY (id)','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql = IF((SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
               WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='reg'
               AND CONSTRAINT_TYPE='PRIMARY KEY')=0,
              'ALTER TABLE reg ADD PRIMARY KEY (reg_id)','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql = IF((SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
               WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='settings'
               AND CONSTRAINT_TYPE='PRIMARY KEY')=0,
              'ALTER TABLE settings ADD PRIMARY KEY (id)','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql = IF((SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
               WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='staff'
               AND CONSTRAINT_TYPE='PRIMARY KEY')=0,
              'ALTER TABLE staff ADD PRIMARY KEY (id)','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- =========================================================
-- 4. FOREIGN KEYS: only add when the existing data is valid
-- =========================================================
-- cake -> category
SET @bad = (SELECT COUNT(*) FROM cake c LEFT JOIN category k ON k.id=c.category_id
            WHERE c.category_id IS NOT NULL AND k.id IS NULL);
SET @exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
               WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME='cake'
               AND CONSTRAINT_NAME='fk_cake_category');
SET @sql = IF(@bad=0 AND @exists=0,
  'ALTER TABLE cake ADD CONSTRAINT fk_cake_category FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE SET NULL ON UPDATE CASCADE',
  'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- cake -> flavor (only if a flavor table exists)
SET @flavor_table = (SELECT COUNT(*) FROM information_schema.TABLES
                     WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='flavor');
SET @bad = IF(@flavor_table=1,
  (SELECT COUNT(*) FROM cake c LEFT JOIN flavor f ON f.id=c.flavor_id
   WHERE c.flavor_id IS NOT NULL AND f.id IS NULL),0);
SET @exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
               WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME='cake'
               AND CONSTRAINT_NAME='fk_cake_flavor');
SET @sql = IF(@flavor_table=1 AND @bad=0 AND @exists=0,
  'ALTER TABLE cake ADD CONSTRAINT fk_cake_flavor FOREIGN KEY (flavor_id) REFERENCES flavor(id) ON DELETE SET NULL ON UPDATE CASCADE',
  'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- carts -> customer / cake
SET @bad = (SELECT COUNT(*) FROM carts c LEFT JOIN customer u ON u.id=c.customer_id
            WHERE c.customer_id IS NOT NULL AND u.id IS NULL);
SET @exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME='carts' AND CONSTRAINT_NAME='fk_carts_customer');
SET @sql = IF(@bad=0 AND @exists=0,'ALTER TABLE carts ADD CONSTRAINT fk_carts_customer FOREIGN KEY (customer_id) REFERENCES customer(id) ON DELETE SET NULL ON UPDATE CASCADE','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
SET @bad = (SELECT COUNT(*) FROM carts c LEFT JOIN cake k ON k.id=c.cake_id WHERE c.cake_id IS NOT NULL AND k.id IS NULL);
SET @exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME='carts' AND CONSTRAINT_NAME='fk_carts_cake');
SET @sql = IF(@bad=0 AND @exists=0,'ALTER TABLE carts ADD CONSTRAINT fk_carts_cake FOREIGN KEY (cake_id) REFERENCES cake(id) ON DELETE SET NULL ON UPDATE CASCADE','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- orders -> customer / cake (only valid references)
SET @bad = (SELECT COUNT(*) FROM orders o LEFT JOIN customer u ON u.id=o.customer_id WHERE o.customer_id IS NOT NULL AND u.id IS NULL);
SET @exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME='orders' AND CONSTRAINT_NAME='fk_orders_customer');
SET @sql = IF(@bad=0 AND @exists=0,'ALTER TABLE orders ADD CONSTRAINT fk_orders_customer FOREIGN KEY (customer_id) REFERENCES customer(id) ON DELETE SET NULL ON UPDATE CASCADE','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
SET @bad = (SELECT COUNT(*) FROM orders o LEFT JOIN cake k ON k.id=o.cake_id WHERE o.cake_id IS NOT NULL AND k.id IS NULL);
SET @exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME='orders' AND CONSTRAINT_NAME='fk_orders_cake');
SET @sql = IF(@bad=0 AND @exists=0,'ALTER TABLE orders ADD CONSTRAINT fk_orders_cake FOREIGN KEY (cake_id) REFERENCES cake(id) ON DELETE SET NULL ON UPDATE CASCADE','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- delivery -> orders / staff
SET @bad = (SELECT COUNT(*) FROM delivery d LEFT JOIN orders o ON o.id=d.order_id WHERE o.id IS NULL);
SET @exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME='delivery' AND CONSTRAINT_NAME='fk_delivery_order');
SET @sql = IF(@bad=0 AND @exists=0,'ALTER TABLE delivery ADD CONSTRAINT fk_delivery_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE NO ACTION ON UPDATE CASCADE','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
SET @bad = (SELECT COUNT(*) FROM delivery d LEFT JOIN staff st ON st.id=d.staff_id WHERE d.staff_id IS NOT NULL AND st.id IS NULL);
SET @exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME='delivery' AND CONSTRAINT_NAME='fk_delivery_staff');
SET @sql = IF(@bad=0 AND @exists=0,'ALTER TABLE delivery ADD CONSTRAINT fk_delivery_staff FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE SET NULL ON UPDATE CASCADE','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- feedback -> orders / customer / cake
SET @bad = (SELECT COUNT(*) FROM feedback f LEFT JOIN orders o ON o.id=f.order_id WHERE f.order_id IS NOT NULL AND o.id IS NULL);
SET @exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME='feedback' AND CONSTRAINT_NAME='fk_feedback_order');
SET @sql = IF(@bad=0 AND @exists=0,'ALTER TABLE feedback ADD CONSTRAINT fk_feedback_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL ON UPDATE CASCADE','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
SET @bad = (SELECT COUNT(*) FROM feedback f LEFT JOIN customer u ON u.id=f.customer_id WHERE f.customer_id IS NOT NULL AND u.id IS NULL);
SET @exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME='feedback' AND CONSTRAINT_NAME='fk_feedback_customer');
SET @sql = IF(@bad=0 AND @exists=0,'ALTER TABLE feedback ADD CONSTRAINT fk_feedback_customer FOREIGN KEY (customer_id) REFERENCES customer(id) ON DELETE SET NULL ON UPDATE CASCADE','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
SET @bad = (SELECT COUNT(*) FROM feedback f LEFT JOIN cake k ON k.id=f.cake_id WHERE f.cake_id IS NOT NULL AND k.id IS NULL);
SET @exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME='feedback' AND CONSTRAINT_NAME='fk_feedback_cake');
SET @sql = IF(@bad=0 AND @exists=0,'ALTER TABLE feedback ADD CONSTRAINT fk_feedback_cake FOREIGN KEY (cake_id) REFERENCES cake(id) ON DELETE SET NULL ON UPDATE CASCADE','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- offers -> cake
SET @bad = (SELECT COUNT(*) FROM offers o LEFT JOIN cake k ON k.id=o.cake_id WHERE k.id IS NULL);
SET @exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME='offers' AND CONSTRAINT_NAME='fk_offers_cake');
SET @sql = IF(@bad=0 AND @exists=0,'ALTER TABLE offers ADD CONSTRAINT fk_offers_cake FOREIGN KEY (cake_id) REFERENCES cake(id) ON DELETE NO ACTION ON UPDATE CASCADE','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- payment: deliberately use order_ref_id for FK because historical order_id
-- values include orders that are no longer present. Existing payment rows remain untouched.
SET @bad = (SELECT COUNT(*) FROM payment p LEFT JOIN orders o ON o.id=p.order_ref_id WHERE p.order_ref_id IS NOT NULL AND o.id IS NULL);
SET @exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME='payment' AND CONSTRAINT_NAME='fk_payment_order_ref');
SET @sql = IF(@bad=0 AND @exists=0,'ALTER TABLE payment ADD CONSTRAINT fk_payment_order_ref FOREIGN KEY (order_ref_id) REFERENCES orders(id) ON DELETE SET NULL ON UPDATE CASCADE','SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET FOREIGN_KEY_CHECKS = 1;

-- =========================================================
-- FINAL CHECKS
-- =========================================================
SELECT 'category' AS table_name, COUNT(*) AS records FROM category
UNION ALL SELECT 'cake', COUNT(*) FROM cake
UNION ALL SELECT 'carts', COUNT(*) FROM carts
UNION ALL SELECT 'customer', COUNT(*) FROM customer
UNION ALL SELECT 'delivery', COUNT(*) FROM delivery
UNION ALL SELECT 'feedback', COUNT(*) FROM feedback
UNION ALL SELECT 'orders', COUNT(*) FROM orders
UNION ALL SELECT 'offers', COUNT(*) FROM offers
UNION ALL SELECT 'payment', COUNT(*) FROM payment
UNION ALL SELECT 'staff', COUNT(*) FROM staff;
