<?php

namespace MW\Module\Domain\Topic;

use MW\Module\Domain\GeneralManager;

class Manager extends GeneralManager
{

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

}
