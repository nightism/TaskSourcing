CREATE DATABASE IF NOT EXISTS task_sourcing;

CREATE SEQUENCE region_id_seq INCREMENT BY 1 MINVALUE 0 START WITH 0 NO CYCLE;
CREATE SEQUENCE id_seq INCREMENT BY 1 MINVALUE 0 START WITH 0 NO CYCLE;
CREATE SEQUENCE task_id_seq INCREMENT BY 1 MINVALUE 0 START WITH 0 NO CYCLE;
CREATE SEQUENCE biding_id_seq INCREMENT BY 1 MINVALUE 0 START WITH 0 NO CYCLE;
CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 0 START WITH 0 NO CYCLE;
CREATE SEQUENCE assignment_id_seq INCREMENT BY 1 MINVALUE 0 START WITH 0 NO CYCLE;
CREATE SEQUENCE payment_id_seq INCREMENT 1 MINVALUE 0 START WITH 0 NO CYCLE;


CREATE TABLE regions (
    id int PRIMARY KEY DEFAULT nextval('region_id_seq'),
    name varchar(255) UNIQUE NOT NULL
);

CREATE TABLE users (
    id int PRIMARY KEY DEFAULT nextval('id_seq'),
    name varchar(255) UNIQUE NOT NULL,
    email varchar(255) UNIQUE NOT NULL,
    password varchar(255) NOT NULL,
    is_admin boolean NOT NULL DEFAULT false,
    is_active boolean NOT NULL DEFAULT true,
    region int REFERENCES regions(id)
);

CREATE TABLE credit_cards (
    card_number varchar(16),
    owner int REFERENCES users(id),
    PRIMARY KEY (card_number, owner)
);

CREATE TABLE categories (
    id int PRIMARY KEY DEFAULT nextval('category_id_seq'),
    name varchar(255)
);

CREATE TABLE tasks (
    id int PRIMARY KEY DEFAULT nextval('task_id_seq'),
    owner int REFERENCES users(id) 
        ON DELETE CASCADE NOT NULL,
    title varchar(255) NOT NULL,
    description text,
    post_time timestamp NOT NULL DEFAULT current_timestamp,
    start_time timestamp,
    end_time timestamp,
    is_active boolean NOT NULL DEFAULT true,
    category int REFERENCES categories(id),
    region int REFERENCES regions(id),
    salary int NOT NULL,
    CONSTRAINT start_time CHECK (start_time > post_time),
    CONSTRAINT end_time CHECK (end_time > start_time)
);

CREATE TABLE biddings (
    id int PRIMARY KEY DEFAULT nextval('biding_id_seq'),
    bidder int REFERENCES users(id),
    task int REFERENCES tasks(id) 
        ON DELETE CASCADE NOT NULL
);

CREATE TABLE assignments (
    id int PRIMARY KEY DEFAULT nextval('assignment_id_seq'),
    task int REFERENCES tasks(id)
        ON DELETE CASCADE NOT NULL,
    assignee int REFERENCES users(id) NOT NULL,
    is_done boolean NOT NULL DEFAULT false,
    UNIQUE (task)
);

CREATE TABLE payments (
    id int PRIMARY KEY DEFAULT nextval('payment_id_seq'),
    task int REFERENCES tasks(id) ON DELETE CASCADE,
    payer int,
    card varchar(16),
    FOREIGN KEY (payer, card) REFERENCES credit_cards(owner, card_number)
        ON DELETE CASCADE
);

ALTER TABLE tasks ADD total_salary INTEGER;

CREATE OR REPLACE FUNCTION calculateTotalSalary(taskNum INTEGER)
RETURNS INTEGER AS $$
DECLARE totalSalary INTEGER; days DOUBLE PRECISION; hours DOUBLE PRECISION; mins DOUBLE PRECISION;
startTime timestamp; endTime timestamp; mountPerHour INTEGER;
BEGIN
SELECT start_time, end_time, salary INTO startTime, endTime, mountPerHour FROM tasks WHERE id = taskNum;
mins = extract(MINUTE FROM (endTime - startTime));
days = extract(DAY FROM (endTime - startTime));
hours = extract(HOUR FROM (endTime - startTime)) + days * 24;
IF mins > 0 THEN hours = hours + 1;
ELSE END IF;
totalSalary = hours * mountPerHour;
RETURN totalSalary;
END; $$
LANGUAGE PLPGSQL;

