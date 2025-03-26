<?php
// GENERATED [2025-03-26 07:48:35]
namespace MW\Shared;

class MWI18nHelper
{
    const PAGE_TITLE_GUEST_INDEX = 'PAGE_TITLE_GUEST_INDEX';
    const PAGE_TITLE_RECOVERY_PASSWORD = 'PAGE_TITLE_RECOVERY_PASSWORD';
    const PAGE_TITLE_MESSAGE = 'PAGE_TITLE_MESSAGE';
    const PAGE_TITLE_ADMIN_INDEX = 'PAGE_TITLE_ADMIN_INDEX';
    const PAGE_TITLE_PARALLEL = 'PAGE_TITLE_PARALLEL';
    const PAGE_TITLE_PARALLEL_LIST = 'PAGE_TITLE_PARALLEL_LIST';
    const PAGE_TITLE_GROUP = 'PAGE_TITLE_GROUP';
    const PAGE_TITLE_GROUP_LIST = 'PAGE_TITLE_GROUP_LIST';
    const PAGE_TITLE_TEACHER = 'PAGE_TITLE_TEACHER';
    const PAGE_TITLE_TEACHER_LIST = 'PAGE_TITLE_TEACHER_LIST';
    const PAGE_TITLE_TEACHER_INDEX = 'PAGE_TITLE_TEACHER_INDEX';
    const PAGE_TITLE_SUBJECT = 'PAGE_TITLE_SUBJECT';
    const PAGE_TITLE_SUBJECT_LIST = 'PAGE_TITLE_SUBJECT_LIST';
    const PAGE_TITLE_STUDENT = 'PAGE_TITLE_STUDENT';
    const PAGE_TITLE_STUDENT_LIST = 'PAGE_TITLE_STUDENT_LIST';
    const PAGE_TITLE_STUDENT_LIST_CHANGE_CLASS = 'PAGE_TITLE_STUDENT_LIST_CHANGE_CLASS';
    const PAGE_TITLE_STUDENT_LIST_CHANGE_GROUP = 'PAGE_TITLE_STUDENT_LIST_CHANGE_GROUP';
    const PAGE_TITLE_STUDENT_CLASS_GROUP_HISTORY = 'PAGE_TITLE_STUDENT_CLASS_GROUP_HISTORY';
    const PAGE_TITLE_TOPIC_LIST = 'PAGE_TITLE_TOPIC_LIST';
    const PAGE_TITLE_TOPIC = 'PAGE_TITLE_TOPIC';
    const PAGE_TITLE_CATEGORY_TAG_LIST = 'PAGE_TITLE_CATEGORY_TAG_LIST';
    const PAGE_TITLE_CATEGORY_TAG = 'PAGE_TITLE_CATEGORY_TAG';
    const PAGE_TITLE_SCHOOL_YEAR_LIST = 'PAGE_TITLE_SCHOOL_YEAR_LIST';
    const PAGE_TITLE_SCHOOL_YEAR = 'PAGE_TITLE_SCHOOL_YEAR';
    const PAGE_TITLE_SERIE_LIST = 'PAGE_TITLE_SERIE_LIST';
    const PAGE_TITLE_SERIE = 'PAGE_TITLE_SERIE';
    const PAGE_TITLE_SCHEDULE = 'PAGE_TITLE_SCHEDULE';
    const PAGE_TITLE_LESSON = 'PAGE_TITLE_LESSON';
    const PAGE_TITLE_LESSON_JOURNAL = 'PAGE_TITLE_LESSON_JOURNAL';
    const PAGE_TITLE_TEACHER_GROUP = 'PAGE_TITLE_TEACHER_GROUP';
    const PAGE_TITLE_STUDENT_SERIE_SOLUTION = 'PAGE_TITLE_STUDENT_SERIE_SOLUTION';
    const PAGE_TITLE_STUDENT_GROUP_LIST = 'PAGE_TITLE_STUDENT_GROUP_LIST';
    const PAGE_TITLE_STUDENT_SERIE_GROUP_LIST = 'PAGE_TITLE_STUDENT_SERIE_GROUP_LIST';
    const PAGE_TITLE_TASK_LIST = 'PAGE_TITLE_TASK_LIST';
    const PAGE_TITLE_TASK = 'PAGE_TITLE_TASK';
    const PAGE_TITLE_STUDENT_SERIE_LIST = 'PAGE_TITLE_STUDENT_SERIE_LIST';

