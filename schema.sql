CREATE DATABASE task_sourcing;
CREATE TABLE users (
    user_id int PRIMARY KEY,
    user_name varchar(255) UNIQUE NOT NULL,
    email_address varchar(255) UNIQUE NOT NULL,
    password varchar(255) NOT NULL,
    is_admin boolean NOT NULL,
    is_active boolean NOT NULL
);

CREATE TABLE categories(
    category_id int PRIMARY KEY,
    category_name varchar(255)
);

CREATE TABLE tasks (
    task_id int PRIMARY KEY,
    task_name varchar(255) NOT NULL,
    description text,
    post_time date NOT NULL,
    start_time date,
    end_time date,
    is_active boolean NOT NULL,
    category_id int REFERENCES categories(category_id)
);

CREATE TABLE posts (
    post_id int PRIMARY KEY,
    poster_id int REFERENCES users(user_id),
        ON DELETE CASCADE NOT NULL,
    task_id int REFERENCES tasks(task_id)
        ON DELETE CASCADE NOT NULL
);

CREATE TABLE task_picks (
    pick_id int PRIMARY KEY,
    picker_id int REFERENCES users(user_id) NOT NULL,
    taker_name varchar(255),
    task_id int REFERENCES tasks(task_id) 
        ON DELETE CASCADE NOT NULL
);

CREATE TABLE biddings (
    bid_id int PRIMARY KEY,
    bid_amount: int NOT NULL,
    bidder_id int REFERENCES users(user_id)
        ON DELETE CASCADE NOT NULL,
    task_id int REFERENCES tasks(task_id) 
        ON DELETE CASCADE NOT NULL
);