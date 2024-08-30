INSERT INTO main__api (id, code_name) VALUES (1, 'signIn'); 
INSERT INTO main__api (id, code_name) VALUES (2, 'recoveryPassword'); 
INSERT INTO main__api (id, code_name) VALUES (3, 'signOut');
INSERT INTO main__api (id, code_name) VALUES (4, 'saveParallel');
INSERT INTO main__api (id, code_name) VALUES (5, 'saveGroup');
INSERT INTO main__api (id, code_name) VALUES (6, 'saveTeacher');
INSERT INTO main__api (id, code_name) VALUES (7, 'removeParallel');
INSERT INTO main__api (id, code_name) VALUES (8, 'removeGroup');
INSERT INTO main__api (id, code_name) VALUES (9, 'blockTeacher');
INSERT INTO main__api (id, code_name) VALUES (10, 'removeTeacher');
INSERT INTO main__api (id, code_name) VALUES (11, 'saveSubject');
INSERT INTO main__api (id, code_name) VALUES (12, 'removeSubject');
INSERT INTO main__api (id, code_name) VALUES (13, 'saveStudent');
INSERT INTO main__api (id, code_name) VALUES (14, 'removeStudent');
INSERT INTO main__api (id, code_name) VALUES (15, 'changeClass');
INSERT INTO main__api (id, code_name) VALUES (16, 'changeGroup');
INSERT INTO main__api (id, code_name) VALUES (17, 'saveTopic');
INSERT INTO main__api (id, code_name) VALUES (18, 'removeTopic');
INSERT INTO main__api (id, code_name) VALUES (19, 'saveCategoryTag');
INSERT INTO main__api (id, code_name) VALUES (20, 'removeCategoryTag');

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
INSERT INTO main__page (id, code_name, name) VALUES (12, 'subject', '{"title":{"ru": "Предмет", "en": "Subject"}}');
INSERT INTO main__page (id, code_name, name) VALUES (13, 'subjectList', '{"title":{"ru": "Список предметов", "en": "Subject List"}}');
INSERT INTO main__page (id, code_name, name) VALUES (14, 'student', '{"title":{"ru": "Ученик", "en": "Student"}}');
INSERT INTO main__page (id, code_name, name) VALUES (15, 'studentList', '{"title":{"ru": "Список учеников", "en": "Student List"}}');
INSERT INTO main__page (id, code_name, name) VALUES (16, 'studentListChangeClass', '{"title":{"ru": "Смена класса", "en": "Change Class"}}');
INSERT INTO main__page (id, code_name, name) VALUES (17, 'studentListChangeGroup', '{"title":{"ru": "Смена группы", "en": "Change Group"}}');
INSERT INTO main__page (id, code_name, name) VALUES (18, 'studentClassGroupHistory', '{"title":{"ru": "История ученика", "en": "Student History"}}');
INSERT INTO main__page (id, code_name, name) VALUES (19, 'topicList', '{"title":{"ru": "Список тем задач", "en": "Topic List"}}');
INSERT INTO main__page (id, code_name, name) VALUES (20, 'topic', '{"title":{"ru": "Тема задач", "en": "Topic"}}');
INSERT INTO main__page (id, code_name, name) VALUES (21, 'categoryTagList', '{"title":{"ru": "Список категорий и тегов", "en": "Category Tag List"}}');
INSERT INTO main__page (id, code_name, name) VALUES (22, 'categoryTag', '{"title":{"ru": "Категория и теги", "en": "Category Tag"}}');

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
    
INSERT INTO main__user (id, first_name, last_name, middle_name, login, password, email, account_id) VALUES (1, 'Любовь', 'Корешкова', '', 'luba', 'xdCgiX8fHlWm.', "luba@mail.ru", 1);
