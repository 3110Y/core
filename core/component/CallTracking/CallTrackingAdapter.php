<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 13.06.2018
 * Time: 15:52
 */

namespace core\component\CallTracking;


use core\component\CallTracking\source\ {
    CallTracking, RequestData, Visit, Phones, Action
};
use core\core;
use core\component\{
    CForm\IButton, registry\registry, resources\resources, application\AControllers, templateEngine\engine\simpleView
};
use DateTime;
use DomainException;

class CallTrackingAdapter extends CallTracking
{
    public function __construct()
    {
        $requestData  = new RequestData();
        $requestData->setUTM($_GET ?? []);
        $requestData->setURI($_SERVER['REQUEST_URI'] ?? '');
        $requestData->setReferer($_SERVER['HTTP_REFERER'] ?? '');
        $requestData->setUserAgent($_SERVER['HTTP_USER_AGENT'] ?? '');
        $requestData->setAccept($_SERVER['HTTP_ACCEPT'] ?? '');
        $requestData->setSessionID($_COOKIE[self::$sessionKey] ?? '');
        setcookie(self::$sessionKey, $requestData->getSessionID(), time() + self::$sessionLifetime, '/');
        parent::__construct($requestData);
    }

    /**
     * Подменяет дефолтные номера телефонов на номера из пула
     *
     * @param array|string $data
     * @return mixed
     */
    public function replace($data)
    {
        if (!$this->substitutions) {
            return $data;
        }
        if (\is_array($data)) {
            foreach ($data as $key  =>  $value) {
                $data[$key] = $this->replace($value);
            }
        } else {
            $phones = $this->substitutions->getNumbers();
            $data = strtr($data, array_column($phones,'virtual_phone_number','real_phone_number'));
            $data = strtr($data, array_column($phones,'virtual_phone_text','real_phone_text'));

        }
        return $data;
    }

    /**
     * Регистрируем действие пользователя
     * $actionKey - название действия,
     * $data - дополнительные значения
     *
     * @param string $actionKey
     * @param array $data
     * @return bool
     */
    public static function action(string $actionKey, array $data = []): bool
    {
        $requestData  = new RequestData();
        $requestData->setSessionID($_COOKIE[self::$sessionKey] ?? '');
        foreach ($data as $key => $datum) {
            $requestData->set($key, $datum);
        }
        return parent::registryAction($requestData,$actionKey);
    }

    /**
     * API действий пользователя
     * @param string $actionKey - название действия,
     * @param string $methodName - название метода,
     * @param array $data
     * @return mixed
     */
    public static function api(string $actionKey, string $methodName, array $data = [])
    {
        $requestData  = new RequestData();
        foreach ($data as $key => $datum) {
            $requestData->set($key, $datum);
        }
        return parent::actionAPI($requestData,$actionKey, $methodName);
    }

    /**
     * @return bool
     */
    public static function call(): bool
    {
        $requestData  = new RequestData();
        $requestData->setPhone($_POST['int'] ?? '');
        $requestData->setVirtualPhone($_POST['ext'] ?? '');
        $requestData->set('callDuration',   $_POST['duration']  ?? null);
        $requestData->set('callRecord',     stripcslashes($_POST['mp3link'] ?? ''));
        $requestData->set('callID',         $_POST['id']        ?? null);
        if (empty($requestData->get('callID'))) {
            return false;
        }
        return parent::registryAction($requestData,'Call');
    }

    /**
     * @param null|string $phone
     * @return bool
     */
    public static function callRequest(?string $phone = null): bool
    {
        $requestData  = new RequestData();
        $requestData->setPhone(Phones::digitFormat($phone ?? $_POST['phone'] ?? ''));
        $requestData->setSessionID($_COOKIE[self::$sessionKey] ?? '');
        if (empty($requestData->get('phone'))) {
            return false;
        }
        return parent::registryAction($requestData,'CallRequest');
    }

