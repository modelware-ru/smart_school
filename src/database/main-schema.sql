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


CREATE TABLE main__categoryTag (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    name VARCHAR(100) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__categoryTag___unique_name UNIQUE (name)
) ENGINE = InnoDB;

CREATE TABLE main__topic (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    name VARCHAR(100) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__topic___unique_name UNIQUE (name)
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
    CONSTRAINT main__user___unique_login UNIQUE (login),
    CONSTRAINT main__user___unique_email UNIQUE (email)
) ENGINE = InnoDB;

CREATE TABLE main__serie (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    name VARCHAR(100) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__serie___unique_name UNIQUE (name)
) ENGINE = InnoDB;

CREATE TABLE main__parallel (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    name VARCHAR(100) DEFAULT '' NOT NULL,
    number VARCHAR(10) DEFAULT '' NOT NULL,
    show_in_group ENUM ('Y', 'N') NOT NULL,
    `order` TINYINT NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__parallel___unique_name UNIQUE (name),
    CONSTRAINT main__parallel___unique_number UNIQUE (number)
) ENGINE = InnoDB;

CREATE TABLE main__subject (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    name VARCHAR(100) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__subject___unique_name UNIQUE (name)
) ENGINE = InnoDB;

CREATE TABLE main__schoolYear (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    name VARCHAR(100) DEFAULT '' NOT NULL,
    start_date DATE NOT NULL,
    finish_date DATE NOT NULL,
    is_current ENUM('Y', 'N') DEFAULT 'N' NOT NULL,
    PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE main__tag (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    categoryTag_id INT UNSIGNED NOT NULL,
    name VARCHAR(100) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__tag___categoryTag_id FOREIGN KEY (categoryTag_id) REFERENCES main__categoryTag(id),
    CONSTRAINT main__tag___unique_name_categoryTag_id UNIQUE (name, categoryTag_id)
) ENGINE = InnoDB;

CREATE TABLE main__task (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    name VARCHAR(100) DEFAULT '' NOT NULL,
    topic_id INT UNSIGNED NOT NULL,
    path VARCHAR(1000) DEFAULT '' NOT NULL,
    note TEXT,
    PRIMARY KEY (id),
    CONSTRAINT main__task___topic_id FOREIGN KEY (topic_id) REFERENCES main__topic(id),
    CONSTRAINT main__task___unique_name UNIQUE (name)
) ENGINE = InnoDB;

CREATE TABLE main__group (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    parallel_id INT UNSIGNED NOT NULL,
    name VARCHAR(100) DEFAULT '' NOT NULL,
    `order` TINYINT NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__group___parallel_id FOREIGN KEY (parallel_id) REFERENCES main__parallel(id),
    CONSTRAINT main__group___unique_parallel_id_name UNIQUE (parallel_id, name)
) ENGINE = InnoDB;

CREATE TABLE main__task_tag (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    task_id INT UNSIGNED NOT NULL,
    tag_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__task_tag___task_id FOREIGN KEY (task_id) REFERENCES main__task(id) ON DELETE CASCADE,
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
    schoolYear_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__user_group___user_id FOREIGN KEY (user_id) REFERENCES main__user(id),
    CONSTRAINT main__user_group___group_id FOREIGN KEY (group_id) REFERENCES main__group(id),
    CONSTRAINT main__user_group___schoolYear_id FOREIGN KEY (schoolYear_id) REFERENCES main__schoolYear(id)
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

CREATE TABLE main__student_serie (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    student_id INT UNSIGNED NOT NULL,
    serie_id INT UNSIGNED NOT NULL,
    group_id INT UNSIGNED NOT NULL,
    lesson_id INT UNSIGNED,
    type ENUM ('CLASS', 'HOME') NOT NULL,
    `date` DATETIME NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__student_serie___student_id FOREIGN KEY (student_id) REFERENCES main__student(id),
    CONSTRAINT main__student_serie___serie_id FOREIGN KEY (serie_id) REFERENCES main__serie(id),
    CONSTRAINT main__student_serie___lesson_id FOREIGN KEY (lesson_id) REFERENCES main__lesson(id),
    CONSTRAINT main__student_serie___group_id FOREIGN KEY (group_id) REFERENCES main__group(id),
    CONSTRAINT main__student_serie___unique_student_id_serie_id UNIQUE (student_id, serie_id)
) ENGINE = InnoDB;
 
CREATE TABLE main__studentSerie_serieTask (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    student_serie_id INT UNSIGNED NOT NULL,
    serie_task_id INT UNSIGNED NOT NULL,
    value TINYINT,
    `date` DATETIME NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__student_serieTask___student_serie_id FOREIGN KEY (student_serie_id) REFERENCES main__student_serie(id),
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
    attendanceDict_id INT UNSIGNED,
    note TEXT,
    PRIMARY KEY (id),
    CONSTRAINT main__student_lesson___student_id FOREIGN KEY (student_id) REFERENCES main__student(id),
    CONSTRAINT main__student_lesson___lesson_id FOREIGN KEY (lesson_id) REFERENCES main__lesson(id),
    CONSTRAINT main__student_lesson___attendanceDict_id FOREIGN KEY (attendanceDict_id) REFERENCES main__attendance_Dict(id)
) ENGINE = InnoDB;

CREATE TABLE main__lesson_serie (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    lesson_id INT UNSIGNED NOT NULL,
    serie_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__lesson_serie___lesson_id FOREIGN KEY (lesson_id) REFERENCES main__lesson(id),
    CONSTRAINT main__lesson_serie___serie_id FOREIGN KEY (serie_id) REFERENCES main__serie(id)
) ENGINE = InnoDB;
