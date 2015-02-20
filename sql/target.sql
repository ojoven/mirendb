CREATE TABLE `table_test` (
  `id` int(11) NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
INSERT INTO `table_test` VALUES (1,1),(1,1);
CREATE TABLE `table_to_add` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
CREATE TABLE `table_to_add_plus_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
INSERT INTO `table_to_add_plus_data` VALUES (1,'2015-01-31 00:00:00'),(2,'2015-01-29 00:00:00');
CREATE TABLE `table_to_keep` (
  `id2` int(11) NOT NULL AUTO_INCREMENT,
  `field_to_add_3` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT 'predefined',
  `field_to_add_2` int(11) NOT NULL,
  `field_to_add` varchar(20) COLLATE utf8_bin DEFAULT 'predefined' COMMENT 'Comment',
  `field_to_add_4` int(11) NOT NULL,
  `field_to_add_5` int(11) NOT NULL,
  PRIMARY KEY (`id2`),
  UNIQUE KEY `field_to_add_4` (`field_to_add_4`),
  UNIQUE KEY `field_to_add` (`field_to_add`),
  KEY `field_to_add_5` (`field_to_add_5`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
INSERT INTO `table_to_keep` VALUES (1,'predefined',12,'predefined',12,12);
CREATE TABLE `table_to_keep_2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(32) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
INSERT INTO `table_to_keep_2` VALUES (1,'value1'),(2,'value2'),(3,'value3');
