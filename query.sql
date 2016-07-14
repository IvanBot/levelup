create table levelup76;
use levelup76;
create table users (
 id int unsigned AUTO_INCREMENT PRIMARY KEY,
 phone bigint unsigned NOT NULL,
 username TINYTEXT,
 surname TINYTEXT,
 ip TINYTEXT,
 email TINYTEXT,
 userpassword TINYTEXT,
 permission TINYINT,
usercomment TEXT);

create table 
activity (
id int unsigned AUTO_INCREMENT PRIMARY KEY,
trainer_id int unsigned,
activityname TEXT,
activitycomment TEXT,
activityduration int,
maxcount int,
mincount int);
 
create table 
trainers (
id int unsigned AUTO_INCREMENT PRIMARY KEY,
phone bigint unsigned,
trainername TINYTEXT,
trainersurname TINYTEXT,
experience DATE,
email TINYTEXT,
photo TEXT,
userpassword TINYTEXT,
permission TINYINT,
active TINYINT,
trainercomment TEXT);
 
 

create table
record_activity (
id int unsigned AUTO_INCREMENT PRIMARY KEY,
user_id int unsigned,
schedule_id int unsigned,
activity_id int unsigned,
activitydate DATE,
starttime TIME,
recordcomment TEXT);

create table 
schedule_activity (
id int unsigned AUTO_INCREMENT PRIMARY KEY,
activity_id int unsigned,
trainer_id int unsigned,
starttime TIME,
endtime TIME,
activityduration TINYINT,
activitydate DATE,
startdate DATE,
mincount int,
maxcount int,
cycleday TINYINT,
deleted TINYINT
);