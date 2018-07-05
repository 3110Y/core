<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 15.06.2018
 * Time: 11:10
 */

namespace core\component\CallTracking\source\Extensions;


use core\component\{
    fileCache\fileCache, PDO\PDO, registry\registry
};
use core\component\CallTracking\source\{
    Action, AExtension, Phones, Substitutions
};

class Call extends AExtension
{
    /** Название действия */
    public const actionName = 'Звонок';

    /** @var string Название таблицы в БД */
    protected static $tableName = 'calltracking_call';

    /** @var array Список полей данных */
    protected static $actionFields = [
        'action_id',
        'phone',
        'duration',
        'record',
        'external_id',
        'record_store',
        'record_is_downloaded',
    ];

    /**
     * Сохраняем запись разговора
     */
    protected function downloadRecord(): void
    {
        /** @var PDO $db */
        $db     =   registry::get('db');
        $where = [
            'record_is_downloaded'  => 0,
        ];
        $record = $db->selectRow(self::$tableName, '*', $where,'RAND()');
        if (false !== $record) {
            $this->data = $record;
            if (!$this->data['id'] || $this->data['record_is_downloaded'] || !$this->data['record']) {
                return;
            }
            $file = file_get_contents($this->data['record']);
            if ($file)
            {
                /** @noinspection ReturnFalseInspection */
                $name           =   'record_' . uniqid($this->data['id'] . '_' . date('Y-m-d') . '_', true) . '.mp3';

                $dirAbsolute    =   fileCache::getDir('calltracking');
                $dir            =   fileCache::getDir('calltracking', false);

                /** @noinspection ReturnFalseInspection */
                file_put_contents("{$dirAbsolute}/{$name}", $file);

                $this->setActionData([
                    'record_is_downloaded'  => 1,
                    'record_store'          => "{$dir}/{$name}"
                ]);
                $this->update();
            }
        }
    }

    /**
     * Добавление действия пользователя
     *
     * @param Action $action
     * @return bool
     */
    public function registry(Action $action): bool
    {
        /** @var PDO $db */
        $db     =   registry::get('db');
        $this->setActionData([
            'external_id'   =>  $this->requestData->get('callID'),
        ]);
        if (!empty($this->requestData->get('callID'))) {
            $record = $db->selectRow(self::$tableName, '*', ['external_id' => $this->data['external_id']]);
            if (false !== $record) {
                $this->data = $record;
                $this->setActionData([
                    'duration'      =>  $this->requestData->get('callDuration'),
                    'record'        =>  $this->requestData->get('callRecord'),
                ]);
                return $this->update();
            }
        }
        $phones = new Phones($this->requestData);
        $phoneID = $phones->getVirtualID();
        if (null === $phoneID) {
            return false;
        }
        $substitution = Substitutions::getRecordByPhoneID($phoneID);
        if (null === $substitution) {
            return false;
        }

        $action->setActionData([
            'visitor_id'    => $substitution['visitor_id'],
        ]);
        $action->registry();
        $this->setActionData([
            'action_id'     =>  $action->getID(),
            'phone'         =>  $this->requestData->getPhone(),
            'duration'      =>  $this->requestData->get('callDuration'),
            'record'        =>  $this->requestData->get('callRecord'),
        ]);
        return (bool) $this->insert();
    }

    /**
     * Запрос на создание таблицы
     *
     * @return string
     */
    public static function getInstallQuery(): string
    {
        self::$colsInstallQueryData = '
          `phone` varchar(255) NOT NULL COMMENT \'Номер клиента\',
          `duration` int(11) NOT NULL COMMENT \'Длительность звонка\',
          `record` text NOT NULL COMMENT \'Внешняя ссылка на файл записи\',
          `record_store` text NOT NULL COMMENT \'Локальная ссылка на файл записи\',
          `external_id` varchar(50) NOT NULL COMMENT \'Внешний идентификатор\',
          `record_is_downloaded` tinyint(1) NOT NULL DEFAULT \'0\'
        ';
        return parent::getInstallQuery();
    }
}