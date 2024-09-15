<?php

namespace MW\Module\Domain;

use MW\Shared\DBManager;
use MW\Service\Authz\Constant as AuthzConstant;

class Manager
{
    private $_db;

    function __construct($key = null, $hasTransaction = true)
    {
        $this->_db = DBManager::GetConnection($key, $hasTransaction);
    }

    public function getParallelList()
    {
        $stmt = <<<SQL
SELECT mp.id, mp.name, mp.number, mp.show_in_group,
(SELECT COUNT(mg.id) FROM main__group mg WHERE mg.parallel_id = mp.id) mg_count,
(SELECT COUNT(msch.id) FROM main__student_class_Hist msch WHERE msch.parallel_id = mp.id) msch_count
FROM main__parallel mp
SQL;
        return $this->_db->select($stmt);
    }

    public function getParallelById($parallelId)
    {
        $stmt = <<<SQL
SELECT mp.id, mp.name, mp.number, mp.show_in_group
FROM main__parallel mp
WHERE mp.id = :parallelId 
SQL;
        return $this->_db->select($stmt, ['parallelId' => $parallelId]);
    }

    public function createParallel($name, $number, $showInGroup)
    {
        $stmt = <<<SQL
INSERT INTO main__parallel (name, number, show_in_group)
VALUES (:name, :number, :showInGroup)
SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'name' => $name,
                'number' => $number,
                'showInGroup' => $showInGroup,
            ],
        ]);
    }

    public function updateParallel($parallelId, $name, $number, $showInGroup)
    {
        $stmt = <<<SQL
UPDATE main__parallel SET name = :name, number = :number, show_in_group = :showInGroup 
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $parallelId,
                'name' => $name,
                'number' => $number,
                'showInGroup' => $showInGroup,
            ]
        ]);
    }

    public function removeParallel($parallelId)
    {
        $stmt = <<<SQL
DELETE FROM main__parallel WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $parallelId]);
    }

    public function getGroupList()
    {
        $stmt = <<<SQL
SELECT mg.id group_id, mg.name group_name, mp.name parallel_name,
(SELECT COUNT(mug.id) FROM main__user_group mug WHERE mug.group_id = mg.id) mug_count,
(SELECT COUNT(ml.id) FROM main__lesson ml WHERE ml.group_id = mg.id) ml_count,
(SELECT COUNT(msgh.id) FROM main__student_group_Hist msgh WHERE msgh.group_id = mg.id) msgh_count
FROM main__group mg
JOIN main__parallel mp ON mp.id = mg.parallel_id
SQL;
        return $this->_db->select($stmt);
    }

    public function getGroupById($groupId)
    {
        $stmt = <<<SQL
SELECT mg.id group_id, mg.name group_name, mg.parallel_id
FROM main__group mg
WHERE mg.id = :groupId 
SQL;
        return $this->_db->select($stmt, ['groupId' => $groupId]);
    }

    public function getGroupListByParallelId($parallelId)
    {
        $stmt = <<<SQL
SELECT mg.id group_id, mg.name group_name
FROM main__group mg
WHERE mg.parallel_id = :parallelId
SQL;
        return $this->_db->select($stmt, ['parallelId' => $parallelId]);
    }

    public function createGroup($name, $parallelId)
    {
        $stmt = <<<SQL
INSERT INTO main__group (name, parallel_id)
VALUES (:name, :parallelId)
SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'name' => $name,
                'parallelId' => $parallelId,
            ],
        ]);
    }

    public function updateGroup($groupId, $name, $parallelId)
    {
        $stmt = <<<SQL
UPDATE main__group SET name = :name, parallel_id = :parallelId
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $groupId,
                'name' => $name,
                'parallelId' => $parallelId,
            ]
        ]);
    }

    public function removeGroup($groupId)
    {
        $stmt = <<<SQL
DELETE FROM main__group WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $groupId]);
    }

    public function getActiveTeacherList()
    {
        $stmt = <<<SQL
SELECT mu.id, mu.first_name, mu.last_name, mu.middle_name
FROM main__user mu
JOIN authz__account_role aar ON aar.account_id = mu.account_id AND aar.role_id = :roleId AND aar.role_state_id = :roleStateId
ORDER BY mu.last_name, mu.first_name, mu.middle_name
SQL;
        return $this->_db->select($stmt, ['roleId' => AuthzConstant::ROLE_TEACHER_ID, 'roleStateId' => AuthzConstant::ROLE_STATE_TEACHER_ACTIVE_ID]);
    }

    public function getTeacherList()
    {
        $stmt = <<<SQL
SELECT mu.id teacher_id, mu.first_name, mu.last_name, mu.middle_name, aar.role_state_id,
(SELECT COUNT(mug.id) FROM main__user_group mug WHERE mug.user_id = mu.id) mug_count
FROM main__user mu
JOIN authz__account_role aar ON aar.account_id = mu.account_id AND aar.role_id = :roleId
ORDER BY mu.last_name, mu.first_name, mu.middle_name
SQL;
        return $this->_db->select($stmt, ['roleId' => AuthzConstant::ROLE_TEACHER_ID]);
    }

    public function getTeacherListInGroup($groupId)
    {
        $stmt = <<<SQL
SELECT mu.id teacher_id, mu.first_name, mu.last_name, mu.middle_name
FROM main__user mu
JOIN authz__account_role aar ON aar.account_id = mu.account_id AND aar.role_id = :roleId AND aar.role_state_id = :roleStateId
JOIN main__user_group mug ON mug.user_id = mu.id AND mug.group_id = :groupId
ORDER BY mu.last_name, mu.first_name, mu.middle_name
SQL;
        return $this->_db->select($stmt, [
            'roleId' => AuthzConstant::ROLE_TEACHER_ID,
            'roleStateId' => AuthzConstant::ROLE_STATE_TEACHER_ACTIVE_ID,
            'groupId' => $groupId
        ]);
    }

    public function removeTeacherListFromGroup($groupId)
    {
        $stmt = <<<SQL
DELETE FROM main__user_group WHERE group_id = :groupId
SQL;
        return $this->_db->delete($stmt, ['groupId' => $groupId]);
    }

    public function addTeacherListToGroup($groupId, $teacherList)
    {
        $stmt = <<<SQL
INSERT INTO main__user_group (group_id, user_id) VALUES (:groupId, :userId);
SQL;
        return $this->_db->insert($stmt, $teacherList, ['groupId' => $groupId]);
    }


    public function blockTeacher($teacherId, $roleStateId)
    {
        $teacherRole = AuthzConstant::ROLE_TEACHER_ID;
        $stmt = <<<SQL
SELECT aar.account_id FROM authz__account_role aar 
WHERE aar.account_id = (SELECT mu.account_id FROM main__user mu WHERE mu.id = {$teacherId})
AND aar.role_id  = {$teacherRole} INTO @accountId;

UPDATE authz__account_role SET role_state_id = {$roleStateId} WHERE account_id = @accountId AND role_id  = {$teacherRole};
SQL;
        return $this->_db->exec($stmt);
    }

    public function getTeacherById($teacherId)
    {
        $teacherRole = AuthzConstant::ROLE_TEACHER_ID;
        $stmt = <<<SQL
SELECT mu.id teacher_id, mu.first_name, mu.last_name, mu.middle_name, mu.login, mu.email, aar.role_state_id, mu.account_id
FROM main__user mu
JOIN authz__account_role aar ON aar.account_id = mu.account_id AND aar.role_id = {$teacherRole}
WHERE mu.id = :teacherId
ORDER BY mu.last_name, mu.first_name, mu.middle_name
SQL;
        return $this->_db->select($stmt, ['teacherId' => $teacherId]);
    }

    public function getGroupListForTeacher($teacherId)
    {
        $stmt = <<<SQL
SELECT mg.id group_id, mg.name group_name, mp.name parallel_name
FROM main__group mg
JOIN main__user_group mug ON mug.group_id = mg.id AND mug.user_id = :teacherId
JOIN main__parallel mp ON mp.id = mg.parallel_id
SQL;
        return $this->_db->select($stmt, [
            'teacherId' => $teacherId
        ]);
    }

    public function createTeacher($accountId, $firstName, $lastName, $middleName, $login, $password, $email)
    {
        $stmt = <<<SQL
INSERT INTO main__user (account_id, first_name, last_name, middle_name, login, password, email)
VALUES (:accountId, :firstName, :lastName, :middleName, :login, :password, :email)
SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'accountId' => $accountId,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'middleName' => $middleName,
                'login' => $login,
                'password' => $password,
                'email' => $email,
            ],
        ]);
    }

    public function updateTeacher($teacherId, $firstName, $lastName, $middleName, $login, $password, $email)
    {
        $passwordLine = '';
        if (!empty($password)) {
            $passwordLine = "password = :password,";
        }
        $stmt = <<<SQL
UPDATE main__user SET 
first_name = :firstName,
last_name = :lastName,
middle_name = :middleName,
login = :login,
{$passwordLine}
email = :email
WHERE id = :id
SQL;
        $vars = [
            'id' => $teacherId,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'middleName' => $middleName,
            'login' => $login,
            'email' => $email,
        ];
        if (!empty($password)) {
            $vars['password'] = $password;
        }

        return $this->_db->update($stmt, [
            0 => $vars
        ]);
    }

    public function removeGroupListFromTeacher($teacherId)
    {
        $stmt = <<<SQL
DELETE FROM main__user_group WHERE user_id = :teacherId
SQL;
        return $this->_db->delete($stmt, ['teacherId' => $teacherId]);
    }

    public function addGroupListToTeacher($teacherId, $groupList)
    {
        $stmt = <<<SQL
INSERT INTO main__user_group (group_id, user_id) VALUES (:groupId, :userId);
SQL;
        return $this->_db->insert($stmt, $groupList, ['userId' => $teacherId]);
    }

    public function removeTeacher($teacherId)
    {
        $stmt = <<<SQL
DELETE FROM main__user WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $teacherId]);
    }

    public function getSubjectList()
    {
        $stmt = <<<SQL
SELECT ms.id, ms.name,
(SELECT COUNT(ml.id) FROM main__lesson ml WHERE ml.subject_id = ms.id) ml_count
FROM main__subject ms
SQL;
        return $this->_db->select($stmt);
    }

    public function getSubjectById($subjectId)
    {
        $stmt = <<<SQL
SELECT ms.id, ms.name
FROM main__subject ms
WHERE ms.id = :subjectId 
SQL;
        return $this->_db->select($stmt, ['subjectId' => $subjectId]);
    }

    public function createSubject($name)
    {
        $stmt = <<<SQL
INSERT INTO main__subject (name)
VALUES (:name)
SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'name' => $name,
            ],
        ]);
    }

    public function updateSubject($subjectId, $name)
    {
        $stmt = <<<SQL
UPDATE main__subject SET name = :name
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $subjectId,
                'name' => $name,
            ]
        ]);
    }

    public function removeSubject($subjectId)
    {
        $stmt = <<<SQL
DELETE FROM main__subject WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $subjectId]);
    }

    public function getStudentList()
    {
        $stmt = <<<SQL
SELECT ms.id student_id, ms.first_name, ms.last_name, ms.middle_name, t1.class_number, t1.class_letter, t1.class_parallel_id, t2.group_name, t2.group_parallel_id, t2.group_parallel_number,
(SELECT COUNT(msch.id) FROM main__student_class_Hist msch WHERE msch.student_id = ms.id) msch_count,
(SELECT COUNT(msgh.id) FROM main__student_group_Hist msgh WHERE msgh.student_id = ms.id) msgh_count,
(SELECT COUNT(msl.id) FROM main__student_lesson msl WHERE msl.student_id = ms.id) msl_count,
(SELECT COUNT(mss.id) FROM main__student_serie mss WHERE mss.student_id = ms.id) mss_count,
(SELECT COUNT(msst.id) FROM main__student_serieTask msst WHERE msst.student_id = ms.id) msst_count
FROM main__student ms

LEFT JOIN (
    SELECT ch.student_id, mp.`number` class_number, msch.letter class_letter, msch.parallel_id class_parallel_id
    FROM (
	    SELECT id, student_id, start_date, `order`,	MAX(start_date) OVER(PARTITION BY student_id) max_start_date, MAX(`order`) OVER(PARTITION BY student_id, start_date) max_order
	    FROM main__student_class_Hist) ch
    JOIN main__student_class_Hist msch ON msch.id = ch.id
    JOIN main__parallel mp ON msch.parallel_id = mp.id 
    WHERE ch.start_date = ch.max_start_date AND ch.`order` = ch.max_order
) t1 ON t1.student_id = ms.id

LEFT JOIN (SELECT gh.student_id, mg.name group_name, mg.parallel_id group_parallel_id, mp.number group_parallel_number
FROM (
	SELECT id, student_id, start_date, `order`,	MAX(start_date) OVER(PARTITION BY student_id) max_start_date, MAX(`order`) OVER(PARTITION BY student_id, start_date) max_order
	FROM main__student_group_Hist) 
gh
JOIN main__student_group_Hist msgh ON msgh.id = gh.id
JOIN main__group mg ON msgh.group_id = mg.id 
JOIN main__parallel mp ON mg.parallel_id = mp.id 
WHERE gh.start_date = gh.max_start_date AND gh.`order` = gh.max_order) t2 
ON t2.student_id = ms.id
ORDER BY ms.last_name, ms.first_name, ms.middle_name
SQL;
        return $this->_db->select($stmt);
    }

    public function getStudentById($studentId)
    {
        $stmt = <<<SQL
SELECT ms.id student_id, ms.first_name, ms.last_name, ms.middle_name
FROM main__student ms
WHERE ms.id = :studentId 
SQL;
        return $this->_db->select($stmt, ['studentId' => $studentId]);
    }

    public function getStudentByIdList($studentIdList)
    {
        $studentIdListString = implode(',', $studentIdList);
        $stmt = <<<SQL
SELECT ms.id student_id, ms.first_name, ms.last_name, ms.middle_name, t1.class_number, t1.class_letter, t1.class_parallel_id
FROM main__student ms
LEFT JOIN (
    SELECT ch.student_id, mp.`number` class_number, msch.letter class_letter, msch.parallel_id class_parallel_id
    FROM (
	    SELECT id, student_id, start_date, `order`,	MAX(start_date) OVER(PARTITION BY student_id) max_start_date, MAX(`order`) OVER(PARTITION BY student_id, start_date) max_order
	    FROM main__student_class_Hist) ch
    JOIN main__student_class_Hist msch ON msch.id = ch.id
    JOIN main__parallel mp ON msch.parallel_id = mp.id 
    WHERE ch.start_date = ch.max_start_date AND ch.`order` = ch.max_order
) t1 ON t1.student_id = ms.id
WHERE ms.id IN ({$studentIdListString})
ORDER BY ms.last_name, ms.first_name, ms.middle_name
SQL;
        return $this->_db->select($stmt);
    }

    public function createStudent($firstName, $lastName, $middleName)
    {
        $stmt = <<<SQL
INSERT INTO main__student (first_name, last_name, middle_name)
VALUES (:firstName, :lastName, :middleName)
SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'middleName' => $middleName,
            ],
        ]);
    }

    public function updateStudent($studentId, $firstName, $lastName, $middleName)
    {
        $stmt = <<<SQL
UPDATE main__student SET 
first_name = :firstName,
last_name = :lastName,
middle_name = :middleName
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $studentId,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'middleName' => $middleName,
            ]
        ]);
    }

    public function removeStudent($studentId)
    {
        $stmt = <<<SQL
DELETE FROM main__student WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $studentId]);
    }

    public function getMaxOrderForStudentClassHistory($studentIdList, $startDate)
    {
        $sl = array_reduce($studentIdList, function ($carry, $item) {
            $carry[] = $item['id'];
            return $carry;
        }, []);

        $studentIdListString = implode(',', $sl);

        $stmt = <<<SQL
SELECT msch.student_id, MAX(`order`) max_order FROM main__student_class_Hist msch 
WHERE msch.start_date = :startDate
AND msch.student_id IN ({$studentIdListString})
GROUP BY msch.student_id
SQL;
        return $this->_db->select($stmt, ['startDate' => $startDate]);
    }

    public function addStudentClassHistory($studentIdList, $startDate, $parallelId, $classLetter, $reason)
    {
        $stmt = <<<SQL
INSERT INTO main__student_class_Hist (student_id, start_date, parallel_id, letter, reason, `order`)
VALUES (:id, :startDate, :parallelId, :classLetter, :reason, :order)
SQL;
        return $this->_db->insert(
            $stmt,
            $studentIdList,
            [
                'startDate' => $startDate,
                'parallelId' => $parallelId,
                'classLetter' => $classLetter,
                'reason' => $reason,
            ],
        );
    }

    public function getMaxOrderForStudentGroupHistory($studentIdList, $startDate)
    {
        $sl = array_reduce($studentIdList, function ($carry, $item) {
            $carry[] = $item['id'];
            return $carry;
        }, []);

        $studentIdListString = implode(',', $sl);

        $stmt = <<<SQL
SELECT msgh.student_id, MAX(`order`) max_order FROM main__student_group_Hist msgh 
WHERE msgh.start_date = :startDate
AND msgh.student_id IN ({$studentIdListString})
GROUP BY msgh.student_id
SQL;
        return $this->_db->select($stmt, ['startDate' => $startDate]);
    }

    public function addStudentGroupHistory($studentIdList, $startDate, $groupId, $reason)
    {
        $stmt = <<<SQL
INSERT INTO main__student_group_Hist (student_id, start_date, group_id, reason, `order`)
VALUES (:id, :startDate, :groupId, :reason, :order)
SQL;
        return $this->_db->insert(
            $stmt,
            $studentIdList,
            [
                'startDate' => $startDate,
                'groupId' => $groupId,
                'reason' => $reason,
            ],
        );
    }

    public function getStudentClassGroupHistory($studentId)
    {
        $stmt = <<<SQL
SELECT msch.id class_history_id, 0 group_history_id, msch.start_date, msch.`order`, CONCAT(mp.number, msch.letter) class_name, '' group_name, reason FROM main__student_class_Hist msch 
JOIN main__parallel mp ON mp.id = msch.parallel_id 
WHERE msch.student_id = :studentId1
UNION
SELECT 0 class_history_id, msgh.id group_history_id, msgh.start_date, msgh.`order`, '' class_name, CONCAT('[', mp.`number`, '] ', mg.name) group_name, reason FROM main__student_group_Hist msgh 
JOIN main__group mg ON mg.id = msgh.group_id 
JOIN main__parallel mp ON mp.id = mg.parallel_id 
WHERE msgh.student_id = :studentId2
ORDER BY start_date
SQL;
        return $this->_db->select($stmt, ['studentId1' => $studentId, 'studentId2' => $studentId]);
    }

    public function getTopicList()
    {
        $stmt = <<<SQL
SELECT mt.id, mt.name,
(SELECT COUNT(mts.id) FROM main__task mts WHERE mts.topic_id = mt.id) mts_count
FROM main__topic mt
SQL;
        return $this->_db->select($stmt);
    }

    public function getTopicById($topicId)
    {
        $stmt = <<<SQL
SELECT mt.id, mt.name
FROM main__topic mt
WHERE mt.id = :topicId 
SQL;
        return $this->_db->select($stmt, ['topicId' => $topicId]);
    }

    public function createTopic($name)
    {
        $stmt = <<<SQL
INSERT INTO main__topic (name)
VALUES (:name)
SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'name' => $name,
            ],
        ]);
    }

    public function updateTopic($topicId, $name)
    {
        $stmt = <<<SQL
UPDATE main__topic SET name = :name
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $topicId,
                'name' => $name,
            ]
        ]);
    }

    public function removeTopic($topicId)
    {
        $stmt = <<<SQL
DELETE FROM main__topic WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $topicId]);
    }

    public function getCategoryTagList()
    {
        $stmt = <<<SQL
SELECT mct.id, mct.name,
(SELECT COUNT(mt.id) FROM main__tag mt WHERE mt.categoryTag_id = mct.id) mt_count
FROM main__categoryTag mct
SQL;
        return $this->_db->select($stmt);
    }

    public function getCategoryById($categoryTagId)
    {
        // (SELECT JSON_ARRAYAGG(JSON_OBJECT('id', mt.id, 'name', mt.name)) FROM main__tag mt WHERE mt.categoryTag_id = mct.id) tag_list 

        $stmt = <<<SQL
SELECT mct.id, mct.name
FROM main__categoryTag mct
WHERE mct.id = :categoryTagId
SQL;
        return $this->_db->select($stmt, ['categoryTagId' => $categoryTagId]);
    }

    public function getCategoryTagListById($categoryTagId)
    {

        $stmt = <<<SQL
SELECT mt.id, mt.name
FROM main__tag mt
WHERE mt.categoryTag_id = :categoryTagId
SQL;
        return $this->_db->select($stmt, ['categoryTagId' => $categoryTagId]);
    }

    public function createCategoryTag($name)
    {
        $stmt = <<<SQL
INSERT INTO main__categoryTag (name)
VALUES (:name)
SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'name' => $name,
            ],
        ]);
    }

    public function updateCategoryTag($categoryTagId, $name)
    {
        $stmt = <<<SQL
UPDATE main__categoryTag SET name = :name
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $categoryTagId,
                'name' => $name,
            ]
        ]);
    }

    public function removeCategoryTag($categoryTagId)
    {
        $stmt = <<<SQL
DELETE FROM main__categoryTag WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $categoryTagId]);
    }

    public function removeTagListFromCategoryTag($removedTagIdList, $categoryTagId)
    {
        $removedTagIdListString = implode(',', $removedTagIdList);

        $stmt = <<<SQL
DELETE FROM main__tag WHERE id IN ({$removedTagIdListString}) AND categoryTag_id = :categoryTagId
SQL;
        return $this->_db->delete($stmt, ['categoryTagId' => $categoryTagId]);
    }

    public function addTagListToCategoryTag($newTagList, $categoryTagId)
    {
        $tl = array_map(function ($item) {
            return [
                'name' => $item,
            ];
        }, $newTagList);

        $stmt = <<<SQL
INSERT INTO main__tag (name, categoryTag_id)
VALUES (:name, :categoryTagId)
SQL;
        return $this->_db->insert(
            $stmt,
            $tl,
            [
                'categoryTagId' => $categoryTagId
            ],
        );
    }

    public function getSchoolYearList()
    {
        $stmt = <<<SQL
SELECT msy.id, msy.name, msy.start_date, msy.finish_date, msy.is_current
FROM main__schoolYear msy
ORDER BY msy.start_date DESC
SQL;
        return $this->_db->select($stmt);
    }


    public function getCurrentSchoolYearAndCount()
    {
        $stmt = <<<SQL
SELECT 
(SELECT msy.id FROM main__schoolYear msy WHERE is_current = 'Y') current_id,
(SELECT COUNT(msy.id) FROM main__schoolYear msy) `count`;
SQL;
        return $this->_db->select($stmt);
    }

    public function getSchoolYearById($schoolYeadId)
    {
        $stmt = <<<SQL
SELECT msy.id, msy.name, msy.start_date, msy.finish_date, msy.is_current
FROM main__schoolYear msy
WHERE msy.id = :schoolYeadId 
SQL;
        return $this->_db->select($stmt, ['schoolYeadId' => $schoolYeadId]);
    }

    public function createSchoolYear($name, $startDate, $finishDate, $isCurrent)
    {
        $stmt = <<<SQL
INSERT INTO main__schoolYear (name, start_date, finish_date, is_current)
VALUES (:name, :startDate, :finishDate, :isCurrent)
SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'name' => $name,
                'startDate' => $startDate,
                'finishDate' => $finishDate,
                'isCurrent' => $isCurrent,
            ],
        ]);
    }

    public function updateSchoolYear($schoolYearId, $name, $startDate, $finishDate, $isCurrent)
    {
        $stmt = <<<SQL
UPDATE main__schoolYear SET name = :name, start_date = :startDate, finish_date = :finishDate, is_current = :isCurrent
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $schoolYearId,
                'name' => $name,
                'startDate' => $startDate,
                'finishDate' => $finishDate,
                'isCurrent' => $isCurrent,
            ]
        ]);
    }

    public function updateSchoolYearIsCurrent($schoolYearId, $isCurrent)
    {
        $stmt = <<<SQL
UPDATE main__schoolYear SET is_current = :isCurrent
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $schoolYearId,
                'isCurrent' => $isCurrent,
            ]
        ]);
    }


    public function removeSchoolYear($schoolYearId)
    {
        $stmt = <<<SQL
DELETE FROM main__schoolYear WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $schoolYearId]);
    }

    public function getSerieList()
    {
        $stmt = <<<SQL
SELECT ms.id, ms.name,
(SELECT COUNT(mst.id) FROM main__serie_task mst WHERE mst.serie_id = ms.id) mst_count,
(SELECT COUNT(mss.id) FROM main__student_serie mss WHERE mss.serie_id = ms.id) mss_count,
(SELECT COUNT(mls.id) FROM main__lesson_serie mls WHERE mls.serie_id = ms.id) mls_count
FROM main__serie ms
SQL;
        return $this->_db->select($stmt);
    }

    public function getSerieById($serieId)
    {
        $stmt = <<<SQL
SELECT ms.id, ms.name
FROM main__serie ms
WHERE ms.id = :serieId 
SQL;
        return $this->_db->select($stmt, ['serieId' => $serieId]);
    }

    public function getSerieTaskListById($serieId)
    {

        $stmt = <<<SQL
SELECT mt.id, mt.name
FROM main__serie_task mst
JOIN main__task mt ON mt.id = mst.task_id
WHERE mst.serie_id = :serieId
SQL;
        return $this->_db->select($stmt, ['serieId' => $serieId]);
    }

    public function createSerie($name)
    {
        $stmt = <<<SQL
INSERT INTO main__serie (name)
VALUES (:name)
SQL;
        return $this->_db->insert($stmt, [
            0 => [
                'name' => $name,
            ],
        ]);
    }

    public function updateSerie($serieId, $name)
    {
        $stmt = <<<SQL
UPDATE main__serie SET name = :name
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $serieId,
                'name' => $name,
            ]
        ]);
    }

    public function removeTaskListFromSerie($removedTaskIdList, $serieId)
    {
        $removedTaskIdListString = implode(',', $removedTaskIdList);

        $stmt = <<<SQL
DELETE FROM main__serie_task WHERE task_id IN ({$removedTaskIdListString}) AND serie_id = :serieId
SQL;
        return $this->_db->delete($stmt, ['serieId' => $serieId]);
    }


    public function createTaskList($newTaskList)
    {
        $tl = array_map(function ($item) {
            return [
                'name' => $item,
            ];
        }, $newTaskList);

        // TODO: topic
        $stmt = <<<SQL
INSERT INTO main__task (name, topic_id)
VALUES (:name, :topicId)
SQL;
        return $this->_db->insert(
            $stmt,
            $tl,
            [
                'topicId' => 1,
            ],
            true
        );
    }

    public function addTaskListToSerie($newTaskIdList, $serieId)
    {
        $stmt = <<<SQL
INSERT INTO main__serie_task (serie_id, task_id)
VALUES (:serieId, :taskId)
SQL;
        return $this->_db->insert(
            $stmt,
            $newTaskIdList,
            [
                'serieId' => $serieId,
            ],
        );
    }

    public function removeSerie($serieId)
    {
        $stmt = <<<SQL
DELETE FROM main__serie WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $serieId]);
    }
}
