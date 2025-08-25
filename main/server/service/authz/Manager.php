<?php

namespace MW\Service\Authz;

use MW\Shared\DBManager;

class Manager
{
    private $_db;

    function __construct($key = null, $hasTransaction = true)
    {
        $this->_db = DBManager::GetConnection($key, $hasTransaction);
    }

    public function createAccount()
    {
        $stmt = 'INSERT INTO authz__account (id) VALUES (:id)';
        return $this->_db->insert($stmt, [0 => ['id' => NULL]]);
    }

    public function createAccountRole($accountId, $roleId, $roleStateId)
    {
        $stmt = 'INSERT INTO authz__account_role (account_id, role_id, role_state_id) VALUES (:accountId, :roleId, :roleStateId)';
        return $this->_db->insert($stmt, [
            0 => [
                'accountId' => $accountId,
                'roleId' => $roleId,
                'roleStateId' => $roleStateId,
            ],
        ]);
    }

    public function getPermissionByRole($roleId, $roleStateId, $resource = NULL, $resourceType = NULL, $actionId = NULL)
    {
        $vars = [
            'roleId' => $roleId,
            'roleStateId' => $roleStateId,
        ];

        $resourceCondition = '';
        if (!is_null($resource)) {
            if (is_array($resource)) {
            } else {
                $resourceCondition = 'AND ap.resource_code_name_mask LIKE :resource ';
                $vars['resource'] = $resource;
            }
        }

        $resourceTypeCondition = '';
        if (!is_null($resourceType)) {
            $resourceTypeCondition = 'AND aa.resource_type = :resourceType ';
            $vars['resourceType'] = $resourceType;
        }

        $actionIdCondition = '';
        if (!is_null($actionId)) {
            $actionIdCondition = 'AND aa.id = :actionId ';
            $vars['actionId'] = $actionId;
        }

        $stmt = <<<SQL
SELECT 
arp.prio,
arp.permission,
ap.resource_code_name_mask resource,
aa.resource_type,
ap.action_id,
arp.`options`
FROM authz__role_permission arp
JOIN authz__role ar ON ar.id = arp.role_id
JOIN authz__permission ap ON ap.id = arp.permission_id {$resourceCondition}
JOIN authz__action aa ON aa.id = ap.action_id {$resourceTypeCondition} {$actionIdCondition}
WHERE arp.role_id = :roleId AND (arp.role_state_id = :roleStateId OR arp.role_state_id IS NULL)
ORDER BY arp.prio 
SQL;

        return $this->_db->select($stmt, $vars);
    }

    public function getPermissionByAccount($accountId, $roleId, $roleStateId, $resource = NULL, $resourceType = NULL, $actionId = NULL)
    {
        $vars = [
            'accountId' => $accountId,
            'roleStateId1' => $roleStateId,
            'roleId2' => $roleId,
            'roleStateId2' => $roleStateId,
            'roleId3' => $roleId,
            'roleStateId3' => $roleStateId,
        ];

        $resourceCondition1 = '';
        $resourceCondition2 = '';
        if (!is_null($resource)) {
            $resourceCondition1 = 'AND ap.resource_code_name_mask LIKE :resource1 ';
            $vars['resource1'] = $resource;
            $resourceCondition2 = 'AND ap.resource_code_name_mask LIKE :resource2 ';
            $vars['resource2'] = $resource;
        }

        $resourceTypeCondition1 = '';
        $resourceTypeCondition2 = '';
        if (!is_null($resourceType)) {
            $resourceTypeCondition1 = 'AND aa.resource_type = :resourceType1 ';
            $vars['resourceType1'] = $resourceType;
            $resourceTypeCondition2 = 'AND aa.resource_type = :resourceType2 ';
            $vars['resourceType2'] = $resourceType;
        }

        $actionIdCondition1 = '';
        $actionIdCondition2 = '';
        if (!is_null($actionId)) {
            $actionIdCondition1 = 'AND aa.id = :actionId1 ';
            $vars['actionId1'] = $actionId;
            $actionIdCondition2 = 'AND aa.id = :actionId2 ';
            $vars['actionId2'] = $actionId;
        }

        $stmt = <<<SQL
SELECT arp.prio, arp.permission, ap.resource_code_name_mask resource, aa.resource_type, ap.action_id, arp.`options` 
FROM authz__role_permission arp 
JOIN authz__role ar ON ar.id = arp.role_id 
JOIN authz__account_role aar ON aar.role_id = ar.id AND aar.role_state_id = :roleStateId1
JOIN authz__permission ap ON ap.id = arp.permission_id {$resourceCondition1}
JOIN authz__action aa ON aa.id = ap.action_id {$resourceTypeCondition1} {$actionIdCondition1}
WHERE arp.role_id = :roleId2 AND (arp.role_state_id = :roleStateId2 OR arp.role_state_id IS NULL)
UNION
SELECT agp.prio, agp.permission, ap.resource_code_name_mask resource, aa.resource_type, ap.action_id, ag.`options` 
FROM authz__group_permission agp 
JOIN authz__group ag ON ag.id = agp.group_id AND (ag.role_id = :roleId3 OR ag.role_id IS NULL) AND (ag.role_state_id = :roleStateId3 OR ag.role_state_id IS NULL)
JOIN authz__account_group aag ON aag.group_id = ag.id AND aag.account_id = :accountId
JOIN authz__permission ap ON ap.id = agp.permission_id {$resourceCondition2}
JOIN authz__action aa ON aa.id = ap.action_id {$resourceTypeCondition2} {$actionIdCondition2}
ORDER BY prio;
SQL;

        return $this->_db->select($stmt, $vars);
    }

    public function getRoleListByAccount($accountId)
    {
        $stmt = <<<SQL
SELECT role_id, role_state_id FROM authz__account_role WHERE account_id = :accountId ORDER BY `order`
SQL;

        return $this->_db->select($stmt, ['accountId' => $accountId]);
    }

    public function removeAccountRole($accountId)
    {
        $stmt = 'DELETE FROM authz__account_role WHERE account_id = :id';
        return $this->_db->delete($stmt, ['id' => $accountId]);
    }

    public function removeAccountGroup($accountId)
    {
        $stmt = 'DELETE FROM authz__account_group WHERE account_id = :id';
        return $this->_db->delete($stmt, ['id' => $accountId]);
    }

    public function removeAccount($accountId)
    {
        $stmt = 'DELETE FROM authz__account WHERE id = :id';
        return $this->_db->delete($stmt, ['id' => $accountId]);
    }

}
