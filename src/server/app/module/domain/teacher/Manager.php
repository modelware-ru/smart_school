<?php

namespace MW\Module\Domain\Teacher;

use MW\Service\Authz\Constant as AuthzConstant;
use MW\Module\Domain\GeneralManager;

class Manager extends GeneralManager
{

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

    public function getGroupListForTeacher($teacherId, $schoolYearId)
    {
        $stmt = <<<SQL
SELECT mg.id group_id, mg.name group_name, mp.name parallel_name
FROM main__group mg
JOIN main__user_group mug ON mug.group_id = mg.id AND mug.user_id = :teacherId AND mug.schoolYear_id = :schoolYearId
JOIN main__parallel mp ON mp.id = mg.parallel_id
ORDER BY mp.number, mg.name
SQL;
        return $this->_db->select($stmt, [
            'teacherId' => $teacherId,
            'schoolYearId' => $schoolYearId,
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


    public function getTeacherGroupBySchoolYearId($schoolYearId)
    {

        $stmt = <<<SQL
SELECT mp.id parallel_id, mp.name parallel_name, mg.id group_id, mg.name group_name, mu.id user_id, mu.first_name, mu.last_name, mu.middle_name 
FROM main__group mg
JOIN main__parallel mp ON mp.id = mg.parallel_id 
LEFT JOIN main__user_group mug ON mug.group_id = mg.id AND mug.schoolYear_id = :schoolYearId
LEFT JOIN main__user mu ON mu.id = mug.user_id
ORDER BY mp.`order`, mg.`order` 
SQL;
        return $this->_db->select($stmt, [
            "schoolYearId" => $schoolYearId,
        ]);
    }

    public function removeTeacherGroup($groupId, $schoolYearId)
    {
        $stmt = <<<SQL
DELETE FROM main__user_group WHERE group_id = :groupId AND schoolYear_id = :schoolYearId
SQL;
        return $this->_db->delete($stmt, ['groupId' => $groupId, 'schoolYearId' => $schoolYearId]);
    }

    public function createTeacherGroup($groupId, $schoolYearId, $teacherList)
    {
        $tl = array_map(function ($item) {
            return [
                'userId' => $item,
            ];
        }, $teacherList);

        $stmt = <<<SQL
INSERT INTO main__user_group (user_id, group_id, schoolYear_id)
VALUES (:userId, :groupId, :schoolYearId)
SQL;
        return $this->_db->insert(
            $stmt,
            $tl,
            [
                'groupId' => $groupId,
                'schoolYearId' => $schoolYearId,
            ],
            true
        );
    }
}
