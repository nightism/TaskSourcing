# Creates database
CREATE DATABASE task_sourcing;

# Creates sequences for id
CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 0 START WITH 0 NO CYCLE;
CREATE SEQUENCE task_id_seq INCREMENT BY 1 MINVALUE 0 START WITH 0 NO CYCLE;
CREATE SEQUENCE biding_id_seq INCREMENT BY 1 MINVALUE 0 START WITH 0 NO CYCLE;
CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 0 START WITH 0 NO CYCLE;
CREATE SEQUENCE post_id_seq INCREMENT BY 1 MINVALUE 0 START WITH 0 NO CYCLE;
CREATE SEQUENCE pick_id_seq INCREMENT BY 1 MINVALUE 0 START WITH 0 NO CYCLE;

# Create tables
CREATE TABLE users (
    user_id int PRIMARY KEY DEFAULT nextval('user_id_seq'),
    user_name varchar(255) UNIQUE NOT NULL,
    email_address varchar(255) UNIQUE NOT NULL,
    password varchar(255) NOT NULL,
    is_admin boolean NOT NULL DEFAULT false,
    is_active boolean NOT NULL DEFAULT true
);

CREATE TABLE categories(
    category_id int PRIMARY KEY DEFAULT nextval('category_id_seq'),
    category_name varchar(255)
);

CREATE TABLE tasks (
    task_id int PRIMARY KEY DEFAULT nextval('task_id_seq'),
    poster_id int REFERENCES users(user_id) 
        ON DELETE CASCADE NOT NULL,
    post_time timestamp NOT NULL DEFAULT current_timestamp,
    picker_id int REFERENCES users(user_id),
    task_name varchar(255) NOT NULL,
    description text,
    start_time timestamp,
    end_time timestamp,
    is_active boolean NOT NULL DEFAULT true,
    category_id int REFERENCES categories(category_id)
);

CREATE TABLE biddings (
    bid_id int PRIMARY KEY DEFAULT nextval('biding_id_seq'),
    bid_amount int NOT NULL,
    bidder_id int REFERENCES users(user_id)
        ON DELETE CASCADE NOT NULL,
    task_id int REFERENCES tasks(task_id) 
        ON DELETE CASCADE NOT NULL
);

# Default data
# Admins
INSERT INTO users(user_name, email_address, password, is_admin) VALUES ('Yang Zhuohan', 'billstark1996@gmail.com', '123456', true);
INSERT INTO users(user_name, email_address, password, is_admin) VALUES ('Sun Mingyang', 'sun.mingyang@u.nus.edu', '123456', true);
INSERT INTO users(user_name, email_address, password, is_admin) VALUES ('Wu Zefeng', 'flamesdesperado@gmail.com', '123456', true);
INSERT INTO users(user_name, email_address, password, is_admin) VALUES ('Li Zihan', 'e0012710@u.nus.edu', '123456', true);

# Users
INSERT INTO users(user_name, email_address, password) VALUES ('Jiang Haotian', 'e0012663@u.nus.edu', '123456');
INSERT INTO users(user_name, email_address, password) VALUES ('Wu Hanqing', 'e0012689@u.nus.edu', '123456');
INSERT INTO users(user_name, email_address, password) VALUES ('Chen Ke', 'e0012717@u.nus.edu', '123456');
INSERT INTO users(user_name, email_address, password) VALUES ('Luo Yuyang', 'e0012652@u.nus.edu', '123456');
INSERT INTO users(user_name, email_address, password) VALUES ('Duan Yichen', 'e0012639@u.nus.edu', '123456');
INSERT INTO users(user_name, email_address, password) VALUES ('Xu Ruolan', 'e0012662@u.nus.edu', '123456');

# Categories
INSERT INTO categories(category_name) VALUES('Web Development');
INSERT INTO categories(category_name) VALUES('iOS Development');
INSERT INTO categories(category_name) VALUES('Antroid Development');
INSERT INTO categories(category_name) VALUES('Full Stack');
INSERT INTO categories(category_name) VALUES('Graphic Design');
INSERT INTO categories(category_name) VALUES('Maya');
INSERT INTO categories(category_name) VALUES('Unreal');
INSERT INTO categories(category_name) VALUES('Unity');
INSERT INTO categories(category_name) VALUES('Poster Design');
INSERT INTO categories(category_name) VALUES('Font Design');
INSERT INTO categories(category_name) VALUES('Adobe Photoshop');
INSERT INTO categories(category_name) VALUES('Adobe Illustrator');
INSERT INTO categories(category_name) VALUES('Adobe AfterEffects');

# Queries examples
# Post a task
INSERT INTO tasks(poster_id, task_name, description, category_id) 
VALUES(0, 'Logo design for cs2102 task sourcing website', 
    'Design a logo that will be used for a task sourcing website. The logo should look nice and fancy.', 4);
INSERT INTO tasks(poster_id, task_name, description, start_time, end_time, category_id)
VALUES(0, 'iOS development for Give For Free',
    'Give For Free is an website that sells pre-loved goods. Currently we have already got 600+ users and need an iOS version.', 
    '2017-5-1', '2017-7-31', 1);

# List all tasks
SELECT u.user_name, task.task_name, c.category_name
    FROM users u
    INNER JOIN tasks task ON u.user_id = task.poster_id
    INNER JOIN categories c ON task.category_id = c.category_id;

# List all tasks except for some people's task
SELECT u.user_name, task.task_name, c.category_name
    FROM users u
    INNER JOIN tasks task ON u.user_id = task.poster_id
    INNER JOIN categories c ON task.category_id = c.category_id
    WHERE u.user_id <> 0;
