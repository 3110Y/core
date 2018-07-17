<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 15.06.2018
 * Time: 14:38
 */

namespace core\component\CallTracking\source;


use core\component\fileCache\fileCache;
use core\component\PDO\PDO;
use core\component\registry\registry;
use DateInterval;

class Action extends AAction
{
    /** @var string Название таблицы в БД */
    protected static $tableName = 'calltracking_action';

    /** @var array Список полей данных */
    protected static $actionFields = [
        'action_key',
        'action_name',
        'visitor_id',
        'date_update'
    ];

    /**
     * @return int
     */
    public function getID(): int
    {
        return $this->data['id'] ?? 0;
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return static::$tableName;
    }

    /**
     * Добавление действия пользователя
     *
     * @return int
     */
    public function registry(): int
    {
        return $this->insert();
    }

    /**
     * Добавляем данные обновления
     *
     * @param array $data
     */
    public function setActionData(array $data): void
    {
        /** @noinspection ReturnFalseInspection */
        $data['date_update'] = date('Y-m-d H:i:s');
        parent::setActionData($data);
    }

    /**
     * @param RequestData $requestData
     * @param string $actionName
     */
    private static function log(RequestData $requestData, string $actionName): void
    {
        $name           =   'logs.txt';
        $dirAbsolute    =   fileCache::getDir('calltracking');
        $filePath = $dirAbsolute . '/' . $name;

        $data = null;
        if(file_exists($filePath)) {
            /** @noinspection ReturnFalseInspection */
            $data = file_get_contents($filePath);
        } else {
            try {
                /** @noinspection ReturnFalseInspection */
                file_put_contents($filePath,'');
                chmod($filePath, 0770);
            } catch (\Exception $exception) {}
        }
        $data .= 'Action: ' . $actionName . PHP_EOL;
        $data .= 'Date: ' . (new \DateTime())->format('Y-m-d H:i:s') . PHP_EOL;
        $data .= 'Request Data: ' . $requestData .  PHP_EOL;
        $data .= 'Post Data: ' . $requestData .  PHP_EOL;
        $data .= '---------------------------------------' . PHP_EOL;
        /** @noinspection ReturnFalseInspection */
        file_put_contents($filePath, $data);
    }

    private function extension($key): ?IExtension
    {
        $extensionObject = __NAMESPACE__.'\\Extensions\\' . $key;
        if (!class_exists($extensionObject) || !is_subclass_of($extensionObject,AExtension::class)) {
            return null;
        }
        return new $extensionObject($this->requestData);
    }

    /**
     * @return bool
     */
    public function registryAction(): bool
    {
        self::log($this->requestData, $this->data['action_key'] ?? 'Default');
        $extension = $this->extension($this->data['action_key'] ?? 'Default');
        if (null === $extension) {
            $extension = $this->extension('Default');
        }
        if (empty($this->data['action_name'])) {
            $this->data['action_name'] = \constant(\get_class($extension).'::actionName') ?? '';
        }
        return $extension->registry($this);
    }

    /**
     * @param string $actionKey
     * @param int $actionID
     * @return array|null
     */
    public function getExtensionData(string $actionKey, int $actionID): ?array
    {
        $this->data['id'] = $actionID;
        $extension = $this->extension($actionKey);
        if (null === $extension) {
            return null;
        }
        return $extension->getExtensionData($this);
    }

    /**
     * @param string $actionKey
     * @param int $actionID
     * @param array $visitData
     * @return array|null
     */
    public function getVisitData(string $actionKey, int $actionID, array $visitData): ?array
    {
        $this->data['id'] = $actionID;
        $this->data['visitor_id'] = $visitData['visitor_id'] ?? 0;
        $extension = $this->extension($actionKey);
        if (null === $extension) {
            return null;
        }
        return $extension->getVisitData($this,$visitData);
    }

    /**
     * @param string $actionKey
     * @param string $methodName
     * @return mixed
     */
    public function extensionAPI(string $actionKey, string $methodName)
    {
        $extension = $this->extension($actionKey ?? 'Default');
        if (null === $extension) {
            return false;
        }
        return $extension->api($methodName);
    }

