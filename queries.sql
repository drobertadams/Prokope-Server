CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(45) NOT NULL DEFAULT '',
  `firstname` varchar(60) NOT NULL DEFAULT '',
  `lastname` varchar(60) NOT NULL DEFAULT '',
  `professorid` int(11) DEFAULT NULL,
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

create table if not exists `authors` (
	`id` int(11) not null auto_increment,
	`name` varchar(100) not null default '',
	`icon` varchar(1024) default '',
	`created` datetime not null,
	`userid` int(11) not null,
	`bio` mediumtext default null,
	primary key(`id`)
) engine=myisam default charset=utf8;

create table if not exists `documents` (
	`id` int(11) not null auto_increment,
	`title` varchar(100) not null default '',
	`content` mediumtext default '',
	`created` datetime not null,
	`userid` int(11) not null,
	`authorid` int(11) not null,
	`parentid` int(11) default null,
	primary key(`id`)
) engine=myisam default charset=utf8;

create table if not exists `comments` (
	`id` int(11) not null auto_increment,
	`document_id` int(11) not null,
	`content` mediumtext default '',
	`created` datetime not null,
	`userid` int(11) not null,
	primary key(`id`)
) engine=myisam default charset=utf8;

create table if not exists `vocabularies` (
	`id` int(11) not null auto_increment,
	`document_id` int(11) not null,
	`content` mediumtext not null default '',
	`created` datetime not null,
	`userid` int(11) not null,
	primary key(`id`)
) engine=myisam default charset=utf8;

create table if not exists `sidebars` (
	`id` int(11) not null auto_increment,
	`document_id` int(11) not null,
	`content` mediumtext not null default '',
	`created` datetime not null,
	`userid` int(11) not null,
	primary key(`id`)
) engine=myisam default charset=utf8; 

# Add support for hierarchical documents.
#alter table documents add column (`parentid` int(11) default null);
#alter table documents change column `content` `content` mediumtext default '';

# Add the root "Document" with id=1.
insert into documents (id, title, created, userid, authorid, parentid) values (1, "Document", now(), -1, -1, NULL); 

# Add a user group for professors and normal users.
insert into groups (title) values ('professor');
insert into groups (title) values ('user');

# Add support for author biographical information.
#alter table authors add column (`bio` mediumtext default null);

# Add support for professors.
#alter table users add column (`professorid` int(11) default null);

# Add support for each comment to occupy a row in the the comments table.
#alter table comments add column (`ref` mediumtext not null);
#alter table comments add column (`type` mediumtext default null);
#alter table comments add column (`title` mediumtext default null);
#alter table comments change column `content` `content` mediumtext default '';
#alter table comments add column (`src` mediumtext default null);