CREATE OR REPLACE FUNCTION addTaskTotalSalary()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE tasks
    SET total_salary = calculateTotalSalary(new.id)
    WHERE id = new.id;
    RETURN NULL;
END; $$
LANGUAGE PLPGSQL;

CREATE OR REPLACE FUNCTION updateTaskTotalSalary()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE tasks
    SET total_salary = calculateTotalSalary(new.id)
    WHERE id = new.id;
    RETURN NULL;
END; $$
LANGUAGE PLPGSQL;

CREATE TRIGGER addTask
AFTER INSERT
ON tasks
FOR EACH ROW
EXECUTE PROCEDURE addTaskTotalSalary();

CREATE TRIGGER updateTask
AFTER UPDATE of salary
ON tasks
FOR EACH ROW
EXECUTE PROCEDURE updateTaskTotalSalary();


INSERT INTO regions(name) VALUES ('Clementi');
INSERT INTO regions(name) VALUES ('Tampines');
INSERT INTO regions(name) VALUES ('Queens Town');
INSERT INTO regions(name) VALUES ('Holland Village');
INSERT INTO regions(name) VALUES ('Changi');

INSERT INTO users(name, email, password, is_admin, region) VALUES ('Yang Zhuohan', 'billstark1996@gmail.com', '123456', true, 0);
INSERT INTO users(name, email, password, is_admin, region) VALUES ('Sun Mingyang', 'sun.mingyang@u.nus.edu', '123456', true, 0);
INSERT INTO users(name, email, password, is_admin, region) VALUES ('Wu Zefeng', 'flamesdesperado@gmail.com', '123456', true, 0);
INSERT INTO users(name, email, password, is_admin, region) VALUES ('Li Zihan', 'e0012710@u.nus.edu', '123456', true, 0);
INSERT INTO users(name, email, password, is_admin, region) VALUES ('Deng Yue', 'dengyue@yyy.com', '123456', true, 0);

INSERT INTO users(name, email, password, region) VALUES ('Jiang Haotian', 'e0012663@u.nus.edu', '123456', 0);
INSERT INTO users(name, email, password, region) VALUES ('Wu Hanqing', 'e0012689@u.nus.edu', '123456', 1);
INSERT INTO users(name, email, password, region) VALUES ('Chen Ke', 'e0012717@u.nus.edu', '123456', 1);
INSERT INTO users(name, email, password, region) VALUES ('Luo Yuyang', 'e0012652@u.nus.edu', '123456', 2);
INSERT INTO users(name, email, password, region) VALUES ('Duan Yichen', 'e0012639@u.nus.edu', '123456', 0);

INSERT INTO credit_cards(card_number, owner) VALUES ('1217528338469765', 0);
INSERT INTO credit_cards(card_number, owner) VALUES ('9907232816517776', 1);
INSERT INTO credit_cards(card_number, owner) VALUES ('2384753088271560', 2);
INSERT INTO credit_cards(card_number, owner) VALUES ('8261890571873179', 3);
INSERT INTO credit_cards(card_number, owner) VALUES ('0159338814002797', 4);
INSERT INTO credit_cards(card_number, owner) VALUES ('2953670727111960', 5);
INSERT INTO credit_cards(card_number, owner) VALUES ('4025347713735174', 6);
INSERT INTO credit_cards(card_number, owner) VALUES ('4007339852045690', 7);
INSERT INTO credit_cards(card_number, owner) VALUES ('7930161340703607', 8);
INSERT INTO credit_cards(card_number, owner) VALUES ('7930161340703607', 9);

INSERT INTO categories(name) VALUES('Dusting');
INSERT INTO categories(name) VALUES('Sweeping');
INSERT INTO categories(name) VALUES('Laundry');
INSERT INTO categories(name) VALUES('Delivering');
INSERT INTO categories(name) VALUES('Car maintaining');
INSERT INTO categories(name) VALUES('Kitchen cleaning');
INSERT INTO categories(name) VALUES('Bathroom cleaning');
INSERT INTO categories(name) VALUES('Pet caring');
INSERT INTO categories(name) VALUES('Plant caring');
INSERT INTO categories(name) VALUES('Babysisting');
INSERT INTO categories(name) VALUES('Meal preparing');
INSERT INTO categories(name) VALUES('Translating');
INSERT INTO categories(name) VALUES('Tuor guiding');
INSERT INTO categories(name) VALUES('Teaching');
