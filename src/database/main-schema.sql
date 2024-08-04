CREATE TABLE main__api (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    code_name VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE main__page (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    code_name VARCHAR(100) NOT NULL,
    name JSON NOT NULL,
    PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE main__widget (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    code_name VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
) ENGINE = InnoDB;


CREATE TABLE main__tagCategory (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    name VARCHAR(100) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__tagCategory___unique_1 UNIQUE (name)
) ENGINE = InnoDB;

CREATE TABLE main__topic (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    name VARCHAR(100) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__topic___unique_1 UNIQUE (name)
) ENGINE = InnoDB;

CREATE TABLE main__attendance_Dict (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    name VARCHAR(100) DEFAULT '' NOT NULL,
    display VARCHAR(5) DEFAULT '' NOT NULL,
    `default` ENUM ('Y', 'N') NOT NULL,
    PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE main__student (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    first_name VARCHAR(100) DEFAULT '',
    last_name VARCHAR(100) DEFAULT '',
    middle_name VARCHAR(100) DEFAULT '',
    PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE main__user (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    account_id INT UNSIGNED NOT NULL,
    first_name VARCHAR(100) DEFAULT '',
    last_name VARCHAR(100) DEFAULT '',
    middle_name VARCHAR(100) DEFAULT '',
    login VARCHAR(50) NOT NULL,
    password CHAR(20) NOT NULL,
    email VARCHAR(100),
    PRIMARY KEY (id),
    CONSTRAINT main__user___account_id FOREIGN KEY (account_id) REFERENCES authz__account(id),
    CONSTRAINT main__user___unique_1 UNIQUE (login),
    CONSTRAINT main__user___unique_2 UNIQUE (email)
) ENGINE = InnoDB;

CREATE TABLE main__serie (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    name VARCHAR(100) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__serie___unique_1 UNIQUE (name)
) ENGINE = InnoDB;

CREATE TABLE main__parallel (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    name_text VARCHAR(100) DEFAULT '' NOT NULL,
    name_number VARCHAR(10) DEFAULT '' NOT NULL,
    show_in_group ENUM ('Y', 'N') NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__parallel___unique_1 UNIQUE (name_text),
    CONSTRAINT main__parallel___unique_2 UNIQUE (name_number)
) ENGINE = InnoDB;

CREATE TABLE main__subject (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    name VARCHAR(100) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__subject___unique_1 UNIQUE (name)
) ENGINE = InnoDB;

CREATE TABLE main__tag (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    name VARCHAR(100) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__tag___unique_1 UNIQUE (name)
) ENGINE = InnoDB;

CREATE TABLE main__task (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    path VARCHAR(1000) DEFAULT '' NOT NULL,
    note TEXT,
    PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE main__group (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    parallel_id INT UNSIGNED NOT NULL,
    name VARCHAR(100) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__group___parallel_id FOREIGN KEY (parallel_id) REFERENCES main__parallel(id),
    CONSTRAINT main__group___unique_1 UNIQUE (name)
) ENGINE = InnoDB;

CREATE TABLE main__task_tag (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    task_id INT UNSIGNED NOT NULL,
    tag_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__task_tag___task_id FOREIGN KEY (task_id) REFERENCES main__task(id),
    CONSTRAINT main__task_tag___tag_id FOREIGN KEY (tag_id) REFERENCES main__tag(id)
) ENGINE = InnoDB;

CREATE TABLE main__serie_task (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    serie_id INT UNSIGNED NOT NULL,
    task_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__serie_task___serie_id FOREIGN KEY (serie_id) REFERENCES main__serie(id),
    CONSTRAINT main__serie_task___task_id FOREIGN KEY (task_id) REFERENCES main__task(id)
) ENGINE = InnoDB;

CREATE TABLE main__user_group (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    group_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__user_group___user_id FOREIGN KEY (user_id) REFERENCES main__user(id),
    CONSTRAINT main__user_group___group_id FOREIGN KEY (group_id) REFERENCES main__group(id)
) ENGINE = InnoDB;

CREATE TABLE main__lesson (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    subject_id INT UNSIGNED NOT NULL,
    group_id INT UNSIGNED NOT NULL,
    `date` DATETIME NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__lesson___subject_id FOREIGN KEY (subject_id) REFERENCES main__subject(id),
    CONSTRAINT main__lesson___group_id FOREIGN KEY (group_id) REFERENCES main__group(id)
) ENGINE = InnoDB;

CREATE TABLE main__student_serieTask (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    student_id INT UNSIGNED NOT NULL,
    serie_task_id INT UNSIGNED NOT NULL,
    value TINYINT,
    `date` DATETIME NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__student_serieTask___student_id FOREIGN KEY (student_id) REFERENCES main__student(id),
    CONSTRAINT main__student_serieTask___serie_task_id FOREIGN KEY (serie_task_id) REFERENCES main__serie_task(id)
) ENGINE = InnoDB;

CREATE TABLE main__student_group_Hist (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    student_id INT UNSIGNED NOT NULL,
    group_id INT UNSIGNED NOT NULL,
    reason TEXT,
    start_date DATETIME NOT NULL,
    `order` TINYINT NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__student_group_Hist___student_id FOREIGN KEY (student_id) REFERENCES main__student(id),
    CONSTRAINT main__student_group_Hist___group_id FOREIGN KEY (group_id) REFERENCES main__group(id)
) ENGINE = InnoDB;

CREATE TABLE main__student_class_Hist (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    parallel_id INT UNSIGNED,
    student_id INT UNSIGNED NOT NULL,
    letter VARCHAR(100),
    reason TEXT,
    start_date DATETIME NOT NULL,
    `order` TINYINT NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__student_class_Hist___parallel_id FOREIGN KEY (parallel_id) REFERENCES main__parallel(id),
    CONSTRAINT main__student_class_Hist___student_id FOREIGN KEY (student_id) REFERENCES main__student(id)
) ENGINE = InnoDB;

CREATE TABLE main__student_lesson (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    student_id INT UNSIGNED NOT NULL,
    lesson_id INT UNSIGNED NOT NULL,
    note TEXT,
    PRIMARY KEY (id),
    CONSTRAINT main__student_lesson___student_id FOREIGN KEY (student_id) REFERENCES main__student(id),
    CONSTRAINT main__student_lesson___lesson_id FOREIGN KEY (lesson_id) REFERENCES main__lesson(id)
) ENGINE = InnoDB;

CREATE TABLE main__lesson_serie (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    lesson_id INT UNSIGNED NOT NULL,
    serie_id INT UNSIGNED NOT NULL,
    type ENUM ('CLASS', 'HOME', 'SPECIAL') NOT NULL,
    name VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__lesson_serie___lesson_id FOREIGN KEY (lesson_id) REFERENCES main__lesson(id),
    CONSTRAINT main__lesson_serie___serie_id FOREIGN KEY (serie_id) REFERENCES main__serie(id)
) ENGINE = InnoDB;
