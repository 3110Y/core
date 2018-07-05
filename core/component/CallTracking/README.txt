Поключение:
    1. Актуализировать (на 20.06.2018):
        model/CFormDefault
        CForm/
            AViewer
            AButton
            field/UKInput
            button/UKButton
            viewer/UKListing/component
    2. Подключить PHPExcel
    3. Прописать инициализацию в роутере приложения
       application/client/router.php:

        /**
         * @return $this
         */
        public function run()
        {
            if (!self::$isAjaxRequest) {
                $callTracking = new CallTrackingAdapter();
                registry::set('CallTracking',$callTracking);
            }
            parent::run();
            return $this;
        }

    4. Сделать подмену номера
        а. Либо поиском и заменой по всему документу в application/client/router.php:
            [ данный способ не рекомендуется, но не возбраняется ]

             /**
             * @return string
             */
            public function render()
            {
                $result = parent::render();
                /** @var \core\component\CallTracking\callTrackingAdapter $callTracking */
                $callTracking =   registry::get('CallTracking');
                if ($callTracking !== false) {
                    $result = $callTracking->replace($result);
                }
                return $result;
            }

        б. Либо непосредственно в месте вывода номера

            ...
            /** @var \core\component\CallTracking\callTrackingAdapter $callTracking */
            $callTracking =   registry::get('CallTracking');
            $phone = $callTracking->getSubstitution($phone);
            ...

            Доступны методы:
                hasSubstitution($phone) - возращает bool надо ли заменять этот номер
                hasSubstitution() - возращает bool надо ли вообще что либо заменять
                getSubstitution($phone) - возращает номер, который необходимо вывести

    5. Прописать регистрацию действий пользователя
        а. Либо через функицю регистрации действий
            CallTrackingAdapter::action(Ключ действия[,доп. данные]);

        б. Либо через предопределённые функции различных действий
            CallTrackingAdapter::call(); - звонок
            CallTrackingAdapter::callRequest(); - заказ звонка
            CallTrackingAdapter::conferenceHallRequest(); - заказ конференц-зала

    6. Создать разделы в админ панели и добавить контроллеры (по примеру как gala-sport.ru)
        а. Раздел управления телефонами:
            self::$content = CallTrackingAdapter::CFormPhonesGenerate(CFormDefault::class, $this);
        б. Раздел обращения:
            self::$content = CallTrackingAdapter::CFormActionsGenerate(CFormDefault::class, $this);
        в. Раздел аналитики по источнику:
            self::$content['CONTENT'] = CallTrackingAdapter::analyticsBySource();
        г. Раздел аналитики по типу:
            self::$content['CONTENT'] = CallTrackingAdapter::analyticsByAction();
        д. Неотображаемый раздел установки (При повторном вызове происходит исключительно создание новых таблиц из расширений действия)
            self::$content['CONTENT'] = CallTrackingAdapter::install() ? 'Успешно' : 'Ошибка!';

    7. По необходимости добавить расширения регистрации действий пользователей
        см. CallTracking/source/Extensions/*.php
        Название класса будет являться его ключом в БД и ключем вызова действия/API

    8. По необходимости прописать вызов методов расширений:
        CallTrackingAdapter::api(Ключ действия, метод, данные);

        Приме: CallTrackingAdapter::api('Call','downloadRecord') - скачивает аудиозапись звонка;

    9. Для сохранения звонков завести отдельное приложение (по примеру как CallTracking application на gala-sport.ru),
        доступное по сслыке и из крона.
            при доступе по ссылке регистрировать действие:
                CallTrackingAdapter::call();
            при вызове по крону вызывать метод downloadRecord расширения Call
                CallTrackingAdapter::api('Call','downloadRecord');