    /**
     * @param null|string $phone
     * @return bool
     */
    public static function conferenceHallRequest(?string $phone = null): bool
    {
        $requestData  = new RequestData();
        $requestData->setPhone(Phones::digitFormat($phone ?? $_POST['phone'] ?? ''));
        $requestData->setSessionID($_COOKIE[self::$sessionKey] ?? '');
        if (empty($requestData->get('phone'))) {
            return false;
        }
        return parent::registryAction($requestData,'ConferenceHall');
    }

    /**
     * @param string $CFormDefault = CFormDefault::class
     * @param AControllers $controller
     * @return array|null
     * @throws \DomainException
     */
    public static function CFormPhonesGenerate(string $CFormDefault, AControllers $controller): ?array
    {
        resources::setCss(self::getTemplate('css/style-fix.css'));
        /** @var \application\admin\model\CFormDefault $CFormDefault */
        if (!method_exists($CFormDefault,'generation')) {
            throw new DomainException('CFormDefault class hasn\'t generation method');
        }
        $fields     =   [
            [
                'type'              =>  'UKSelect',
                'field'             =>  'status',
                'label'             =>  'Статус',
                'grid'              =>  '1-1',
                'list'              =>  [
                    [
                        'id'        =>  1,
                        'name'      =>  'Активно',
                        'selected'  =>  true
                    ],
                    [
                        'id'        =>  2,
                        'name'      =>  'Неактивно'
                    ],
                    [
                        'id'        =>  3,
                        'name'      =>  'Черновик',
                        'disabled'  =>  true
                    ]
                ],
            ],
            [
                'type'              =>  'UKInput',
                'field'             =>  'real_phone_number',
                'label'             =>  'Существующий номер телефона',
                'required'          =>  true,
                'attrs'             =>  [
                    'placeholder'       =>  '79876543210'
                ],
                'grid'              =>  '1-1'
            ],
            [
                'type'              =>  'UKInput',
                'field'             =>  'real_phone_text',
                'label'             =>  'Отображаемый существующий телефон',
                'required'          =>  true,
                'attrs'             =>  [
                    'placeholder'       =>  '8 (987) 654-32-10'
                ],
                'grid'              =>  '1-1'
            ],
            [
                'type'              =>  'UKInput',
                'field'             =>  'virtual_phone_number',
                'label'             =>  'Виртуальный номер телефона',
                'required'          =>  true,
                'attrs'             =>  [
                    'placeholder'       =>  '78000000000'
                ],
                'grid'              =>  '1-1'
            ],
            [
                'type'              =>  'UKInput',
                'field'             =>  'virtual_phone_text',
                'label'             =>  'Отображаемый виртуальный телефон',
                'required'          =>  true,
                'attrs'             =>  [
                    'placeholder'       =>  '8 (800) 000-00-00'
                ],
                'grid'              =>  '1-1'
            ],
            [
                'type'              =>  'UKInput',
                'field'             =>  'referer',
                'label'             =>  'Источник перехода (referer)',
                'listing'           =>  Array(
                    'view'  =>  false,
                ),
                'grid'              =>  '1-1'
            ],
            [
                'type'              =>  'UKInput',
                'field'             =>  'url',
                'label'             =>  'Посещаемая страница (внутренний url)',
                'listing'           =>  Array(
                    'view'  =>  false,
                ),
                'grid'              =>  '1-1'
            ],
            [
                'type'              =>  'UKInput',
                'field'             =>  'utm_source',
                'label'             =>  'Источник компании (utm_source)',
                'listing'           =>  Array(
                    'view'  =>  false,
                ),
                'grid'              =>  '1-1'
            ],
            [
                'type'              =>  'UKInput',
                'field'             =>  'utm_content',
                'label'             =>  'Идентификатор объявления (utm_content)',
                'listing'           =>  Array(
                    'view'  =>  false,
                ),
                'grid'              =>  '1-1'
            ],
            [
                'type'              =>  'UKInput',
                'field'             =>  'utm_medium',
                'label'             =>  'Тип трафика (utm_medium)',
                'listing'           =>  Array(
                    'view'  =>  false,
                ),
                'grid'              =>  '1-1'
            ],
            [
                'type'              =>  'UKInput',
                'field'             =>  'utm_campaign',
                'label'             =>  'Название компании (utm_campaign)',
                'listing'           =>  Array(
                    'view'  =>  false,
                ),
                'grid'              =>  '1-1'
            ],
            [
                'type'              =>  'UKInput',
                'field'             =>  'utm_term',
                'label'             =>  'Ключевое слово (utm_term)',
                'listing'           =>  Array(
                    'view'  =>  false,
                ),
                'grid'              =>  '1-1'
            ],
            [
                'type'              =>  'UKInput',
                'field'             =>  'utm_keyword',
                'label'             =>  'utm_keyword',
                'listing'           =>  Array(
                    'view'  =>  false,
                ),
                'grid'              =>  '1-1'
            ],
            [
                'type'              =>  'UKInput',
                'field'             =>  'utm_fastlink',
                'label'             =>  'utm_fastlink',
                'listing'           =>  Array(
                    'view'  =>  false,
                ),
                'grid'              =>  '1-1'
            ],
        ];
        return $CFormDefault::generation($controller, Phones::getTableName(), 'Телефоны', $fields);
    }

