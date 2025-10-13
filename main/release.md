2025-10-13
---
ALTER TABLE main__serie DROP COLUMN max_value;
ALTER TABLE main__serie ADD max_value_home TINYINT DEFAULT 0;
ALTER TABLE main__serie ADD max_value_class TINYINT DEFAULT 0;

2025-10-12
---
ALTER TABLE main__serie ADD max_value TINYINT DEFAULT 0;
ALTER TABLE main__student_serie ADD max_value TINYINT DEFAULT 0;


2025-09-01
---
CREATE TABLE main__task_subtopic (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    task_id INT UNSIGNED NOT NULL,
    subtopic_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__task_subtopic___task_id FOREIGN KEY (task_id) REFERENCES main__task(id),
    CONSTRAINT main__task_subtopic___subtopic_id FOREIGN KEY (subtopic_id) REFERENCES main__subtopic(id),
    CONSTRAINT main__task_subtopic___unique_task_id_subtopic_id UNIQUE (task_id, subtopic_id)
) ENGINE = InnoDB;

ALTER TABLE main__task DROP FOREIGN KEY main__task___topic_id;
ALTER TABLE main__task DROP COLUMN topic_id;

2025-08-24
---
CREATE TABLE main__subtopic (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    topic_id INT UNSIGNED NOT NULL,
    name VARCHAR(100) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT main__subtopic___topic_id FOREIGN KEY (topic_id) REFERENCES main__topic(id),
    CONSTRAINT main__subtopic___unique_topic_id_name UNIQUE (topic_id, name)
) ENGINE = InnoDB;

2025-03-26
---

INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (135, 2, 'studentSerieList');
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (135, 3, 'ALLOW', 1, '{}', 3); -- studentSerieList
INSERT INTO main__page (code_name, name) VALUES ('studentSerieList', '{"title":{"ru": "Список серий ученика", "en": "Student Serie List"}}');