    /**
     * Данные графика действий по типам для аналитики
     *
     * @param \DateTime $dateStart
     * @param \DateTime $dateEnd
     * @param bool $uniqueVisits
     * @return array
     * @throws \Exception
     */
    public static function analyticsDataActions(\DateTime $dateStart,\DateTime $dateEnd, bool $uniqueVisits): array
    {
        /** @var PDO $db */
        $db = registry::get('db');
        $data = $db->selectRows(self::$tableName,'*', null, null, null, 'action_key');
        foreach ($data as $key => $datum) {
            $data[$key]['name'] = $datum['action_name'];
            $data[$key]['points'] = [];
            $data[$key]['summary'] = 0;
        }
        $data = array_column($data,null,'action_key');

        if ($dateStart > $dateEnd) {
            [$dateStart,$dateEnd] = [$dateEnd,$dateStart];
        }
        $fields = '`visitor_id`,`action_key`, DATE(`date_insert`) AS `date`';
        $where = '"' . $dateStart->format('Y-m-d') . '" <= DATE(`date_update`) && DATE(`date_update`) <= "' . $dateEnd->format('Y-m-d') . '"';
        $actions = $db->selectRows(self::$tableName,$fields,$where);
        $days = [];
        $visitors = [];
        foreach ($actions as $action) {
            if (!isset($days[$action['date']])) {
                $days[$action['date']] = [];
            }
            if (!isset($days[$action['date']][$action['action_key']])) {
                $days[$action['date']][$action['action_key']] = [
                    'action_key'    => $action['action_key'],
                    'count_unique'  => 0,
                    'count'         => 0
                ];
            }
            $days[$action['date']][$action['action_key']]['count']++;
            if ($uniqueVisits && isset($visitors[$action['visitor_id']])) {
                continue;
            }
            $visitors[$action['visitor_id']] = true;
            $days[$action['date']][$action['action_key']]['count_unique']++;
        }

        $oneDay = new DateInterval('P1D');
        $curDate = clone $dateStart;
        for (;$curDate <= $dateEnd;$curDate->add($oneDay)){
            $curDateFormatted = $curDate->format('Y-m-d');
            $dayActions = $days[$curDateFormatted] ?? [];

            foreach ($data as &$datum) {
                $datum['points'][] = [
                    'x' => clone $curDate,
                    'y' => $dayActions[$datum['action_key']]['count_unique'] ?? 0,
                ];
                $datum['summary'] += $dayActions[$datum['action_key']]['count'] ?? 0;
            }
            unset($datum);
        }
        return array_values($data);
    }

    /**
     * Данные графика действий по источникам для аналитики
     *
     * @param \DateTime $dateStart
     * @param \DateTime $dateEnd
     * @param bool $uniqueVisits
     * @return array
     * @throws \Exception
     */
    public static function analyticsDataSources(\DateTime $dateStart,\DateTime $dateEnd, bool $uniqueVisits): array
    {
        /** @var PDO $db */
        $db = registry::get('db');
        $data = Source::getList();
        foreach ($data as $key => $datum) {
            $data[$key]['actions'] = [];
            $data[$key]['points'] = [];
            $data[$key]['summary'] = 0;
        }
        $data = array_column($data,null,'name');

        if ($dateStart > $dateEnd) {
            [$dateStart,$dateEnd] = [$dateEnd,$dateStart];
        }
        $fields = ' *, DATE(`date_insert`) AS `date`';
        $where = '"' . $dateStart->format('Y-m-d') . '" <= DATE(`date_update`) && DATE(`date_update`) <= "' . $dateEnd->format('Y-m-d') . '"';
        $actions = $db->selectRows(self::$tableName,$fields,$where);
        $visits = Visit::getActionsInfo($actions);
        $visits = array_column($visits, null, 'visitor_id');

        $visitors = [];
        foreach ($actions as &$action) {
            $visit = (new self(new RequestData()))->getVisitData($action['action_key'],$action['id'], $visits[$action['visitor_id']] ?? []);
            $source = !empty($visit['utm_source']) ? $visit['utm_source'] : 'Без источника';
            if (!isset($data[$source])) {
                $data[$source] = [
                    'name'      => $source,
                    'actions'   => [],
                    'points'    => [],
                    'summary'   => 0,
                ];
            }
            if (empty($data[$source]['actions'][$action['date']])) {
                $data[$source]['actions'][$action['date']] = [
                    'count'         => 0,
                    'count_unique'  => 0
                ];
            }
            $data[$source]['actions'][$action['date']]['count']++;
            if ($uniqueVisits && isset($visitors[$action['visitor_id']])) {
                continue;
            }
            $visitors[$action['visitor_id']] = true;
            $data[$source]['actions'][$action['date']]['count_unique']++;
        }
        unset($action);
        $oneDay = new DateInterval('P1D');
        $curDate = clone $dateStart;
        for (;$curDate <= $dateEnd;$curDate->add($oneDay)){
            $curDateFormatted = $curDate->format('Y-m-d');

            foreach ($data as &$datum) {
                $datum['points'][] = [
                    'x' => clone $curDate,
                    'y' => $datum['actions'][$curDateFormatted]['count_unique'] ?? 0,
                ];
                $datum['summary'] += $datum['actions'][$curDateFormatted]['count'] ?? 0;
            }
        }
        unset($datum);
        foreach ($data as $index => $datum) {
            if ($datum['summary'] === 0) {
                unset($data[$index]);
            }
        }
        return array_values($data);
    }

    /**
     * Запрос на создание таблицы
     *
     * @return array
     */
    public static function getInstallQuery(): array
    {
        $queryList = ['
            CREATE TABLE IF NOT EXISTS `' . self::$tableName . '` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `parent_id` int(11) NOT NULL DEFAULT \'0\',
              `action_key` varchar(255) NOT NULL COMMENT \'Ключ действия\',
              `action_name` varchar(255) NOT NULL COMMENT \'Название действия\',
              `visitor_id` int(11) NOT NULL DEFAULT \'0\' COMMENT \'Идентификатор посетителя\',
              `date_update` timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\',
              `date_insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;'];


        foreach (glob(__DIR__.'/Extensions/*.php') as $file) {
            /** @var IExtension $class */
            $class = __NAMESPACE__.'\\Extensions\\'.basename($file, '.php');
            if (class_exists($class) && is_subclass_of($class,AExtension::class)) {
                $queryList[] = $class::getInstallQuery();
            }
        }
        return $queryList;
    }
}