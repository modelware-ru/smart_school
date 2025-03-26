2025-03-26
---

INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (135, 2, 'studentSerieList');
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (135, 3, 'ALLOW', 1, '{}', 3); -- studentSerieList
INSERT INTO main__page (code_name, name) VALUES ('studentSerieList', '{"title":{"ru": "Список серий ученика", "en": "Student Serie List"}}');
