INSERT INTO main__api (code_name) VALUES ('signIn'); 
INSERT INTO main__api (code_name) VALUES ('recoveryPassword'); 
INSERT INTO main__api (code_name) VALUES ('signOut');
INSERT INTO main__api (code_name) VALUES ('saveParallel');
INSERT INTO main__api (code_name) VALUES ('saveGroup');
INSERT INTO main__api (code_name) VALUES ('saveTeacher');
INSERT INTO main__api (code_name) VALUES ('removeParallel');
INSERT INTO main__api (code_name) VALUES ('removeGroup');
INSERT INTO main__api (code_name) VALUES ('blockTeacher');
INSERT INTO main__api (code_name) VALUES ('removeTeacher');
INSERT INTO main__api (code_name) VALUES ('saveSubject');
INSERT INTO main__api (code_name) VALUES ('removeSubject');
INSERT INTO main__api (code_name) VALUES ('saveStudent');
INSERT INTO main__api (code_name) VALUES ('removeStudent');
INSERT INTO main__api (code_name) VALUES ('changeClass');
INSERT INTO main__api (code_name) VALUES ('changeGroup');
INSERT INTO main__api (code_name) VALUES ('saveTopic');
INSERT INTO main__api (code_name) VALUES ('removeTopic');
INSERT INTO main__api (code_name) VALUES ('saveCategoryTag');
INSERT INTO main__api (code_name) VALUES ('removeCategoryTag');
INSERT INTO main__api (code_name) VALUES ('saveSchoolYear');
INSERT INTO main__api (code_name) VALUES ('removeSchoolYear');
INSERT INTO main__api (code_name) VALUES ('saveSerie');
INSERT INTO main__api (code_name) VALUES ('removeSerie');

INSERT INTO main__page (code_name, name) VALUES ('guestIndex', '{"title":{"ru": "Вход", "en": "Sign In"}}'); 
INSERT INTO main__page (code_name, name) VALUES ('recoveryPassword', '{"title":{"ru": "Восстановление пароля", "en": "Recovery Password"}}');
INSERT INTO main__page (code_name, name) VALUES ('message', '{"title":{"ru": "Сообщение", "en": "Message"}}');
INSERT INTO main__page (code_name, name) VALUES ('adminIndex', '{"title":{"ru": "Меню", "en": "Menu"}}');
INSERT INTO main__page (code_name, name) VALUES ('parallel', '{"title":{"ru": "Параллель", "en": "Parallel"}}');
INSERT INTO main__page (code_name, name) VALUES ('parallelList', '{"title":{"ru": "Список параллелей", "en": "Parallel List"}}');
INSERT INTO main__page (code_name, name) VALUES ('group', '{"title":{"ru": "Группа", "en": "Group"}}');
INSERT INTO main__page (code_name, name) VALUES ('groupList', '{"title":{"ru": "Список групп", "en": "Group List"}}');
INSERT INTO main__page (code_name, name) VALUES ('teacher', '{"title":{"ru": "Преподаватель", "en": "Teacher"}}');
INSERT INTO main__page (code_name, name) VALUES ('teacherList', '{"title":{"ru": "Список преподавателей", "en": "Teacher List"}}');
INSERT INTO main__page (code_name, name) VALUES ('teacherIndex', '{"title":{"ru": "Меню", "en": "Menu"}}');
INSERT INTO main__page (code_name, name) VALUES ('subject', '{"title":{"ru": "Предмет", "en": "Subject"}}');
INSERT INTO main__page (code_name, name) VALUES ('subjectList', '{"title":{"ru": "Список предметов", "en": "Subject List"}}');
INSERT INTO main__page (code_name, name) VALUES ('student', '{"title":{"ru": "Ученик", "en": "Student"}}');
INSERT INTO main__page (code_name, name) VALUES ('studentList', '{"title":{"ru": "Список учеников", "en": "Student List"}}');
INSERT INTO main__page (code_name, name) VALUES ('studentListChangeClass', '{"title":{"ru": "Смена класса", "en": "Change Class"}}');
INSERT INTO main__page (code_name, name) VALUES ('studentListChangeGroup', '{"title":{"ru": "Смена группы", "en": "Change Group"}}');
INSERT INTO main__page (code_name, name) VALUES ('studentClassGroupHistory', '{"title":{"ru": "История ученика", "en": "Student History"}}');
INSERT INTO main__page (code_name, name) VALUES ('topicList', '{"title":{"ru": "Список тем задач", "en": "Topic List"}}');
INSERT INTO main__page (code_name, name) VALUES ('topic', '{"title":{"ru": "Тема задач", "en": "Topic"}}');
INSERT INTO main__page (code_name, name) VALUES ('categoryTagList', '{"title":{"ru": "Список категорий и тегов", "en": "Category Tag List"}}');
INSERT INTO main__page (code_name, name) VALUES ('categoryTag', '{"title":{"ru": "Категория и теги", "en": "Category Tag"}}');
INSERT INTO main__page (code_name, name) VALUES ('schoolYearList', '{"title":{"ru": "Список учебных годов", "en": "School Year List"}}');
INSERT INTO main__page (code_name, name) VALUES ('schoolYear', '{"title":{"ru": "Учебный год", "en": "School Year"}}');
INSERT INTO main__page (code_name, name) VALUES ('serieList', '{"title":{"ru": "Список серий годов", "en": "Serie List"}}');
INSERT INTO main__page (code_name, name) VALUES ('serie', '{"title":{"ru": "Серия", "en": "Serie"}}');

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
    