    /**
     * @param string $CFormDefault = CFormDefault::class
     * @param AControllers $controller
     * @return array|null
     * @throws \BadMethodCallException
     * @throws \DomainException
     */
    public static function CFormActionsGenerate(string $CFormDefault, AControllers $controller): ?array
    {
        resources::setJs(self::getTemplate('vendor/wavesurfer.min.js'));
        resources::setJs(self::getTemplate('js/cform.js'));
        resources::setCss(self::getTemplate('css/analytic.css'));
        resources::setCss(self::getTemplate('css/cform-table-td-align-center.css'));

        /** @var \core\component\database\driver\PDO\component $db */
        $db = registry::get('db');

        /** @var \application\admin\model\CFormDefault $CFormDefault */
        switch (true) {
            case !method_exists($CFormDefault,'generation'):
            case !method_exists($CFormDefault,'config'):
            case !method_exists($CFormDefault,'setNoEdit'):
            case !method_exists($CFormDefault,'setDefaultOrder'):
            case !method_exists($CFormDefault,'setRowButtonController'):
            case !method_exists($CFormDefault,'addButton'):
                throw new DomainException('CFormDefault class hasn\'t [generation|config|setNoEdit|setDefaultOrder|addButton] method');
        }
        $fields     =   [
            [
                'type'              =>  'UKInput',
                'field'             =>  'action_name',
                'label'             =>  'Тип обращения',
            ],
            [
                'type'              =>  'UKInput',
                'field'             =>  'phone',
                'label'             =>  'Номер телефона',
            ],
            [
                'type'              =>  'UKInput',
                'field'             =>  'source',
                'label'             =>  'Источник',
            ],
            [
                'type'              =>  'UKInput',
                'field'             =>  'date_insert',
                'label'             =>  'Дата',
            ],
        ];
        $additionalDataButton = [
            'action'    => 'view',
            'type'      => 'UKButton',
            'url'       => '#',
            'title'     => 'Доп. данные',
            'icon'      => 'search',
            'class'     => 'uk-button-primary uk-button-small call-tracking-alert-data'
        ];
        $playRecordButton = [
            'action'    => 'play',
            'type'      => 'UKButton',
            'url'       => '#',
            'title'     => 'Прослушать запись звонка',
            'icon'      => 'play-circle',
            'class'     => 'uk-button-danger uk-button-small call-tracking-play-record'
        ];
        $dataFiller = function($table, $field, $where) use ($db){
            /** @noinspection PhpUnusedLocalVariableInspection */
            $field = '*, NULL AS `phone`, NULL AS `source`, NULL AS `record`';
            /** @var array $actions */
            $actions = \call_user_func_array([$db,'selectRows'],\func_get_args());
            $visitors = Visit::getActionsInfo($actions);
            $visitors = array_column($visitors, null, 'visitor_id');
            $requestData = new RequestData();

            foreach ($actions as &$action) {
                $actionObject = new Action($requestData);
                $extensionData = $actionObject->getExtensionData($action['action_key'],$action['id']);
                $visitor = $visitors[$action['visitor_id']] ?? [];

                $action['phone']        = $extensionData['phone'] ?? '';
                $action['record']       = $extensionData['record_store'] ?? '';
                $action['source']       = $visitor['utm_source'] ?? '';
                $action['url']          = $visitor['url'] ?? '';
                $action['referer']      = $visitor['referer'] ?? '';
                $action['utm_source']   = $visitor['utm_source'] ?? '';
                $action['utm_term']     = $visitor['utm_term'] ?? '';
                $action['utm_content']  = $visitor['utm_content'] ?? '';
                $action['utm_medium']   = $visitor['utm_medium'] ?? '';
                $action['utm_campaign'] = $visitor['utm_campaign'] ?? '';
                $action['utm_keyword']  = $visitor['utm_keyword'] ?? '';
                $action['utm_fastlink'] = $visitor['utm_fastlink'] ?? '';
            }

            return $actions;

        };
        $rowButtonController = function(IButton $button){
            /** @var \core\component\CForm\button\UKButton\component $button */
            if ($button->getButton()['action'] === 'play') {
                if (empty($button->getRow()['record'])) {
                    $button->setAnswer('');
                } else {
                    $button->setAnswer(str_replace('class=','data-sound="/filecache/' . $button->getRow()['record'] . '" class=', $button->getAnswer()));
                }
            }
            if ($button->getButton()['action'] === 'view') {
                $record = $button->getRow();
                $data = [
                    'REFERER'      => $record['referer'],
                    'UTM_SOURCE'   => $record['utm_source'],
                    'UTM_TERM'     => $record['utm_term'],
                    'UTM_CONTENT'  => $record['utm_content'],
                    'UTM_MEDIUM'   => $record['utm_medium'],
                    'UTM_CAMPAIGN' => $record['utm_campaign'],
                    'UTM_KEYWORD'  => $record['utm_keyword'],
                    'UTM_FASTLINK' => $record['utm_fastlink'],
                ];
                $template = self::getTemplate('template/utm_alert.tpl');
                $alertHTML = simpleView\component::replace($template,$data);
                $button->setAnswer($button->getAnswer() . $alertHTML);
            }
            return $button;
        };
        $CFormDefault::config($controller, Action::getTableName(), 'Обращения', $fields, null);
        $CFormDefault::setDataFunctions($dataFiller, [$db,'selectCount']);
        $CFormDefault::setNoEdit();
        $CFormDefault::setDefaultOrder(['date_insert' => 'DESC']);
        $CFormDefault::setRowButtonController($rowButtonController);
        $CFormDefault::addButton($playRecordButton,'row');
        $CFormDefault::addButton($additionalDataButton,'row');
        return $CFormDefault::generation($controller);
    }

