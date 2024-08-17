INSERT INTO authz__action (id, code_name, name, resource_type) VALUES (1, 'API_CALL', 'Вызов метода', 'main__api');
INSERT INTO authz__action (id, code_name, name, resource_type) VALUES (2, 'PAGE_SHOW', 'Показ страницы', 'main__page');
INSERT INTO authz__action (id, code_name, name, resource_type) VALUES (3, 'WIDGET_SHOW', 'Показ виджета', 'main__widget');

-- Вызов метода
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (1, 1, 'signIn'); 
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (2, 1, 'recoveryPassword'); 
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (3, 1, 'signOut');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (4, 1, 'saveParallel');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (5, 1, 'saveGroup');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (6, 1, 'saveTeacher');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (7, 1, 'removeParallel');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (8, 1, 'removeGroup');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (9, 1, 'blockTeacher');
-- Показ страницы
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (100, 2, 'guestIndex'); 
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (101, 2, 'recoveryPassword');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (102, 2, 'message');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (103, 2, 'adminIndex');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (104, 2, 'parallel');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (105, 2, 'parallelList');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (106, 2, 'group');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (107, 2, 'groupList');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (108, 2, 'teacher');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (109, 2, 'teacherList');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (110, 2, 'teacherIndex');


INSERT INTO authz__role (id, code_name, name, description) VALUES (1, 'Guest', 'Гость', '');
INSERT INTO authz__role (id, code_name, name, description) VALUES (2, 'Admin', 'Администратор', '');
INSERT INTO authz__role (id, code_name, name, description) VALUES (3, 'Teacher', 'Преподаватель', '');

INSERT INTO authz__role_state (id, code_name, name, role_id) VALUES (1, 'active', 'Активный', 1);
INSERT INTO authz__role_state (id, code_name, name, role_id) VALUES (2, 'active', 'Активный', 2);
INSERT INTO authz__role_state (id, code_name, name, role_id) VALUES (3, 'active', 'Активный', 3);
INSERT INTO authz__role_state (id, code_name, name, role_id) VALUES (4, 'blocked', 'Заблокированный', 3);

-- Гость ---- Вызов метода
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (1, 1, 'ALLOW', 1, '{}', 1); -- signIn
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (2, 1, 'ALLOW', 1, '{}', 1); -- recoveryPassword
-- Гость ---- Показ страницы
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (100, 1, 'ALLOW', 1, '{}', 1); -- guestIndex
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (101, 1, 'ALLOW', 1, '{}', 1); -- recoveryPassword

-- Администратор ---- Вызов метода
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (4, 2, 'ALLOW', 1, '{}', 2); -- saveParallel
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (5, 2, 'ALLOW', 1, '{}', 2); -- saveGroup
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (6, 2, 'ALLOW', 1, '{}', 2); -- saveTeacher
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (7, 2, 'ALLOW', 1, '{}', 2); -- removeParallel
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (8, 2, 'ALLOW', 1, '{}', 2); -- removeGroup
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (9, 2, 'ALLOW', 1, '{}', 2); -- blockTeacher

-- Администратор ---- Показ страницы
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (103, 2, 'ALLOW', 1, '{}', 2); -- adminIndex
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (104, 2, 'ALLOW', 1, '{}', 2); -- parallel
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (105, 2, 'ALLOW', 1, '{}', 2); -- parallelList
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (106, 2, 'ALLOW', 1, '{}', 2); -- group
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (107, 2, 'ALLOW', 1, '{}', 2); -- groupList
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (108, 2, 'ALLOW', 1, '{}', 2); -- teacher
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (109, 2, 'ALLOW', 1, '{}', 2); -- teacherList

-- Преподаватель ---- Вызов метода

-- Преподаватель ---- Показ страницы
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (110, 3, 'ALLOW', 1, '{}', 3); -- teacherIndex
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (108, 3, 'ALLOW', 1, '{"self": true}', 3); -- teacher

-- Account
INSERT INTO authz__account (id) VALUES (1);
INSERT INTO authz__account (id) VALUES (2);
INSERT INTO authz__account (id) VALUES (3);

-- Группы
INSERT INTO authz__group (id, name, description, role_id, role_state_id, options, prio) VALUES (1, 'SignOut', 'Возможность сделать signOut', NULL, NULL, '{}', 1);
INSERT INTO authz__group_permission (permission_id, group_id, permission, prio) VALUES (3, 1, 'ALLOW', 1); -- SignOut группа -> Вызов метода sighOut

-- Admin
INSERT INTO authz__account_role (account_id, role_id, role_state_id, `order`) VALUES (1, 2, 2, 1);
INSERT INTO authz__account_role (account_id, role_id, role_state_id, `order`) VALUES (1, 3, 3, 2);
INSERT INTO authz__account_role (account_id, role_id, role_state_id, `order`) VALUES (2, 3, 3, 1);
INSERT INTO authz__account_role (account_id, role_id, role_state_id, `order`) VALUES (3, 3, 4, 1);

INSERT INTO authz__account_group (account_id, group_id) VALUES (1, 1);
INSERT INTO authz__account_group (account_id, group_id) VALUES (2, 1);
INSERT INTO authz__account_group (account_id, group_id) VALUES (3, 1);
