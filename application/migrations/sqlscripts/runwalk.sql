INSERT INTO `ospos_attribute_definitions` (definition_name, definition_type, definition_flags)
  SELECT DISTINCT ospos_items_sizes_categories.name, 'DROPDOWN', 4
  FROM ospos_items_categories
  JOIN ospos_items_sizes_categories ON ospos_items_categories.item_size_category_id = ospos_items_sizes_categories.id;

INSERT INTO `ospos_attribute_values` (attribute_id, attribute_value) SELECT id, size FROM `ospos_items_sizes`;

ALTER TABLE `ospos_items`
  ADD COLUMN `category` varchar(255) NOT NULL;

UPDATE ospos_items SET category = 'Accessoires'
  WHERE category NOT LIKE '%Schoenen%' AND category NOT LIKE '%Kleding%' AND category NOT LIKE '%kledij%' AND category NOT LIKE '%Broeken%' ;

UPDATE ospos_items JOIN ospos_items_categories ON ospos_items_categories.description = category
  JOIN ospos_items_sizes_categories ON ospos_items_sizes_categories.id = ospos_items_sizes_categories.item_size_category_id
  SET category = sc.name;

DELETE FROM ospos_modules where module_id = 'newsletters';

UPDATE `ospos_customers` JOIN ospos_people ON ospos_people.person_id = ospos_customers.person_id SET consent = in_mailing_list;

INSERT INTO `ospos_attribute_links` (attribute_id, definition_id)
  SELECT attribute_id, definition_id
  FROM ospos_items_sizes
  JOIN ospos_attribute_values ON attribute_id = ospos_items_sizes.id
  JOIN ospos_items_sizes_categories ON ospos_items_sizes_categories.id = ospos_items_sizes.item_size_category_id
  JOIN ospos_attribute_definitions ON definition_name = ospos_items_sizes_categories.name;

INSERT INTO `ospos_attribute_links` (item_id, attribute_id, definition_id)
  SELECT item_id, attribute_id, definition_id
  FROM ospos_items
  JOIN ospos_items_sizes ON ospos_items_sizes.id = ospos_items.item_size_id
  JOIN ospos_attribute_values ON attribute_id = ospos_items_sizes.id
  JOIN ospos_items_categories ON ospos_items.item_category_id = ospos_items_categories.id
  JOIN ospos_items_sizes_categories ON ospos_items_sizes_categories.id = ospos_items_categories.item_size_category_id
  JOIN ospos_attribute_definitions ON ospos_items_sizes_categories.name = definition_name;
