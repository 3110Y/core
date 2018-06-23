<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 23.06.2018
 * Time: 12:24
 */

namespace core\component\CallTracking\source\Extensions;


use core\component\CallTracking\source\ {
    Action,
    AExtension
};

class ConferenceHall extends AExtension
{
    /** Название действия */
    public const actionName = 'Конференц-Зал';

    /** @var string Название таблицы в БД */
    protected static $tableName = 'calltracking_conference_hall';

    /** @var array Список полей данных */
    protected static $actionFields = [
        'action_id',
        'phone',
    ];

    /**
     * Запрос на создание таблицы
     *
     * @return string
     */
    public static function getInstallQuery(): string
    {
        self::$colsInstallQueryData = '
          `phone` varchar(255) NOT NULL COMMENT \'Номер клиента\'
        ';
        return parent::getInstallQuery();
    }

    /**
     * @param Action $action
     * @return bool
     */
    public function registry(Action $action): bool
    {
        $this->setActionData([
            'phone' => $this->requestData->getPhone()
        ]);
        return parent::registry($action);
    }
}