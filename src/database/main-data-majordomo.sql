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
INSERT INTO main__api (code_name) VALUES ('saveLesson');
INSERT INTO main__api (code_name) VALUES ('removeLesson');
INSERT INTO main__api (code_name) VALUES ('saveTeacherGroup');
INSERT INTO main__api (code_name) VALUES ('removeTeacherGroup');
INSERT INTO main__api (code_name) VALUES ('addSerieToLesson');
INSERT INTO main__api (code_name) VALUES ('removeSerieFromLesson');
INSERT INTO main__api (code_name) VALUES ('saveStudentSolution');
INSERT INTO main__api (code_name) VALUES ('saveTask');
INSERT INTO main__api (code_name) VALUES ('removeTask');

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
INSERT INTO main__page (code_name, name) VALUES ('schedule', '{"title":{"ru": "Расписание", "en": "Schedule"}}');
INSERT INTO main__page (code_name, name) VALUES ('lesson', '{"title":{"ru": "Занятие", "en": "Lesson"}}');
INSERT INTO main__page (code_name, name) VALUES ('lessonJournal', '{"title":{"ru": "Журнал занятия", "en": "Lesson Journal"}}');
INSERT INTO main__page (code_name, name) VALUES ('teacherGroup', '{"title":{"ru": "Преподаватели в группах", "en": "Teachers in Groups"}}');
INSERT INTO main__page (code_name, name) VALUES ('studentSerieSolution', '{"title":{"ru": "Решение студента", "en": "Student Solution"}}');
INSERT INTO main__page (code_name, name) VALUES ('studentGroupList', '{"title":{"ru": "Список учеников в группе", "en": "Student Group List"}}');
INSERT INTO main__page (code_name, name) VALUES ('studentSerieGroupList', '{"title":{"ru": "Список серий ученика в группе", "en": "Student Serie Group List"}}');
INSERT INTO main__page (code_name, name) VALUES ('taskList', '{"title":{"ru": "Список задач", "en": "Task List"}}');
INSERT INTO main__page (code_name, name) VALUES ('task', '{"title":{"ru": "Задача", "en": "Task"}}');

INSERT INTO main__parallel ( id, name, number, show_in_group, `order`) VALUES (1, 'Первая', '1', 'Y', 1);
INSERT INTO main__parallel ( id, name, number, show_in_group, `order`) VALUES (2, 'Вторая', '2', 'Y', 2);
INSERT INTO main__parallel ( id, name, number, show_in_group, `order`) VALUES (3, 'Третья', '3', 'Y', 3);
INSERT INTO main__parallel ( id, name, number, show_in_group, `order`) VALUES (4, 'Четвертая', '4', 'Y', 4);
INSERT INTO main__parallel ( id, name, number, show_in_group, `order`) VALUES (5, 'Пятая', '5', 'N', 5);
    
INSERT INTO main__user (id, first_name, last_name, middle_name, login, password, email, account_id) VALUES (1, 'Любовь', 'Корешкова', 'Сергеевна', 'admin', 'xdCgiX8fHlWm.', "luba1@mail.ru", 1);
INSERT INTO main__user (id, first_name, last_name, middle_name, login, password, email, account_id) VALUES (2, 'Любовь', 'Корешкова', 'Сергеевна', 'luba', 'xdCgiX8fHlWm.', "luba2@mail.ru", 2);
INSERT INTO main__user (id, first_name, last_name, middle_name, login, password, email, account_id) VALUES (3, 'Александра', 'Кинтас', 'Антоновна', 'alexandra', 'xd50zdGGOc2o6', "mail1@mail.ru", 3);
INSERT INTO main__user (id, first_name, last_name, middle_name, login, password, email, account_id) VALUES (4, 'Кристина', 'Подрез', 'Анатольевна', 'kristina', 'xd50zdGGOc2o6', "mail2@mail.ru", 4);

INSERT INTO main__group (id, parallel_id, name, `order`) VALUES (1, 1, '1-Э Любовь', 1);
INSERT INTO main__group (id, parallel_id, name, `order`) VALUES (2, 1, '1-Э Кристина', 2);
INSERT INTO main__group (id, parallel_id, name, `order`) VALUES (3, 1, '1-С Любовь', 3);
INSERT INTO main__group (id, parallel_id, name, `order`) VALUES (4, 1, '1-С Кристина', 4);
INSERT INTO main__group (id, parallel_id, name, `order`) VALUES (5, 2, 'Исследователи', 1);
INSERT INTO main__group (id, parallel_id, name, `order`) VALUES (6, 2, 'Аналитики', 2);
INSERT INTO main__group (id, parallel_id, name, `order`) VALUES (7, 2, 'Новаторы', 3);
INSERT INTO main__group (id, parallel_id, name, `order`) VALUES (8, 3, 'Исследователи', 1);
INSERT INTO main__group (id, parallel_id, name, `order`) VALUES (9, 3, 'Аналитики', 2);
INSERT INTO main__group (id, parallel_id, name, `order`) VALUES (10, 3, 'Новаторы', 3);
INSERT INTO main__group (id, parallel_id, name, `order`) VALUES (11, 4, 'Исследователи', 1);
INSERT INTO main__group (id, parallel_id, name, `order`) VALUES (12, 4, 'Аналитики', 2);
INSERT INTO main__group (id, parallel_id, name, `order`) VALUES (13, 4, 'Новаторы', 3);

