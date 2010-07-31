create table locations (id integer unique not null auto_increment,
						user_id integer not null,
						lat double,
						long double,
						street_address varchar(255),
						city varchar(64),
						post_code varchar(32),
						country varchar(64),
						update_method varchar(32),
						last_seen datetime);

create table users 	   (id integer unique not null auto_increment,
						username varchar(64) not null,
						passwd varchar(128) not null,
						email varchar(255),
						display_name varchar(100),
						created datetime,
						last_seen datetime);

