1 Общее
    1.1 (low) переключение ролей (админ/преподаватель), если позволяет аккаунт
    + 1.2 (high) показ имени пользователя на странице
    1.3 (low) восстановление пароля
    1.4 как сортировать списки???
        - (high) серия Мы будем называть серии в следующем формате: 2.Н5 КР, это вторая параллель новаторов, 5 серия выдана в классе. Мне нужно, чтобы было не как в винде, когда 2.Н10 КР оказывается раньше 2.Н2.
    1.5 будут общие серии, они будут называться просто 3.4 ДР, что означает, что во всем третьем классе 4 серия была выдана на дз.
    1.6 (high) Делить учебный год на периоды (для отчетов) Первый вариант - интервалы дат
2 Администратор
    2.1 (low) удаление элемента истории ученика
    2.2 (low) поиск ученика
    2.3 (high) отчеты???
3 Преподаватель
    3.1 (low) показать на странице расписания, какие серии есть в занятии ("не реализовано")
    3.2 (low) отметка присутствия ученика на занятии
    3.3 (high) ограничение на оценки для классных и домашних работ. Только домашняя и на параллель (Пока)
    3.4 (high) показать список учеников текущего года (для быстрого поиска)
    3.5 (high) показать список учеников данной группы текущего года (для быстрого поиска)
    3.6 (low) показать список серий, заданных конкретному ученику в текущем году
    3.7 отчеты???
        - средний балл по сериям. То есть, вбили оценки, система посчитала сумма баллов/(количество задач*макс балл)*100 и округлила по правилам до ближайшего целого.
        - средний балл за четверть, отдельно по классным, отдельно по домашним. идея такая же поделить сумму всеееех баллов за четверть на максимально возможный балл, умножить на 100, округлить.
        - Все остальные отчеты требуют сущности задач: была ли эта теория чисел или графы, хотя бы, на первом этапе отношение к крупной теме.



Активные логины - пароли:
admin - luba - админ
luba - luba - преподаватель
ivan - denis - преподаватели
victor - denis - преподаватели


- исправление ошибок (сегодня
- добавление тем к задачам

- Любовь - отчеты


Заводим два предмета: "Решение нестандартных задач" и "Подготовка к поступлению в ШГН".

Расписание такое:

РНЗ:
вторник, уроки у 2, 3, 4 классов
среда, уроки у 1 классов
пятница, уроки у 1, 2, 3 классов

ШГН:
пятница, уроки у 4 класса

Список детей в файле.

Группы в 1 классе по именам преподавателей:
1-Э Любовь
1-Э Кристина
1-С Любовь
1-С Кристина

Во 2, 3, 4 классах: исследователи, аналитики, новаторы. Группы 4 класса по РНЗ и ШГН одинаковые.



SELECT tbl2.student_id, ms.first_name, ms.last_name, ms.middle_name, tbl2.start_date, tbl2.finish_date FROM ( 
	SELECT tbl1.student_id, MIN(tbl1.start_date) start_date, MAX(tbl1.finish_date) finish_date 
	FROM (
		SELECT 
		msgh.student_id, 
		msgh.group_id,
		msgh.start_date,
		IFNULL(LEAD(msgh.start_date, 1) OVER (PARTITION BY msgh.student_id ORDER BY msgh.start_date), CURRENT_DATE()) finish_date
		FROM main__student_group_Hist msgh
		ORDER BY msgh.student_id, msgh.start_date
	) tbl1
	WHERE tbl1.group_id = 1
	GROUP BY tbl1.student_id
) tbl2
JOIN main__student ms ON ms.id = tbl2.student_id



ALTER TABLE smart_school.main__student_serie ADD group_id int(10) unsigned NOT NULL;

SET FOREIGN_KEY_CHECKS=0;
ALTER TABLE main__student_serie 
ADD CONSTRAINT main__student_serie___group_id 
FOREIGN KEY (group_id) REFERENCES main__group(id)


SELECT 
mss.id student_serie_id, 
mss.serie_id, 
mss.group_id,
mss.type serie_type, 
mss.date serie_date, 
ml.`date` lesson_date,
ms.name
FROM main__student_serie mss 
LEFT JOIN main__lesson ml ON ml.id = mss.lesson_id 
LEFT JOIN main__subject ms ON ms.id = ml.subject_id
WHERE mss.student_id = 1 AND mss.group_id = 1



ALTER TABLE smart_school.main__task_tag DROP FOREIGN KEY main__task_tag___task_id;
ALTER TABLE smart_school.main__task_tag ADD CONSTRAINT main__task_tag___task_id FOREIGN KEY (task_id) REFERENCES smart_school.main__task(id) ON DELETE CASCADE ON UPDATE RESTRICT;
