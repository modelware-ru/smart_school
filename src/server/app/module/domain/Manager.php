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

    public function updateParallel($id, $name, $number, $showInGroup)
    {
        $stmt = <<<SQL
UPDATE main__parallel SET name = :name, number = :number, show_in_group = :showInGroup 
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $id,
                'name' => $name,
                'number' => $number,
                'showInGroup' => $showInGroup,
            ]
        ]);
    }

    public function removeParallel($id)
    {
        $stmt = <<<SQL
DELETE FROM main__parallel WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $id]);
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

    public function updateGroup($id, $name, $parallelId)
    {
        $stmt = <<<SQL
UPDATE main__group SET name = :name, parallel_id = :parallelId
WHERE id = :id
SQL;
        return $this->_db->update($stmt, [
            0 => [
                'id' => $id,
                'name' => $name,
                'parallelId' => $parallelId,
            ]
        ]);
    }

    public function removeGroup($id)
    {
        $stmt = <<<SQL
DELETE FROM main__group WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $id]);
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


    public function blockTeacher($id, $roleStateId)
    {
        $teacherRole = AuthzConstant::ROLE_TEACHER_ID;
        $stmt = <<<SQL
SELECT aar.account_id FROM authz__account_role aar 
WHERE aar.account_id = (SELECT mu.account_id FROM main__user mu WHERE mu.id = {$id})
AND aar.role_id  = {$teacherRole} INTO @accountId;

UPDATE authz__account_role SET role_state_id = {$roleStateId} WHERE account_id = @accountId AND role_id  = {$teacherRole};
SQL;
        return $this->_db->exec($stmt);
    }

    public function getTeacherById($teacherId)
    {
        $teacherRole = AuthzConstant::ROLE_TEACHER_ID;
        $stmt = <<<SQL
SELECT mu.id teacher_id, mu.first_name, mu.last_name, mu.middle_name, mu.login, mu.email, aar.role_state_id
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

    public function updateTeacher($id, $firstName, $lastName, $middleName, $login, $password, $email)
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
            'id' => $id,
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
}
