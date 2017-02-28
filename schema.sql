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
    task_name varchar(255) NOT NULL,
    description text,
    post_time timestamp NOT NULL DEFAULT current_timestamp,
    start_time timestamp,
    end_time timestamp,
    is_active boolean NOT NULL DEFAULT true,
    category_id int REFERENCES categories(category_id)
);

CREATE TABLE posts (
    post_id int PRIMARY KEY DEFAULT nextval('post_id_seq'),
    poster_id int REFERENCES users(user_id)
        ON DELETE CASCADE NOT NULL,
    task_id int REFERENCES tasks(task_id)
        ON DELETE CASCADE NOT NULL
);

CREATE TABLE task_picks (
    pick_id int PRIMARY KEY DEFAULT nextval('pick_id_seq'),
    picker_id int REFERENCES users(user_id) NOT NULL,
    taker_name varchar(255),
    task_id int REFERENCES tasks(task_id) 
        ON DELETE CASCADE NOT NULL
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