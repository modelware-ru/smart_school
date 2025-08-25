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
