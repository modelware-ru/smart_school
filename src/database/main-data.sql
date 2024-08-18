INSERT INTO main__api (id, code_name) VALUES (1, 'signIn'); 
INSERT INTO main__api (id, code_name) VALUES (2, 'recoveryPassword'); 
INSERT INTO main__api (id, code_name) VALUES (3, 'signOut');
INSERT INTO main__api (id, code_name) VALUES (4, 'saveParallel');
INSERT INTO main__api (id, code_name) VALUES (5, 'saveGroup');
INSERT INTO main__api (id, code_name) VALUES (6, 'saveTeacher');
INSERT INTO main__api (id, code_name) VALUES (7, 'removeParallel');
INSERT INTO main__api (id, code_name) VALUES (8, 'removeGroup');
INSERT INTO main__api (id, code_name) VALUES (9, 'blockTeacher');

INSERT INTO main__page (id, code_name, name) VALUES (1, 'guestIndex', '{"title":{"ru": "Вход", "en": "Sign In"}}'); 
INSERT INTO main__page (id, code_name, name) VALUES (2, 'recoveryPassword', '{"title":{"ru": "Восстановление пароля", "en": "Recovery Password"}}');
INSERT INTO main__page (id, code_name, name) VALUES (3, 'message', '{"title":{"ru": "Сообщение", "en": "Message"}}');
INSERT INTO main__page (id, code_name, name) VALUES (4, 'adminIndex', '{"title":{"ru": "Меню", "en": "Menu"}}');
INSERT INTO main__page (id, code_name, name) VALUES (5, 'parallel', '{"title":{"ru": "Параллель", "en": "Parallel"}}');
INSERT INTO main__page (id, code_name, name) VALUES (6, 'parallelList', '{"title":{"ru": "Список параллелей", "en": "Parallel List"}}');
INSERT INTO main__page (id, code_name, name) VALUES (7, 'group', '{"title":{"ru": "Группа", "en": "Group"}}');
INSERT INTO main__page (id, code_name, name) VALUES (8, 'groupList', '{"title":{"ru": "Список групп", "en": "Group List"}}');
INSERT INTO main__page (id, code_name, name) VALUES (9, 'teacher', '{"title":{"ru": "Преподаватель", "en": "Teacher"}}');
INSERT INTO main__page (id, code_name, name) VALUES (10, 'teacherList', '{"title":{"ru": "Список преподавателей", "en": "Teacher List"}}');
INSERT INTO main__page (id, code_name, name) VALUES (11, 'teacherIndex', '{"title":{"ru": "Меню", "en": "Menu"}}');

-- INSERT INTO main__widget (id, code_name) VALUES (1, 'signUpForm');
-- INSERT INTO main__widget (id, code_name) VALUES (2, 'guestNavigator');
-- INSERT INTO main__widget (id, code_name) VALUES (3, 'langSwitcher');
-- INSERT INTO main__widget (id, code_name) VALUES (4, 'messageNavigator');
-- INSERT INTO main__widget (id, code_name) VALUES (5, 'notificator');
-- INSERT INTO main__widget (id, code_name) VALUES (6, 'themeSwitcher');
-- INSERT INTO main__widget (id, code_name) VALUES (7, 'userNavigator');

INSERT INTO main__parallel ( id, name, number, show_in_group) VALUES (1, 'Первая', '1', 'Y');
INSERT INTO main__parallel ( id, name, number, show_in_group) VALUES (2, 'Вторая', '2', 'Y');
INSERT INTO main__parallel ( id, name, number, show_in_group) VALUES (3, 'Третья', '3', 'Y');
INSERT INTO main__parallel ( id, name, number, show_in_group) VALUES (4, 'Четвертая', '4', 'Y');
INSERT INTO main__parallel ( id, name, number, show_in_group) VALUES (5, 'Пятая', '5', 'N');
    
INSERT INTO main__user (id, first_name, last_name, middle_name, login, password, email, account_id) VALUES (1, 'Петр', 'Петров', 'Петрович', 'petr', 'xd50zdGGOc2o6', "denis.ivanov@mail.ru", 1);
INSERT INTO main__user (id, first_name, last_name, middle_name, login, password, email, account_id) VALUES (2, 'Иван', 'Иванов', 'Иванович', 'ivan', 'xd50zdGGOc2o6', "denis.ivanov+01@mail.ru", 2);
INSERT INTO main__user (id, first_name, last_name, middle_name, login, password, email, account_id) VALUES (3, 'Виктор', 'Викторов', 'Викторович', 'victor', 'xd50zdGGOc2o6', "denis.ivanov+02@mail.ru", 3);

INSERT INTO main__group (id, parallel_id, name) VALUES (1, 1, 'Аналитики');
INSERT INTO main__group (id, parallel_id, name) VALUES (2, 1, 'Навигаторы');
INSERT INTO main__group (id, parallel_id, name) VALUES (3, 1, 'Исследователи');
INSERT INTO main__group (id, parallel_id, name) VALUES (4, 2, 'Аналитики');
INSERT INTO main__group (id, parallel_id, name) VALUES (5, 2, 'Навигаторы');
INSERT INTO main__group (id, parallel_id, name) VALUES (6, 2, 'Исследователи');
INSERT INTO main__group (id, parallel_id, name) VALUES (7, 3, 'Аналитики');
INSERT INTO main__group (id, parallel_id, name) VALUES (8, 3, 'Навигаторы');
INSERT INTO main__group (id, parallel_id, name) VALUES (9, 3, 'Исследователи');
INSERT INTO main__group (id, parallel_id, name) VALUES (10, 4, 'Аналитики');
INSERT INTO main__group (id, parallel_id, name) VALUES (11, 4, 'Навигаторы');
INSERT INTO main__group (id, parallel_id, name) VALUES (12, 4, 'Исследователи');

INSERT INTO main__user_group (user_id, group_id) VALUES (1, 1);
INSERT INTO main__user_group (user_id, group_id) VALUES (2, 1);
INSERT INTO main__user_group (user_id, group_id) VALUES (3, 1);
INSERT INTO main__user_group (user_id, group_id) VALUES (1, 2);
INSERT INTO main__user_group (user_id, group_id) VALUES (1, 3);
INSERT INTO main__user_group (user_id, group_id) VALUES (2, 4);
INSERT INTO main__user_group (user_id, group_id) VALUES (2, 5);
INSERT INTO main__user_group (user_id, group_id) VALUES (2, 6);
INSERT INTO main__user_group (user_id, group_id) VALUES (3, 4);
INSERT INTO main__user_group (user_id, group_id) VALUES (3, 5);
INSERT INTO main__user_group (user_id, group_id) VALUES (3, 6);
INSERT INTO main__user_group (user_id, group_id) VALUES (3, 7);
INSERT INTO main__user_group (user_id, group_id) VALUES (3, 8);
INSERT INTO main__user_group (user_id, group_id) VALUES (3, 9);
