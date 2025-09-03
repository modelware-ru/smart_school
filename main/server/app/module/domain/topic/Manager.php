<?php

namespace MW\Module\Domain\Topic;

use MW\Module\Domain\GeneralManager;

class Manager extends GeneralManager
{

    public function getTopicList()
    {
        $stmt = <<<SQL
SELECT mt.id, mt.name,
(SELECT COUNT(ms.id) FROM main__subtopic ms WHERE ms.topic_id = mt.id) ms_count
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

    public function getSubtopicListById($topicId)
    {
        $stmt = <<<SQL
SELECT ms.id, ms.name
FROM main__subtopic ms
WHERE ms.topic_id = :topicId
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

    public function removeSubtopicListFromTopic($removedSubtopicIdList, $topicId)
    {
        $removedSubtopicIdListString = implode(',', $removedSubtopicIdList);

        $stmt = <<<SQL
DELETE FROM main__subtopic WHERE id IN ({$removedSubtopicIdListString}) AND topic_id = :topicId
SQL;
        return $this->_db->delete($stmt, ['topicId' => $topicId]);
    }

    public function addSubtopicListToTopic($newSubtopicNameList, $topicId)
    {
        $stmt = <<<SQL
INSERT INTO main__subtopic (name, topic_id)
VALUES (:name, :topicId)
SQL;
        return $this->_db->insert(
            $stmt,
            $newSubtopicNameList,
            [
                'topicId' => $topicId,
            ],
        );
    }

    public function removeTopic($topicId)
    {
        $stmt = <<<SQL
DELETE FROM main__topic WHERE id = :id
SQL;
        return $this->_db->delete($stmt, ['id' => $topicId]);
    }
}
