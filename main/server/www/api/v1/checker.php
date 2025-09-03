<?php

use MW\Shared\Util;
use MW\Service\Authz\Constant as AuthzConstant;

function check_type_parameters($apiResource, $payload)
{
    static $checkList = [
        // signIn START
        AuthzConstant::RESOURCE_API_SIGN_IN => [
            '_type' => 'object',
            'login' => 'string',
            'password' => 'string',
        ],
        // signIn FINISH
        // saveParallel START
        AuthzConstant::RESOURCE_API_SAVE_PARALLEL => [
            '_type' => 'object',
            'id' => 1,
            'name' => 'string',
            'number' => 'string',
            'showInGroup' => true,
            'order' => 1,
        ],
        // saveParallel FINISH
        // removeParallel START
        AuthzConstant::RESOURCE_API_REMOVE_PARALLEL => [
            '_type' => 'object',
            'id' => 1,
        ],
        // removeParallel FINISH
        // saveGroup START
        AuthzConstant::RESOURCE_API_SAVE_GROUP => [
            '_type' => 'object',
            'id' => 1,
            'name' => 'string',
            'parallelId' => 1,
            'order' => 1,
        ],
        // saveGroup FINISH
        // removeGroup START
        AuthzConstant::RESOURCE_API_REMOVE_GROUP => [
            '_type' => 'object',
            'id' => 1,
        ],
        // removeGroup FINISH
        // blockTeacher START
        AuthzConstant::RESOURCE_API_BLOCK_TEACHER => [
            '_type' => 'object',
            'id' => 1,
            'action' => 'string',
        ],
        // blockTeacher FINISH
        // saveTeacher START
        AuthzConstant::RESOURCE_API_SAVE_TEACHER => [
            '_type' => 'object',
            'id' => 1,
            'login' => 'string',
            'email' => 'string',
            'password' => 'string',
            'firstName' => 'string',
            'lastName' => 'string',
            'middleName' => 'string',
            'roleStateId' => 1,
        ],
        // saveTeacher FINISH
        // removeTeacher START
        AuthzConstant::RESOURCE_API_REMOVE_TEACHER => [
            '_type' => 'object',
            'id' => 1,
        ],
        // removeTeacher FINISH
        // saveSubject START
        AuthzConstant::RESOURCE_API_SAVE_SUBJECT => [
            '_type' => 'object',
            'id' => 1,
            'name' => 'string',
        ],
        // saveSubject FINISH
        // removeSubject START
        AuthzConstant::RESOURCE_API_REMOVE_SUBJECT => [
            '_type' => 'object',
            'id' => 1,
        ],
        // removeSubject FINISH
        // saveStudent START
        AuthzConstant::RESOURCE_API_SAVE_STUDENT => [
            '_type' => 'object',
            'id' => 1,
            'firstName' => 'string',
            'lastName' => 'string',
            'middleName' => 'string',
        ],
        // saveStudent FINISH
        // removeStudent START
        AuthzConstant::RESOURCE_API_REMOVE_STUDENT => [
            '_type' => 'object',
            'id' => 1,
        ],
        // removeStudent FINISH
        // changeClass START
        AuthzConstant::RESOURCE_API_CHANGE_CLASS => [
            '_type' => 'object',
            'startDate' => 'string',
            'classLetter' => 'string',
            'parallelId' => 1,
            'reason' => 'string',
            'studentIdList' => [
                '_type' => 'array',
                '_keyType' => 1,
                '_itemTemplate' => [
                    '_type' => 'object',
                    'id' => 1,
                ],
            ]
        ],
        // changeClass FINISH  
        // changeGroup START
        AuthzConstant::RESOURCE_API_CHANGE_GROUP => [
            '_type' => 'object',
            'startDate' => 'string',
            'groupId' => 1,
            'reason' => 'string',
            'studentIdList' => [
                '_type' => 'array',
                '_keyType' => 1,
                '_itemTemplate' => [
                    '_type' => 'object',
                    'id' => 1,
                ],
            ]
        ],
        // changeGroup FINISH  
        // saveTopic START
        AuthzConstant::RESOURCE_API_SAVE_TOPIC => [
            '_type' => 'object',
            'id' => 1,
            'name' => 'string',
            'newSubtopicList' => [
                '_type' => 'array',
                '_keyType' => 1,
                '_itemTemplate' => 'string',
            ],
            'removedSubtopicIdList' => [
                '_type' => 'array',
                '_keyType' => 1,
                '_itemTemplate' => 1,
            ],
        ],
        // saveTopic FINISH
        // removeTopic START
        AuthzConstant::RESOURCE_API_REMOVE_TOPIC => [
            '_type' => 'object',
            'id' => 1,
        ],
        // removeTopic FINISH
        // saveCategoryTag START
        AuthzConstant::RESOURCE_API_SAVE_CATEGORY_TAG => [
            '_type' => 'object',
            'id' => 1,
            'name' => 'string',
            'newTagList' => [
                '_type' => 'array',
                '_keyType' => 1,
                '_itemTemplate' => 'string',
            ],
            'removedTagIdList' => [
                '_type' => 'array',
                '_keyType' => 1,
                '_itemTemplate' => 1,
            ],
        ],
        // saveCategoryTag FINISH
        // removeCategoryTag START
        AuthzConstant::RESOURCE_API_REMOVE_CATEGORY_TAG => [
            '_type' => 'object',
            'id' => 1,
        ],
        // removeCategoryTag FINISH
        // saveSchoolYear START
        AuthzConstant::RESOURCE_API_SAVE_SCHOOL_YEAR => [
            '_type' => 'object',
            'id' => 1,
            'name' => 'string',
            'startDate' => 'string',
            'finishDate' => 'string',
            'isCurrent' => true,
        ],
        // saveSchoolYear FINISH
        // removeSchoolYear START
        AuthzConstant::RESOURCE_API_REMOVE_SCHOOL_YEAR => [
            '_type' => 'object',
            'id' => 1,
        ],
        // removeSchoolYear FINISH
        // saveSerie START
        AuthzConstant::RESOURCE_API_SAVE_SERIE => [
            '_type' => 'object',
            'id' => 1,
            'name' => 'string',
            'newTaskList' => [
                '_type' => 'array',
                '_keyType' => 1,
                '_itemTemplate' => 'string',
            ],
            'removedTaskIdList' => [
                '_type' => 'array',
                '_keyType' => 1,
                '_itemTemplate' => 1,
            ],
        ],
        // saveSerie FINISH
        // removeSerie START
        AuthzConstant::RESOURCE_API_REMOVE_SERIE => [
            '_type' => 'object',
            'id' => 1,
        ],
        // removeSerie FINISH
        // saveLesson START
        AuthzConstant::RESOURCE_API_SAVE_LESSON => [
            '_type' => 'object',
            'id' => 1,
            'date' => 'string',
            'subjectId' => 1,
            'groupId' => 1,
            'serieList' => [
                '_type' => 'array',
                '_keyType' => 1,
                '_itemTemplate' => 1,
            ]
        ],
        // saveLesson FINISH
        // removeLesson START
        AuthzConstant::RESOURCE_API_REMOVE_LESSON => [
            '_type' => 'object',
            'id' => 1,
        ],
        // removeLesson FINISH
        // saveTeacherGroup START
        AuthzConstant::RESOURCE_API_SAVE_TEACHER_GROUP => [
            '_type' => 'object',
            'groupId' => 1,
            'schoolYearId' => 1,
            'teacherList' => [
                '_type' => 'array',
                '_keyType' => 1,
                '_itemTemplate' => 'string',
            ],
        ],
        // saveTeacherGroup FINISH
        // removeTeacherGroup START
        AuthzConstant::RESOURCE_API_REMOVE_TEACHER_GROUP => [
            '_type' => 'object',
            'id' => 1,
        ],
        // removeTeacherGroup FINISH
        // addSerieToLesson START
        AuthzConstant::RESOURCE_API_ADD_SERIE_TO_LESSON => [
            '_type' => 'object',
            'lessonId' => 1,
            'serieId' => 1,
            'groupId' => 1,
            'studentClassList' => [
                '_type' => 'array',
                '_keyType' => 1,
                '_itemTemplate' => 1,
            ],
            'studentHomeList' => [
                '_type' => 'array',
                '_keyType' => 1,
                '_itemTemplate' => 1,
            ],
        ],
        // addSerieToLesson FINISH
        // removeSerieFromLesson START
        AuthzConstant::RESOURCE_API_REMOVE_SERIE_FROM_LESSON => [
            '_type' => 'object',
            'groupId' => 1,
            'lessonId' => 1,
            'serieId' => 1,
            'studentClassList' => [
                '_type' => 'array',
                '_keyType' => 1,
                '_itemTemplate' => 1,
            ],
            'studentHomeList' => [
                '_type' => 'array',
                '_keyType' => 1,
                '_itemTemplate' => 1,
            ],
        ],
        // removeSerieFromLesson FINISH
        // saveStudentSolution START
        AuthzConstant::RESOURCE_API_SAVE_STUDENT_SOLUTION => [
            '_type' => 'object',
            'studentSerieId' => 1,
            'taskList' => [
                '_type' => 'array',
                '_keyType' => 1,
                '_itemTemplate' => [
                    '_type' => 'object',
                    'value' => 'string',
                    'solutionId' => 1,
                    'serieTaskId' => 1,
                ],
            ],
        ],
        // saveStudentSolution FINISH
        // saveTask START
        AuthzConstant::RESOURCE_API_SAVE_TASK => [
            '_type' => 'object',
            'id' => 1,
            'name' => 'string',
            'subtopicList' => [
                '_type' => 'array',
                '_keyType' => 1,
                '_itemTemplate' => 1,
            ],
        ],
        // saveTask FINISH
        // removeTask START
        AuthzConstant::RESOURCE_API_REMOVE_TASK => [
            '_type' => 'object',
            'id' => 1,
        ],
        // removeTask FINISH
        // addHomeSerieToStudent START
        AuthzConstant::RESOURCE_API_ADD_HOME_SERIE_TO_STUDENT => [
            '_type' => 'object',
            'serieId' => 1,
            'studentId' => 1,
            'groupId' => 1,
            'date' => 'string',
        ],
        // addHomeSerieToStudent FINISH
        // removeHomeSerieFromStudent START
        AuthzConstant::RESOURCE_API_REMOVE_HOME_SERIE_FROM_STUDENT => [
            '_type' => 'object',
            'id' => 1,
        ],
        // removeHomeSerieFromStudent FINISH
    ];

    if (!array_key_exists($apiResource, $checkList)) {
        return false;
    }

    return Util::CompareStructures($payload, $checkList[$apiResource]);
}
