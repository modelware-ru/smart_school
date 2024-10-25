<?php

namespace MW\Module\Domain\CategoryTag;

use MW\Module\Domain\GeneralManager;

class Manager extends GeneralManager
{

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

}
