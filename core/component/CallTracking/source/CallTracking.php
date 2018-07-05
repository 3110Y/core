<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 13.06.2018
 * Time: 15:51
 */

namespace core\component\CallTracking\source;


use core\component\PDO\PDO;
use core\component\registry\registry;

abstract class CallTracking implements ICallTracking
{
    /** @var string Название поля сессии в куках */
    protected static $sessionKey = 'callTracking_session_id';

    /** @var int Время жизни сессии */
    protected static $sessionLifetime = 60 * 60 * 12 * 2 * 30;

    /** @var RequestData Данные запроса */
    protected $requestData;

    /** @var Visitor Посетитель */
    protected $visitor;

    /** @var Visit Посещение */
    protected $visit;

    /** @var Phones Виртуальные телефоны */
    protected $phone;

    /** @var Substitutions Подмены телефонов */
    protected $substitutions;

    public function __construct(RequestData $requestData)
    {
        if ($requestData->isSearchBot() || !$requestData->isHtmlRequired()) {
            return;
        }
        $this->requestData = $requestData;
        $this->visitor = new Visitor($this->requestData);
        $this->visit = new Visit($this->requestData, $this->visitor);
        $this->substitutions = new Substitutions($this->requestData, $this->visitor);

    }

    /**
     * Получаем замену реального номера на виртуальный
     *
     * @param string $realNumber
     * @return string
     */
    public function getSubstitution(string $realNumber): string
    {
        if (!$this->substitutions) {
            return $realNumber;
        }
        return $this->substitutions->get($realNumber);
    }

    /**
     * Получаем замену реального номера на виртуальный
     *
     * @param string $realNumber
     * @return bool
     */
    public function hasSubstitution(string $realNumber = ''): bool
    {
        if (!$this->substitutions) {
            return $realNumber;
        }
        return $this->substitutions->has($realNumber);
    }

    /**
     * Регистрируем действие пользователя
     * $actionKey - название действия,
     * $data - дополнительные значения
     *
     * @param RequestData $requestData
     * @param string $actionKey
     * @return bool
     */
    public static function registryAction(RequestData $requestData, string $actionKey): bool
    {
        $action = new Action($requestData);
        $action->setActionData([
            'action_key'    => $actionKey,
        ]);
        return $action->registryAction();
    }

    /**
     * @param RequestData $requestData
     * @param string $actionKey - название действия,
     * @param string $methodName - название метода,
     * @return mixed
     */
    public static function actionAPI(RequestData $requestData, string $actionKey, string $methodName)
    {
        $action = new Action($requestData);
        return $action->extensionAPI($actionKey, $methodName);
    }

    /**
     * Установка
     *
     * @return bool
     */
    public static function install(): bool
    {
        /** @var PDO $db */
        $db = registry::get('db');
        $queryList = Action::getInstallQuery();
        $queryList[] = Phones::getInstallQuery();
        $queryList[] = Source::getInstallQuery();
        $queryList[] = Substitutions::getInstallQuery();
        $queryList[] = Visit::getInstallQuery();
        $queryList[] = Visitor::getInstallQuery();
        $queryList = array_filter($queryList);
        $result = true;
        foreach ($queryList as $query) {
            $result &= (bool) $db->query($query);
        }
        if (0 !== (int) $db->getConnect()->errorCode()) {
            /** @noinspection ForgottenDebugOutputInspection */
            print_r($db->getConnect()->errorInfo());
        }
        return $result;
    }
}