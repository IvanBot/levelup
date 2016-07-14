create table levelup76;

use levelup76;
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