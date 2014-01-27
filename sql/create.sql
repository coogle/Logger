DROP TABLE IF EXISTS `application_log`;

CREATE TABLE `application_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `priority` varchar(30) DEFAULT 'UNKNOWN',
  `event` text,
  `source` varchar(255) DEFAULT NULL,
  `uri` text,
  `ip` varchar(45) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1522263 DEFAULT CHARSET=latin1;

