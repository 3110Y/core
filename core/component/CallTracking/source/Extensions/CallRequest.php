<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 13.06.2018
 * Time: 16:50
 */

namespace core\component\CallTracking\source\Extensions;


use core\component\CallTracking\source\ {
    Action,
    AExtension
};

class CallRequest extends AExtension
{
    /** Название действия */
    public const actionName = 'Заказ звонка';

    /** @var string Название таблицы в БД */
    protected static $tableName = 'calltracking_call_request';

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