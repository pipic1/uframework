CREATE TABLE IF NOT EXISTS STATUS (
	ID           INT(6)          NOT NULL AUTO_INCREMENT,
	DESCRIPTION  VARCHAR(250)	 NOT NULL,
	CREATED_AT   DATETIME		 NOT NULL,
	USER_ID      INT(6),
	PRIMARY KEY (ID)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS USER (
	ID      INT(6)       		NOT NULL AUTO_INCREMENT,
	LOGIN	VARCHAR(250)		NOT NULL,
	HASH	VARCHAR(250)		NOT NULL,
	PRIMARY KEY (ID)
) ENGINE=InnoDB;