-- INSERT INTO main__user (id, first_name, last_name, middle_name, login, password, email, account_id) VALUES (1, 'Любовь', 'Корешкова', '', 'luba', 'xdCgiX8fHlWm.', "luba@mail.ru", 1);
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

INSERT INTO main__student (id, first_name, last_name, middle_name) VALUES (1, 'иван', 'иванов', 'иванович');
INSERT INTO main__student (id, first_name, last_name, middle_name) VALUES (2, 'петр', 'петров', 'петрович');
INSERT INTO main__student (id, first_name, last_name, middle_name) VALUES (3, 'сергей', 'сергеев', 'сергеевич');
INSERT INTO main__student (id, first_name, last_name, middle_name) VALUES (4, 'максим', 'максимов', 'максимович');

INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 1, 'А', 'Причина 1', '2024-01-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 1, 'Б', 'Причина 2', '2024-02-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 1, 'Б', 'Причина 3', '2024-02-01', 2);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 2, 'А', 'Причина 4', '2024-01-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 2, 'Б', 'Причина 5', '2024-02-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 2, 'B', 'Причина 6', '2024-03-01', 1);

INSERT INTO main__student_group_Hist (student_id, group_id, reason, start_date, `order`) VALUES (1, 1, 'Причина _1', '2024-01-01', 1);
INSERT INTO main__student_group_Hist (student_id, group_id, reason, start_date, `order`) VALUES (1, 2, 'Причина _2', '2024-02-01', 1);
INSERT INTO main__student_group_Hist (student_id, group_id, reason, start_date, `order`) VALUES (1, 3, 'Причина _3', '2024-02-01', 2);
INSERT INTO main__student_group_Hist (student_id, group_id, reason, start_date, `order`) VALUES (2, 1, 'Причина _1', '2024-01-01', 1);
INSERT INTO main__student_group_Hist (student_id, group_id, reason, start_date, `order`) VALUES (2, 2, 'Причина _2', '2024-02-01', 1);
INSERT INTO main__student_group_Hist (student_id, group_id, reason, start_date, `order`) VALUES (2, 4, 'Причина _4', '2024-03-01', 1);


INSERT INTO main__topic (id, name) VALUES (1, 'Тема 1');

INSERT INTO main__categoryTag (id, name) VALUES (1, 'Категория 1');
INSERT INTO main__categoryTag (id, name) VALUES (2, 'Категория 2');
INSERT INTO main__categoryTag (id, name) VALUES (3, 'Категория 3');

INSERT INTO main__tag (categoryTag_id, name) VALUES (1, 'Тег 1');
INSERT INTO main__tag (categoryTag_id, name) VALUES (1, 'Тег 1_1');
INSERT INTO main__tag (categoryTag_id, name) VALUES (1, 'Тег 1_2');
INSERT INTO main__tag (categoryTag_id, name) VALUES (2, 'Тег 2');
INSERT INTO main__tag (categoryTag_id, name) VALUES (2, 'Тег 2_1');
INSERT INTO main__tag (categoryTag_id, name) VALUES (2, 'Тег 2_2');
INSERT INTO main__tag (categoryTag_id, name) VALUES (3, 'Тег 3');
INSERT INTO main__tag (categoryTag_id, name) VALUES (3, 'Тег 3_1');
INSERT INTO main__tag (categoryTag_id, name) VALUES (3, 'Тег 3_2');

INSERT INTO main__subject (id, name) VALUES (1, 'Математика');
INSERT INTO main__subject (id, name) VALUES (2, 'Русский язык');
INSERT INTO main__subject (id, name) VALUES (3, 'Литературное чтение');

INSERT INTO main__schoolYear (id, name, start_date, finish_date, is_current) VALUES (1, 'Учебный год 2024-2025', '2024-09-01', '2025-05-31', 'Y');
INSERT INTO main__schoolYear (id, name, start_date, finish_date, is_current) VALUES (2, 'Учебный год 2023-2024', '2023-09-01', '2024-05-31', 'N');
