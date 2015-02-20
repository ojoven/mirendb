/* Create new tables */
-- Table `table_test`
CREATE TABLE `table_test` (
  `id` int(11) NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
INSERT INTO `table_test` VALUES (1,1),(1,1);


-- Table `table_to_add`
CREATE TABLE `table_to_add` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


/* Remove old tables */
-- Table `table_to_keep`
DROP TABLE table_to_keep;

-- Table `table_to_keep_2`
DROP TABLE table_to_keep_2;

-- Table `table_to_remove`
DROP TABLE table_to_remove;

