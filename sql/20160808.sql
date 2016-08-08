ALTER TABLE record_activity ADD COLUMN last_name varchar(128) after name;
ALTER TABLE record_activity ADD COLUMN cnt int not null default '1' after phone;
ALTER TABLE users CHANGE username name varchar(128);
ALTER TABLE users CHANGE surname last_name varchar(128);