    /**
     * отдает шаблон
     * @param string $template шаблон
     * @param string $dir
     *
     * @return string шаблон
     */
    private static function getTemplate(string $template, string $dir = __DIR__): string
    {
        $dir    =   strtr($dir, Array(
            '\\' =>  '/'
        ));
        $dr    =   strtr(core::getDR(), Array(
            '\\' =>  '/'
        ));
        return '/' . str_replace($dr,'', $dir) . '/' . $template;
    }

    /** @var  DateTime */
    private static $dateEnd;
    /** @var  DateTime */
    private static $dateStart;
    /** @var  bool */
    private static $uniqueVisitors;

    /**
     * Подписи под графиком
     * @param DateTime $dateStart
     * @param DateTime $dateEnd
     * @return array
     * @throws \Exception
     */
    private static function getChartXLabels(DateTime $dateStart, DateTime $dateEnd): array
    {
        $result = [];
        if ($dateStart > $dateEnd) {
            [$dateStart,$dateEnd] = [$dateEnd,$dateStart];
        }
        $curDate = clone $dateStart;
        $interval = new \DateInterval('P2D');
        $result[] = $curDate->format('d.m.Y');
        while ($curDate->format('Ymd') < $dateEnd->format('Ymd')) {
            $curDate->add($interval);
            $result[] = $curDate->format('d.m.Y');
        }
        return $result;
    }