    const MSG_FIELD_EMAIL_INCORRECT = 'MSG_FIELD_EMAIL_INCORRECT';
    const MSG_FIELD_DATE_SHOULD_BE_BETWEEN = 'MSG_FIELD_DATE_SHOULD_BE_BETWEEN';
    const MSG_FIELD_IS_REQUIRED = 'MSG_FIELD_IS_REQUIRED';
    const MSG_FIELD_IS_TOO_LONG = 'MSG_FIELD_IS_TOO_LONG';
    const MSG_FIELD_IS_TOO_SHORT = 'MSG_FIELD_IS_TOO_SHORT';
    const MSG_FIELD_START_DATE_IS_GREAT_THAN_FINISH_DATE = 'MSG_FIELD_START_DATE_IS_GREAT_THAN_FINISH_DATE';
    const MSG_FIELD_VALUE_IS_NOT_VALID = 'MSG_FIELD_VALUE_IS_NOT_VALID';
    const MSG_FIELD_WITH_DUPLICATED_VALUE = 'MSG_FIELD_WITH_DUPLICATED_VALUE';
    const MSG_IMPOSSIBLE_TO_REMOVE_DATA = 'MSG_IMPOSSIBLE_TO_REMOVE_DATA';
    const MSG_WRONG_FIELD_VALUE = 'MSG_WRONG_FIELD_VALUE';
    const MSG_WRONG_LOGIN_OR_PASSWORD = 'MSG_WRONG_LOGIN_OR_PASSWORD';
    const MSG_WRONG_SERIE_TYPE = 'MSG_WRONG_SERIE_TYPE';

    const ERR_UNKNOWN = 'ERR_UNKNOWN';
    const ERR_AUTHORIZATION_NEEDED = 'ERR_AUTHORIZATION_NEEDED';
    const ERR_DB_CONNECTION_FAILED = 'ERR_DB_CONNECTION_FAILED';
    const ERR_DB_SQL_STATEMENT_FAILED = 'ERR_DB_SQL_STATEMENT_FAILED';
    const ERR_WRONG_REQUEST_PARAMETERS = 'ERR_WRONG_REQUEST_PARAMETERS';

    private static ?MWI18nHelper $_Instance = null;
    private array $_pageTemplateList = [];
    private array $_msgList = [];
    private array $_errorMsgList = [];
    private array $_errorLogMsgList = [];
    private array $_errorHttpStatusList = [];

    public static function Instance(): MWI18nHelper
    {
        if (is_null(self::$_Instance)) {
            self::$_Instance = new MWI18nHelper();
        }
        return self::$_Instance;
    }

    public static function LogMessage($code, $data): string
    {
        $message = (self::Instance())->message($code);
        if (is_null($message)) {
            return '';
        }
        
        return $message(...$data);
    }

    public function page($pageName, $langId): callable
    {
        return $this->_pageTemplateList[$pageName][$langId];
    }

    public function message($errCode): callable
    {
        return $this->_msgList[$errCode];
    }

    public function errorLogMessage($errCode): callable
    {
        return $this->_errorLogMsgList[$errCode];
    }

    public function httpStatus($errCode): int
    {
        return $this->_errorHttpStatusList[$errCode];
    }