INSERT INTO main__schoolYear (id, name, start_date, finish_date, is_current) VALUES (1, 'Учебный год 2024-2025', '2024-09-01', '2025-05-31', 'Y');

INSERT INTO main__user_group (user_id, group_id, schoolYear_id) VALUES (2, 1, 1);
INSERT INTO main__user_group (user_id, group_id, schoolYear_id) VALUES (2, 2, 1);
INSERT INTO main__user_group (user_id, group_id, schoolYear_id) VALUES (2, 3, 1);
INSERT INTO main__user_group (user_id, group_id, schoolYear_id) VALUES (2, 4, 1);
INSERT INTO main__user_group (user_id, group_id, schoolYear_id) VALUES (2, 5, 1);
INSERT INTO main__user_group (user_id, group_id, schoolYear_id) VALUES (2, 6, 1);
INSERT INTO main__user_group (user_id, group_id, schoolYear_id) VALUES (2, 7, 1);
INSERT INTO main__user_group (user_id, group_id, schoolYear_id) VALUES (2, 8, 1);
INSERT INTO main__user_group (user_id, group_id, schoolYear_id) VALUES (2, 9, 1);
INSERT INTO main__user_group (user_id, group_id, schoolYear_id) VALUES (2, 10, 1);
INSERT INTO main__user_group (user_id, group_id, schoolYear_id) VALUES (2, 11, 1);
INSERT INTO main__user_group (user_id, group_id, schoolYear_id) VALUES (2, 12, 1);
INSERT INTO main__user_group (user_id, group_id, schoolYear_id) VALUES (2, 13, 1);

INSERT INTO main__user_group (user_id, group_id, schoolYear_id) VALUES (4, 2, 1);
INSERT INTO main__user_group (user_id, group_id, schoolYear_id) VALUES (4, 4, 1);


-- 1-Э класс 
INSERT INTO main__student (id, last_name, first_name) VALUES (1, 'Евдокимов', 'Борис');
INSERT INTO main__student (id, last_name, first_name) VALUES (2, 'Панин', 'Андрей');
INSERT INTO main__student (id, last_name, first_name) VALUES (3, 'Крылова', 'Марта');
INSERT INTO main__student (id, last_name, first_name) VALUES (4, 'Ильченко', 'Дарья');
INSERT INTO main__student (id, last_name, first_name) VALUES (5, 'Ошмарина', 'Аделина');
INSERT INTO main__student (id, last_name, first_name) VALUES (6, 'Сурис', 'Мия');
INSERT INTO main__student (id, last_name, first_name) VALUES (7, 'Ефремов-Шереметьев', 'Лука');
INSERT INTO main__student (id, last_name, first_name) VALUES (8, 'Осташев', 'Михаил');
INSERT INTO main__student (id, last_name, first_name) VALUES (9, 'Мартышова', 'Милана');
INSERT INTO main__student (id, last_name, first_name) VALUES (10, 'Швайка', 'Дарина');
INSERT INTO main__student (id, last_name, first_name) VALUES (11, 'Ячковский', 'Алексей');

INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 1, 'Э', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 2, 'Э', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 3, 'Э', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 4, 'Э', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 5, 'Э', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 6, 'Э', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 7, 'Э', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 8, 'Э', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 9, 'Э', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 10, 'Э', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 11, 'Э', 'Начальное заполнение', '2024-09-01', 1);