    /**
     * Цвета меток
     *
     * @param int $key
     * @return string
     */
    private static function getChartDataLabelColor(int $key): string
    {
        $colors = [
            '#555555',
            '#2319DC',
            '#315EFB',
            '#4285F4',
            '#FF0000',
            'maroon',
            'purple',
            'rgba(54, 162, 235, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(255, 99, 132, 0.5)',
            'rgba(54, 162, 235, 0.5)',
            'rgba(75, 192, 192, 0.5)',
        ];
        return $colors[$key % \count($colors)];
    }

    /**
     * Получаем данные для графиков и excel файлов с параметрами формы
     *
     * @param callable $function
     * @return array
     */
    private static function getAnalyticsData(callable $function) : array
    {
        if (!empty($_GET['period'])) {
            $dates = explode('-',$_GET['period']);
            if (\count($dates) !== 2) {
                return [];
            }
            $dateStart = new DateTime($dates[0]);
            $dateEnd = new DateTime($dates[1]);

            if (!$dateStart || !$dateEnd) {
                return [];
            }
        }
        else {
            $dateStart = (new DateTime())->sub(\DateInterval::createFromDateString('1 months - 1 day'));
            $dateEnd = new DateTime();
        }
        self::$dateStart = $dateStart;
        self::$dateEnd = $dateEnd;
        self::$uniqueVisitors = (bool) ($_GET['unique_visits'] ?? false);

        return $function(self::$dateStart,self::$dateEnd, self::$uniqueVisitors);
    }
    /**
     * Страница графика
     *
     * @param string $title
     * @param string $type
     * @param $data
     * @return string
     * @throws \Exception
     */
    private static function getChart(string $title, string $type, array $data) : string
    {
        $datasets = [];
        $summary = [];
        $summary_total = 0;
        foreach ($data as $key => $item) {
            $color = self::getChartDataLabelColor($key);
            $summary_total += $item['summary'];
            $summary[] = [
                'SUMMARY_TITLE' =>  $item['name'],
                'SUMMARY_COLOR' =>  $color,
                'SUMMARY_VALUE' =>  $item['summary'],
            ];
            foreach ((array) $item['points'] as $index => $point) {
                /** @var DateTime $date */
                $date = $point['x'];
                $item['points'][$index]['x'] = $date->format('d.m.Y');
            }
            $datasets[] = [
                'backgroundColor'   =>  $color,
                'borderColor'       =>  $color,
                'borderWidth'       =>  '1',
                'data'              =>  $item['points'],
                'fill'              =>  false,
                'label'             =>  $item['name'],
                'summary'           =>  $item['summary'],
            ];
        }
        $result = [
            'TITLE'             => $title,
            'VIEW'              => $type,
            'LABELS'            => json_encode(self::getChartXLabels(self::$dateStart,self::$dateEnd)),
            'DATASETS'          => json_encode($datasets),
            'UNIQUE_VISITORS'   => (self::$uniqueVisitors !== false) ? 'checked' : '',
            'SUMMARY'           => $summary,
            'SUMMARY_TOTAL'     => $summary_total,
            'START_DATE'        => self::$dateStart->format('d.m.Y'),
            'END_DATE'          => self::$dateEnd->format('d.m.Y'),
        ];

        resources::setJs(self::getTemplate('vendor/moment-with-locales.min.js'));
        resources::setJs(self::getTemplate('vendor/Chart.bundle.js'));
        resources::setJs(self::getTemplate('vendor/daterange/jquery.datepicker.js'));
        resources::setJs(self::getTemplate('js/init-chart.js'));
        resources::setCss(self::getTemplate('vendor/daterange/jquery.datepicker.css'));
        resources::setCss(self::getTemplate('css/analytic.css'));
        $template = self::getTemplate('template/chart.tpl');

        return simpleView\component::replace($template,$result);
    }

