--certificate db
CREATE TABLE certificate (id int(6) NOT NULL auto_increment, certificate_number varchar(255) NOT NULL, building_number varchar(255), vin varchar(255), expire_date datetime not null, symbol varchar(255) not null, PRIMARY KEY (id),UNIQUE id (id));
--user db
CREATE TABLE user_table (id int(6) NOT NULL auto_increment, login varchar(255) NOT NULL, password varchar(255), PRIMARY KEY (id),UNIQUE id (id));
--insert user with credentials:
-- login:    atp_user
-- password: 4btfnC4C
insert into user_table (login, password) values ('atp_user','d4d44849b81fd3afa57402bb24c2a411');

--information abaout updates
CREATE TABLE update_event (id int(6) NOT NULL auto_increment,  date datetime not null, PRIMARY KEY (id),UNIQUE id (id));


