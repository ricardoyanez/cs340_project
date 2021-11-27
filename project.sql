
DROP TABLE IF EXISTS admin;
DROP TABLE IF EXISTS ip;
DROP TABLE IF EXISTS confirmation_delist;
DROP TABLE IF EXISTS delist;
DROP TABLE IF EXISTS confirmation_report;
DROP TABLE IF EXISTS spam;
DROP TABLE IF EXISTS report;


/* create tables */


CREATE TABLE report (
report_id int(11) NOT NULL AUTO_INCREMENT UNIQUE,
report_email varchar(256) NOT NULL,
report_date datetime NOT NULL,
report_ip varchar(16) NOT NULL,
PRIMARY KEY (report_id)
);

CREATE TABLE spam (
spam_id int(11) NOT NULL,
spam_subject varchar(256) NOT NULL,
spam_header text NOT NULL,
spam_body text NOT NULL,
PRIMARY KEY (spam_id),
FOREIGN KEY (spam_id) REFERENCES report (report_id)
);

CREATE TABLE confirmation_report (
confirmation_id int(11) NOT NULL,
confirmation_hash varchar(32) NOT NULL UNIQUE,
confirmation_flag tinyint(1) DEFAULT '0',
confirmation_date datetime DEFAULT NULL,
PRIMARY KEY (confirmation_id),
FOREIGN KEY (confirmation_id) REFERENCES report (report_id)
);

CREATE TABLE delist (
delist_id int(11) NOT NULL AUTO_INCREMENT UNIQUE,
delist_ip varchar(16) NOT NULL,
delist_email varchar(256) NOT NULL,
delist_date datetime NOT NULL,
PRIMARY KEY (delist_id)
);

CREATE TABLE confirmation_delist (
confirmation_id int(11) NOT NULL,
confirmation_hash varchar(32) NOT NULL UNIQUE,
confirmation_flag tinyint(1) DEFAULT '0',
confirmation_date datetime DEFAULT NULL,
PRIMARY KEY (confirmation_id),
FOREIGN KEY (confirmation_id) REFERENCES delist (delist_id)
);

CREATE TABLE ip (
ip_id int(11) NOT NULL,
ip_address varchar(16) NOT NULL,
ip_inqueue tinyint(1) DEFAULT '0',
ip_listed tinyint(1) DEFAULT '0',
delist_id int(11) DEFAULT NULL,
PRIMARY KEY (ip_id),
FOREIGN KEY (ip_id) REFERENCES report (report_id),
FOREIGN KEY (delist_id) REFERENCES delist (delist_id)
);

CREATE TABLE admin (
admin_id int(11) NOT NULL,
admin_ip varchar(16) NOT NULL,
admin_email varchar(256) NOT NULL,
PRIMARY KEY (admin_id),
FOREIGN KEY (admin_id) REFERENCES delist (delist_id)
);