    private function __construct()
    {
        $this->_pageTemplateList = [
            self::PAGE_TITLE_GUEST_INDEX => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Вход", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Sign In", ...$args);
                    },
                ],
            self::PAGE_TITLE_RECOVERY_PASSWORD => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Восстановление пароля", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Recovery Password", ...$args);
                    },
                ],
            self::PAGE_TITLE_MESSAGE => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Сообщение", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Message", ...$args);
                    },
                ],
            self::PAGE_TITLE_ADMIN_INDEX => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Меню", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Menu", ...$args);
                    },
                ],
            self::PAGE_TITLE_PARALLEL => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Параллель", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Parallel", ...$args);
                    },
                ],
            self::PAGE_TITLE_PARALLEL_LIST => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Список параллелей", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Parallel List", ...$args);
                    },
                ],
            self::PAGE_TITLE_GROUP => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Группа", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Group", ...$args);
                    },
                ],
            self::PAGE_TITLE_GROUP_LIST => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Список групп", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Group List", ...$args);
                    },
                ],
            self::PAGE_TITLE_TEACHER => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Преподаватель", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Teacher", ...$args);
                    },
                ],
            self::PAGE_TITLE_TEACHER_LIST => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Список преподавателей", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Teacher List", ...$args);
                    },
                ],
            self::PAGE_TITLE_TEACHER_INDEX => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Меню", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Menu", ...$args);
                    },
                ],
            self::PAGE_TITLE_SUBJECT => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Предмет", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Subject", ...$args);
                    },
                ],
            self::PAGE_TITLE_SUBJECT_LIST => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Список предметов", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Subject List", ...$args);
                    },
                ],
            self::PAGE_TITLE_STUDENT => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Ученик", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Student", ...$args);
                    },
                ],
            self::PAGE_TITLE_STUDENT_LIST => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Список учеников", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Student List", ...$args);
                    },
                ],
            self::PAGE_TITLE_STUDENT_LIST_CHANGE_CLASS => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Смена класса", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Change Class", ...$args);
                    },
                ],
            self::PAGE_TITLE_STUDENT_LIST_CHANGE_GROUP => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Смена группы", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Change Group", ...$args);
                    },
                ],
            self::PAGE_TITLE_STUDENT_CLASS_GROUP_HISTORY => [
                'ru' =>
                    function (...$args) {
                        return sprintf("История ученика", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Student History", ...$args);
                    },
                ],
            self::PAGE_TITLE_TOPIC_LIST => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Список тем задач", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Topic List", ...$args);
                    },
                ],
            self::PAGE_TITLE_TOPIC => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Тема задач", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Topic", ...$args);
                    },
                ],
            self::PAGE_TITLE_CATEGORY_TAG_LIST => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Список категорий и тегов", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Category Tag List", ...$args);
                    },
                ],
            self::PAGE_TITLE_CATEGORY_TAG => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Категория и теги", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Category Tag", ...$args);
                    },
                ],
            self::PAGE_TITLE_SCHOOL_YEAR_LIST => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Список учебных годов", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("School Year List", ...$args);
                    },
                ],
            self::PAGE_TITLE_SCHOOL_YEAR => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Учебный год", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("School Year", ...$args);
                    },
                ],
            self::PAGE_TITLE_SERIE_LIST => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Список серий годов", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Serie List", ...$args);
                    },
                ],
            self::PAGE_TITLE_SERIE => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Серия", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Serie", ...$args);
                    },
                ],
            self::PAGE_TITLE_SCHEDULE => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Расписание", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Schedule", ...$args);
                    },
                ],
            self::PAGE_TITLE_LESSON => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Занятие", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Lesson", ...$args);
                    },
                ],
            self::PAGE_TITLE_LESSON_JOURNAL => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Журнал занятия", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Lesson Journal", ...$args);
                    },
                ],
            self::PAGE_TITLE_TEACHER_GROUP => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Преподаватели в группах", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Teachers in Groups", ...$args);
                    },
                ],
            self::PAGE_TITLE_STUDENT_SERIE_SOLUTION => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Решение студента", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Student Solution", ...$args);
                    },
                ],
            self::PAGE_TITLE_STUDENT_GROUP_LIST => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Список учеников в группе", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Student Group List", ...$args);
                    },
                ],
            self::PAGE_TITLE_STUDENT_SERIE_GROUP_LIST => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Список серий ученика в группе", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Student Serie Group List", ...$args);
                    },
                ],
            self::PAGE_TITLE_TASK_LIST => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Список задач", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Task List", ...$args);
                    },
                ],
            self::PAGE_TITLE_TASK => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Задача", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Task", ...$args);
                    },
                ],
            self::PAGE_TITLE_STUDENT_SERIE_LIST => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Список серий ученика", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Student Serie List", ...$args);
                    },
                ],
        ];
        $this->_msgList = [
            self::MSG_FIELD_EMAIL_INCORRECT => function (...$args) {
                return sprintf("Некорректный адрес электронной почты: %s", ...$args);
            },
            self::MSG_FIELD_DATE_SHOULD_BE_BETWEEN => function (...$args) {
                return sprintf("Дата должна быть между (%s) и (%s)", ...$args);
            },
            self::MSG_FIELD_IS_REQUIRED => function (...$args) {
                return sprintf("Поле должно быть заполнено: %s", ...$args);
            },
            self::MSG_FIELD_IS_TOO_LONG => function (...$args) {
                return sprintf("Поле содержит слишком длинное значение: %d больше чем %d", ...$args);
            },
            self::MSG_FIELD_IS_TOO_SHORT => function (...$args) {
                return sprintf("Поле содержит слишком короткое значение: %d меньше чем %d", ...$args);
            },
            self::MSG_FIELD_START_DATE_IS_GREAT_THAN_FINISH_DATE => function (...$args) {
                return sprintf("Дата начала (%s) больше даты конца (%s)", ...$args);
            },
            self::MSG_FIELD_VALUE_IS_NOT_VALID => function (...$args) {
                return sprintf("Для поля (%s) недопустимое значение (%s)", ...$args);
            },
            self::MSG_FIELD_WITH_DUPLICATED_VALUE => function (...$args) {
                return sprintf("Запись с таким значением уже существует: '%s'", ...$args);
            },
            self::MSG_IMPOSSIBLE_TO_REMOVE_DATA => function (...$args) {
                return sprintf("Невозможно удалить данные: %s", ...$args);
            },
            self::MSG_WRONG_FIELD_VALUE => function (...$args) {
                return sprintf("Неверное значение поля: %s - %s", ...$args);
            },
            self::MSG_WRONG_LOGIN_OR_PASSWORD => function (...$args) {
                return sprintf("Попытка входа в систему. %s", ...$args);
            },
            self::MSG_WRONG_SERIE_TYPE => function (...$args) {
                return sprintf("Одна и та же серия указана как \"классная\", так и \"домашняя\"", ...$args);
            },
        ];

        $this->_errorMsgList = [
            self::ERR_UNKNOWN => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Неизвестная ошибка", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Unknown error", ...$args);
                    },
                ],
            self::ERR_AUTHORIZATION_NEEDED => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Нет прав для выполнения данной операции", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Access forbidden", ...$args);
                    },
                ],
            self::ERR_DB_CONNECTION_FAILED => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Нет доступа к базе данных", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Database connection failed", ...$args);
                    },
                ],
            self::ERR_DB_SQL_STATEMENT_FAILED => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Ошибка при выполнении запроса к базе данных", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("SQL statement error", ...$args);
                    },
                ],
            self::ERR_WRONG_REQUEST_PARAMETERS => [
                'ru' =>
                    function (...$args) {
                        return sprintf("Неверные параметры запроса", ...$args);
                    },
                'en' =>
                    function (...$args) {
                        return sprintf("Wrong request parameters", ...$args);
                    },
                ],
        ];        
        $this->_errorLogMsgList = [
            self::ERR_UNKNOWN => function (...$args) {
                return sprintf("Неизвестная ошибка: %s", ...$args);
            },
            self::ERR_AUTHORIZATION_NEEDED => function (...$args) {
                return sprintf("Нет прав для выполнения данной операции
RoleId:%d
Resource:%s
ActionId:%d
", ...$args);
            },
            self::ERR_DB_CONNECTION_FAILED => function (...$args) {
                return sprintf("Нет доступа к базе данных", ...$args);
            },
            self::ERR_DB_SQL_STATEMENT_FAILED => function (...$args) {
                return sprintf("Ошибка при выполнении запроса к базе данных
Exception: %s
SQL: %s
vars: %s
constVars: %s
", ...$args);
            },
            self::ERR_WRONG_REQUEST_PARAMETERS => function (...$args) {
                return sprintf("Неправильные типы данных в запросе: %s\n%s", ...$args);
            },
        ];
        $this->_errorHttpStatusList = [
            self::ERR_UNKNOWN => 500,
            self::ERR_AUTHORIZATION_NEEDED => 403,
            self::ERR_DB_CONNECTION_FAILED => 500,
            self::ERR_DB_SQL_STATEMENT_FAILED => 500,
            self::ERR_WRONG_REQUEST_PARAMETERS => 400,
        ];
    }
}
