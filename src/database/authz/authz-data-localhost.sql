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
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (10, 1, 'removeTeacher');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (11, 1, 'saveSubject');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (12, 1, 'removeSubject');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (13, 1, 'saveStudent');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (14, 1, 'removeStudent');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (15, 1, 'changeClass');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (16, 1, 'changeGroup');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (17, 1, 'saveTopic');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (18, 1, 'removeTopic');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (19, 1, 'saveCategoryTag');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (20, 1, 'removeCategoryTag');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (21, 1, 'saveSchoolYear');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (22, 1, 'removeSchoolYear');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (23, 1, 'saveSerie');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (24, 1, 'removeSerie');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (25, 1, 'saveLesson');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (26, 1, 'removeLesson');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (27, 1, 'saveTeacherGroup');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (28, 1, 'deleteTeacherGroup');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (29, 1, 'addSerieToLesson');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (30, 1, 'removeSerieFromLesson');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (31, 1, 'saveStudentSolution');

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
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (111, 2, 'subject');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (112, 2, 'subjectList');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (113, 2, 'student');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (114, 2, 'studentList');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (115, 2, 'studentListChangeClass');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (116, 2, 'studentListChangeGroup');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (117, 2, 'studentClassGroupHistory');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (118, 2, 'topicList');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (119, 2, 'topic');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (120, 2, 'categoryTagList');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (121, 2, 'categoryTag');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (122, 2, 'schoolYearList');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (123, 2, 'schoolYear');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (124, 2, 'serieList');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (125, 2, 'serie');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (126, 2, 'schedule');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (127, 2, 'lesson');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (128, 2, 'lessonJournal');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (129, 2, 'teacherGroup');
INSERT INTO authz__permission (id, action_id, resource_code_name_mask) VALUES (130, 2, 'studentSerieSolution');

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
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (10, 2, 'ALLOW', 1, '{}', 2); -- removeTeacher
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (11, 2, 'ALLOW', 1, '{}', 2); -- saveSubject
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (12, 2, 'ALLOW', 1, '{}', 2); -- removeSubject
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (13, 2, 'ALLOW', 1, '{}', 2); -- saveStudent
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (14, 2, 'ALLOW', 1, '{}', 2); -- removeStudent
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (15, 2, 'ALLOW', 1, '{}', 2); -- changeClass
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (16, 2, 'ALLOW', 1, '{}', 2); -- changeGroup
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (21, 2, 'ALLOW', 1, '{}', 2); -- saveSchoolYear
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (22, 2, 'ALLOW', 1, '{}', 2); -- removeSchoolYear
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (27, 2, 'ALLOW', 1, '{}', 2); -- saveTeacherGroup
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (28, 2, 'ALLOW', 1, '{}', 2); -- deleteTeacherGroup

-- Администратор ---- Показ страницы
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (103, 2, 'ALLOW', 1, '{}', 2); -- adminIndex
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (104, 2, 'ALLOW', 1, '{}', 2); -- parallel
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (105, 2, 'ALLOW', 1, '{}', 2); -- parallelList
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (106, 2, 'ALLOW', 1, '{}', 2); -- group
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (107, 2, 'ALLOW', 1, '{}', 2); -- groupList
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (108, 2, 'ALLOW', 1, '{}', 2); -- teacher
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (109, 2, 'ALLOW', 1, '{}', 2); -- teacherList
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (111, 2, 'ALLOW', 1, '{}', 2); -- subject
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (112, 2, 'ALLOW', 1, '{}', 2); -- subjectList
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (113, 2, 'ALLOW', 1, '{}', 2); -- student
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (114, 2, 'ALLOW', 1, '{}', 2); -- studentList
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (115, 2, 'ALLOW', 1, '{}', 2); -- studentListChangeClass
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (116, 2, 'ALLOW', 1, '{}', 2); -- studentListChangeGroup
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (117, 2, 'ALLOW', 1, '{}', 2); -- studentClassGroupHistory
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (122, 2, 'ALLOW', 1, '{}', 2); -- schoolYearList
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (123, 2, 'ALLOW', 1, '{}', 2); -- schoolYear
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (129, 2, 'ALLOW', 1, '{}', 2); -- teacherGroup


-- Преподаватель ---- Вызов метода
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (17, 3, 'ALLOW', 1, '{}', 3); -- saveStudent
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (18, 3, 'ALLOW', 1, '{}', 3); -- removeStudent
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (19, 3, 'ALLOW', 1, '{}', 3); -- saveCategoryTag
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (20, 3, 'ALLOW', 1, '{}', 3); -- removeCategoryTag
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (23, 3, 'ALLOW', 1, '{}', 3); -- saveSerie
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (24, 3, 'ALLOW', 1, '{}', 3); -- removeSerie
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (25, 3, 'ALLOW', 1, '{}', 3); -- saveLesson
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (26, 3, 'ALLOW', 1, '{}', 3); -- removeLesson
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (29, 3, 'ALLOW', 1, '{}', 3); -- addSerieToLesson
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (30, 3, 'ALLOW', 1, '{}', 3); -- removeSerieFromLesson
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (31, 3, 'ALLOW', 1, '{}', 3); -- saveStudentSolution

-- Преподаватель ---- Показ страницы
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (110, 3, 'ALLOW', 1, '{}', 3); -- teacherIndex
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (108, 3, 'ALLOW', 1, '{"self": true}', 3); -- teacher
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (118, 3, 'ALLOW', 1, '{}', 3); -- topicList
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (119, 3, 'ALLOW', 1, '{}', 3); -- topic
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (120, 3, 'ALLOW', 1, '{}', 3); -- categoryTagList
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (121, 3, 'ALLOW', 1, '{}', 3); -- categoryTag
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (124, 3, 'ALLOW', 1, '{}', 3); -- serieList
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (125, 3, 'ALLOW', 1, '{}', 3); -- serie
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (107, 3, 'ALLOW', 1, '{}', 3); -- groupList
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (106, 3, 'ALLOW', 1, '{}', 3); -- group
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (126, 3, 'ALLOW', 1, '{}', 3); -- schedule
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (127, 3, 'ALLOW', 1, '{}', 3); -- lesson
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (128, 3, 'ALLOW', 1, '{}', 3); -- lessonJournal
INSERT INTO authz__role_permission (permission_id, role_id, permission, prio, options, role_state_id) VALUES (130, 3, 'ALLOW', 1, '{}', 3); -- studentSerieSolution

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
