CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(45) NOT NULL DEFAULT '',
  `firstname` varchar(60) NOT NULL DEFAULT '',
  `lastname` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `group_memberships` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `userid` int(11) NOT NULL,
    `groupid` int(11) NOT NULL,
    PRIMARY KEY(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

create table if not exists `documents` (
	`id` int(11) not null auto_increment,
	`title` varchar(100) not null default '',
	`content` mediumtext not null default '',
	`created` datetime not null,
	`userid` int(11) not null,
	primary key(`id`)
) engine=myisam default charset=utf8;
