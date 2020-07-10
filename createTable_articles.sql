DROP TABLE IF EXISTS articles;

CREATE TABLE articles
(
	id					smallint unsigned NOT NULL auto_increment,
	-- "smallint unsigned" : this field can hold integers from 0 to 65,535
    -- "NOT NULL" : this field cannot hold a null value
    -- "auto_increment" : automatically generate ids in sequential order
    publicationDate 	date NOT NULL,
    title				varchar(255),
    -- "(255)" : this field can hold 255 characters
    summary				text NOT NULL,
    -- "text" : this field can hold 65,535 characters
    content				mediumtext NOT NULL,
    -- "mediumtext": this field can hold 16,777,215 characters
    
    PRIMARY KEY (id)
);