-- 1-С класс
INSERT INTO main__student (id, last_name, first_name) VALUES (12, 'Иванова', 'Ольга');
INSERT INTO main__student (id, last_name, first_name) VALUES (13, 'Краснов', 'Лев');
INSERT INTO main__student (id, last_name, first_name) VALUES (14, 'Егоров', 'Василий');
INSERT INTO main__student (id, last_name, first_name) VALUES (15, 'Рыбальченко', 'Вова');
INSERT INTO main__student (id, last_name, first_name) VALUES (16, 'Ляджин', 'Артем');
INSERT INTO main__student (id, last_name, first_name) VALUES (17, 'Вилкова', 'Елизавета');
INSERT INTO main__student (id, last_name, first_name) VALUES (18, 'Климов', 'Николай');
INSERT INTO main__student (id, last_name, first_name) VALUES (19, 'Петрова', 'Вероника');
INSERT INTO main__student (id, last_name, first_name) VALUES (20, 'Брудная', 'Сара'); 
INSERT INTO main__student (id, last_name, first_name) VALUES (21, 'Мамаева', 'Каролина');

INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 12, 'С', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 13, 'С', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 14, 'С', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 15, 'С', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 16, 'С', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 17, 'С', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 18, 'С', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 19, 'С', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 20, 'С', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (1, 21, 'С', 'Начальное заполнение', '2024-09-01', 1);

-- 2-А класс
INSERT INTO main__student (id, first_name, last_name) VALUES (22, 'Беата', 'Сурис');
INSERT INTO main__student (id, first_name, last_name) VALUES (23, 'София', 'Страмоусова');
INSERT INTO main__student (id, first_name, last_name) VALUES (24, 'Таисия', 'Фадеева');
INSERT INTO main__student (id, first_name, last_name) VALUES (25, 'Платон', 'Кулебякин');
INSERT INTO main__student (id, first_name, last_name) VALUES (26, 'Агафья', 'Перегудова');
INSERT INTO main__student (id, first_name, last_name) VALUES (27, 'Артем', 'Мойсиевич');
INSERT INTO main__student (id, first_name, last_name) VALUES (28, 'Зоя', 'Пелин');
INSERT INTO main__student (id, first_name, last_name) VALUES (29, 'София', 'Бойцова');
INSERT INTO main__student (id, first_name, last_name) VALUES (30, 'Агата', 'Пантелеева');

INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 22, 'А', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 23, 'А', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 24, 'А', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 25, 'А', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 26, 'А', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 27, 'А', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 28, 'А', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 29, 'А', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 30, 'А', 'Начальное заполнение', '2024-09-01', 1);

-- 2-К класс
INSERT INTO main__student (id, first_name, last_name) VALUES (31, 'Алексей', 'Мелешко');
INSERT INTO main__student (id, first_name, last_name) VALUES (32, 'Тимофей', 'Васильев');
INSERT INTO main__student (id, first_name, last_name) VALUES (33, 'Александра', 'Худницкая');
INSERT INTO main__student (id, first_name, last_name) VALUES (34, 'Милана', 'Андриянец');
INSERT INTO main__student (id, first_name, last_name) VALUES (35, 'Владимир', 'Мартыновский');
INSERT INTO main__student (id, first_name, last_name) VALUES (36, 'София', 'Кутейникова');
INSERT INTO main__student (id, first_name, last_name) VALUES (37, 'Ксения', 'Орлова');

INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 31, 'К', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 32, 'К', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 33, 'К', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 34, 'К', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 35, 'К', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 36, 'К', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (2, 37, 'К', 'Начальное заполнение', '2024-09-01', 1);

-- 3 класс
INSERT INTO main__student (id, last_name, first_name) VALUES (38, 'Алексеев', 'Даниил');
INSERT INTO main__student (id, last_name, first_name) VALUES (39, 'Ильченко', 'Филипп');
INSERT INTO main__student (id, last_name, first_name) VALUES (40, 'Верпаховский', 'Иннокентий'); 
INSERT INTO main__student (id, last_name, first_name) VALUES (41, 'Максимова', 'Мария');
INSERT INTO main__student (id, last_name, first_name) VALUES (42, 'Максимчук', 'Давид');
INSERT INTO main__student (id, last_name, first_name) VALUES (43, 'Концелидзе', 'Лука');
INSERT INTO main__student (id, last_name, first_name) VALUES (44, 'Волков', 'Ярослав');
INSERT INTO main__student (id, last_name, first_name) VALUES (45, 'Мелешко', 'Елизавета');
INSERT INTO main__student (id, last_name, first_name) VALUES (46, 'Швайка', 'Егор');
INSERT INTO main__student (id, last_name, first_name) VALUES (47, 'Климов', 'Матвей');
INSERT INTO main__student (id, last_name, first_name) VALUES (48, 'Ривлин', 'Мартин');
INSERT INTO main__student (id, last_name, first_name) VALUES (49, 'Газарх', 'Даниил');
INSERT INTO main__student (id, last_name, first_name) VALUES (50, 'Кутейникова', 'Ольга');

INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (3, 38, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (3, 39, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (3, 40, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (3, 41, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (3, 42, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (3, 43, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (3, 44, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (3, 45, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (3, 46, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (3, 47, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (3, 48, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (3, 49, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (3, 50, '_', 'Начальное заполнение', '2024-09-01', 1);

-- 3’ класс
INSERT INTO main__student (id, first_name, last_name) VALUES (51, 'Василиса', 'Гудченко');
INSERT INTO main__student (id, first_name, last_name) VALUES (52, 'Владимир', 'Силкин');
INSERT INTO main__student (id, first_name, last_name) VALUES (53, 'Ульяна', 'Ячковская');

INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (3, 51, '"', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (3, 52, '"', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (3, 53, '"', 'Начальное заполнение', '2024-09-01', 1);

-- 4 класс
INSERT INTO main__student (id, first_name, last_name) VALUES (54, 'Яна', 'Хейфец');
INSERT INTO main__student (id, first_name, last_name) VALUES (55, 'Светлана', 'Седых');
INSERT INTO main__student (id, first_name, last_name) VALUES (56, 'Милана', 'Порошина');
INSERT INTO main__student (id, first_name, last_name) VALUES (57, 'Ева', 'Павлова');
INSERT INTO main__student (id, first_name, last_name) VALUES (58, 'Екатерина', 'Петрова');
INSERT INTO main__student (id, first_name, last_name) VALUES (59, 'Амир', 'Алиев');
INSERT INTO main__student (id, first_name, last_name) VALUES (60, 'Виктор', 'Смирнов');
INSERT INTO main__student (id, first_name, last_name) VALUES (61, 'Михаил', 'Никулин');
INSERT INTO main__student (id, first_name, last_name) VALUES (62, 'Андрей', 'Алексеев');
INSERT INTO main__student (id, first_name, last_name) VALUES (63, 'Алексей', 'Некрестьянов');
INSERT INTO main__student (id, first_name, last_name) VALUES (64, 'Вячеслав', 'Пилехин');
INSERT INTO main__student (id, first_name, last_name) VALUES (65, 'Михаил', 'Следевский');

INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (4, 54, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (4, 55, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (4, 56, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (4, 57, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (4, 58, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (4, 59, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (4, 60, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (4, 61, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (4, 62, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (4, 63, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (4, 64, '_', 'Начальное заполнение', '2024-09-01', 1);
INSERT INTO main__student_class_Hist (parallel_id, student_id, letter, reason, start_date, `order`) VALUES (4, 65, '_', 'Начальное заполнение', '2024-09-01', 1);

INSERT INTO main__topic (id, name) VALUES (1, 'Тема 1');

-- INSERT INTO main__categoryTag (id, name) VALUES (1, 'Категория 1');
-- INSERT INTO main__categoryTag (id, name) VALUES (2, 'Категория 2');
-- INSERT INTO main__categoryTag (id, name) VALUES (3, 'Категория 3');

-- INSERT INTO main__tag (categoryTag_id, name) VALUES (1, 'Тег 1');
-- INSERT INTO main__tag (categoryTag_id, name) VALUES (1, 'Тег 1_1');
-- INSERT INTO main__tag (categoryTag_id, name) VALUES (1, 'Тег 1_2');
-- INSERT INTO main__tag (categoryTag_id, name) VALUES (2, 'Тег 2');
-- INSERT INTO main__tag (categoryTag_id, name) VALUES (2, 'Тег 2_1');
-- INSERT INTO main__tag (categoryTag_id, name) VALUES (2, 'Тег 2_2');
-- INSERT INTO main__tag (categoryTag_id, name) VALUES (3, 'Тег 3');
-- INSERT INTO main__tag (categoryTag_id, name) VALUES (3, 'Тег 3_1');
-- INSERT INTO main__tag (categoryTag_id, name) VALUES (3, 'Тег 3_2');

INSERT INTO main__subject (id, name) VALUES (1, 'Решение нестандартных задач');
INSERT INTO main__subject (id, name) VALUES (2, 'Подготовка к поступлению в ШГН');

-- INSERT INTO main__serie (id, name) VALUES (1, 'Первая');
-- INSERT INTO main__serie (id, name) VALUES (2, 'Вторая');
-- INSERT INTO main__serie (id, name) VALUES (3, 'Третья');
-- INSERT INTO main__serie (id, name) VALUES (4, 'Четвертая');

INSERT INTO main__attendance_Dict (id, name, display, `default`) VALUES (1, 'Отсутствует', '-', 'Y');
INSERT INTO main__attendance_Dict (id, name, display, `default`) VALUES (2, 'Присутствует', '+', 'N');
INSERT INTO main__attendance_Dict (id, name, display, `default`) VALUES (3, 'Болел', 'Б', 'N');