    /**
     * @param $data - данные в виде вдумерного массива
     * @param string $fileName - название файла
     * @param string $title - заголовок
     * @throws \PHPExcel_Exception
     */
    private static function outputExcel($data, $fileName = 'analytics', $title = 'Аналитика'): void
    {
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator('INDINS LLC');
        $objPHPExcel->getProperties()->setLastModifiedBy('INDINS LLC Information System');
        $objPHPExcel->getProperties()->setTitle($title);
        $objPHPExcel->getProperties()->setSubject($title);
        $objPHPExcel->getProperties()->setDescription($title);
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle($title);
        $sheet->fromArray($data, null, 'A1', false);
        $lastCol = $sheet->getHighestDataColumn();
        $lastRow = $sheet->getHighestRow();
        $sheet
            ->getStyle('B1:'.$lastCol.$lastRow)
            ->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $styleBoldArray = array(
            'font' => array(
                'bold' => true
            )
        );
        $sheet
            ->getStyle('A1:'.$lastCol.'1')
            ->applyFromArray($styleBoldArray);
        $sheet
            ->getStyle('A'.$lastRow.':'.$lastCol.$lastRow)
            ->applyFromArray($styleBoldArray);
        foreach (range('A', $lastCol) as $col) {
            $sheet
                ->getColumnDimension($col)
                ->setAutoSize(true);
        }

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    }

    /**
     * @param array $data
     * @return array
     */
    public static function getExcelData(array $data): array
    {
        $excelData = [];
        $header = [''];
        $footer = [];
        $total = 0;
        foreach ($data as $col) {
            $header[] = $col['name'];
            $footer[] = $col['summary'];
            $total += $col['summary'];

            foreach ((array) $col['points'] as $cell) {
                /** @var \DateTime $dateTime */
                $dateTime = $cell['x'];
                $dateText = $dateTime->format('d.m.Y');
                if (!isset($excelData[$dateText])) {
                    $excelData[$dateText] = [$dateText];
                }
                $excelData[$dateText][] = $cell['y'] ?: '-';
            }
        }
        $excelData = array_values($excelData);
        array_unshift($footer,'Всего: ' . $total);
        array_unshift($excelData,$header);
        $excelData[] = $footer;
        return $excelData;
    }

    /**
     * Аналитика по типу
     *
     * @return string
     * @throws \Exception
     * @throws \PHPExcel_Exception
     */
    public static function analyticsByAction(): string
    {
        $data = self::getAnalyticsData([Action::class,'analyticsDataActions']);
        if (empty($_GET['download'])) {
            return self::getChart('Аналитика по типу', 'type', $data);
        }
        $excelData = self::getExcelData($data);
        self::outputExcel($excelData, 'gala-sport-action-analytics', 'Аналитика по типу');
        exit;
    }

    /**
     * Аналитика по источникам
     *
     * @return string
     * @throws \Exception
     * @throws \PHPExcel_Exception
     */
    public static function analyticsBySource(): string
    {
        $data = self::getAnalyticsData([Action::class,'analyticsDataSources']);
        if (empty($_GET['download'])) {
            return self::getChart('Аналитика по источникам', 'type', $data);
        }
        $excelData = self::getExcelData($data);
        self::outputExcel($excelData, 'gala-sport-source-analytics', 'Аналитика по источникам');
        exit;
    }
}