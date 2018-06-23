<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 13.06.2018
 * Time: 18:09
 */

namespace core\component\CallTracking\source;


class RequestData
{

    /** @var string Источник компании */
    private $utmSource;

    /** @var string Индификатор источника компании */
    private $utmSourceID = 0;

    /** @var string Тип трафика */
    private $utmMedium;

    /** @var string Название компании */
    private $utmCampaign;

    /** @var string Идентификатор объявления */
    private $utmContent;

    /** @var string Ключевое слово */
    private $utmTerm;

    /** @var string URI запроса  */
    private $requestURI;

    /** @var string путь запроса  */
    private $URLPath;

    /** @var string параметры запроса */
    private $URLQuery;

    /** @var string источник перехода */
    private $httpReferer;

    /** @var string индификатор посетителя */
    private $cookiesSessionID;

    /** @var string User-Agent пользователя */
    private $userAgent;

    /** @var bool Является ли пользователь ботом */
    private $searchBot = false;

    /** @var string Content-type ожидаемый браузером */
    private $accept;

    /** @var bool Есть ли text/html в HTTP_ACCEPT */
    private $htmlRequired = true;

    /** @var string Номер телефона пользователя */
    private $phone;

    /** @var string Виртуальный номер телефона */
    private $virtualPhone;

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->utmSource ?? '';
    }

    /**
     * @param string $source
     */
    public function setSource(string $source): void
    {
        $this->utmSourceID = Source::getID($source);
        $this->utmSource = $source;
    }

    /**
     * @return int
     */
    public function getSourceID(): int
    {
        return (int) $this->utmSourceID;
    }

    /**
     * @param int $sourceID
     */
    public function setSourceID(int $sourceID): void
    {
        $this->utmSource = Source::getByID($sourceID);
        $this->utmSourceID = $sourceID;
    }

    /**
     * @return string
     */
    public function getMedium(): string
    {
        return $this->utmMedium ?? '';
    }

    /**
     * @param string $medium
     */
    public function setMedium(string $medium): void
    {
        $this->utmMedium = $medium;
    }

    /**
     * @return string
     */
    public function getCampaign(): string
    {
        return $this->utmCampaign ?? '';
    }

    /**
     * @param string $campaign
     */
    public function setCampaign(string $campaign): void
    {
        $this->utmCampaign = $campaign;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->utmContent ?? '';
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->utmContent = $content;
    }

    /**
     * @return string
     */
    public function getTerm(): string
    {
        return $this->utmTerm ?? '';
    }

    /**
     * @param string $term
     */
    public function setTerm(string $term): void
    {
        $this->utmTerm = $term;
    }

    /**
     * Получаем ассоциативный массив UTM меток
     *
     * @return array
     */
    public function getUTM(): array
    {
        return [
            'utm_source'    => $this->getSource(),
            'utm_medium'    => $this->getMedium(),
            'utm_campaign'  => $this->getCampaign(),
            'utm_content'   => $this->getContent(),
            'utm_term'      => $this->getTerm(),
        ];
    }

    /**
     * Парсим массив UTM меток и заполняем данные
     *
     * @param array $data
     */
    public function setUTM(array $data = []): void
    {
        $this->setSource($data['utm_source'] ?? '');
        $this->setMedium($data['utm_medium'] ?? '');
        $this->setCampaign($data['utm_campaign'] ?? '');
        $this->setContent($data['utm_content'] ?? '');
        $this->setTerm($data['utm_term'] ?? '');
        foreach ($data as $key => $datum) {
            if (0 === strpos($key, 'utm_')) {
                $this->set($key, $datum);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getRequestURI(): string
    {
        return $this->requestURI ?? '';
    }

    /**
     * @return mixed
     */
    public function getURLPath(): string
    {
        return $this->URLPath ?? '';
    }

    /**
     * @return mixed
     */
    public function getURLQuery(): string
    {
        return $this->URLQuery ?? '';
    }

    /**
     * Парсим URI запроса и записываем
     *
     * @param string $requestURI
     */
    public function setURI(string $requestURI): void
    {
        /** @noinspection ReturnFalseInspection */
        $this->URLPath = parse_url($requestURI,PHP_URL_PATH) ?: '';
        /** @noinspection ReturnFalseInspection */
        $this->URLQuery = parse_url($requestURI,PHP_URL_QUERY) ?: '';

        $this->requestURI = $requestURI;
    }

    /**
     * @return string
     */
    public function getReferer(): string
    {
        return $this->httpReferer ?? '';
    }

    /**
     * @param string $referer
     */
    public function setReferer(string $referer): void
    {
        $this->httpReferer = $referer;
    }

    /**
     * @return string
     */
    public function getSessionID(): string
    {
        return $this->cookiesSessionID ?? '';
    }

    /**
     * @param string $sessionID
     */
    public function setSessionID(string $sessionID = ''): void
    {
        $this->cookiesSessionID = $sessionID ?: strtr(uniqid(random_int(0, 999), true), ['.' => random_int(0, 9)]);
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent ?? '';
    }

    /**
     * @param string $userAgent
     */
    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
        /** @noinspection SpellCheckingInspection */
        $this->searchBot = (bool) preg_match('/Applebot|baiduspider|Bingbot|Googlebot|ia_archiver|msnbot|Naverbot|seznambot|Slurp|teoma|Twitterbot|Yandex|Yeti/',$userAgent);
    }

    /**
     * @return string
     */
    public function getAccept(): string
    {
        return $this->accept ?? '';
    }

    /**
     * @param string $accept
     */
    public function setAccept(string $accept): void
    {
        $this->accept = $accept;
        $this->htmlRequired = (false !== strpos($accept,'text/html'));
    }

    /**
     * @return bool
     */
    public function isHtmlRequired(): bool
    {
        return $this->htmlRequired;
    }

    /**
     * @return bool
     */
    public function isSearchBot(): bool
    {
        return $this->searchBot;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone ?? '';
    }

    /**
     * @param string $phone
     * @return void
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getVirtualPhone(): string
    {
        return $this->virtualPhone ?? '';
    }

    /**
     * @param string $phone
     * @return void
     */
    public function setVirtualPhone(string $phone): void
    {
        $this->virtualPhone = $phone;
    }

    /**
     * @param string $param
     * @return mixed
     */
    public function get(string $param)
    {
        $selfVariables = array_keys(get_class_vars(self::class));

        if (!\in_array($param, $selfVariables, true)) {
            return $this->$param ?? null;
        }
        if (\is_callable([$this,'get' . ucfirst($param)])) {
            return $this->{'get' . ucfirst($param)}();
        }
        if (\is_callable([$this,'is' . ucfirst($param)])) {
            return $this->{'is' . ucfirst($param)}();
        }
        return null;
    }

    /**
     * @param string $param
     * @param mixed $value
     * @return void
     */
    public function set(string $param, $value): void
    {
        $selfVariables = array_keys(get_class_vars(self::class));

        if (!\in_array($param, $selfVariables, true)) {
            $this->$param = $value;
        }
        if (\is_callable([$this,'set' . ucfirst($param)])) {
            $this->{'set' . ucfirst($param)}($value);
        }
    }

    public function __toString() : string
    {
        $data = array_filter(get_object_vars($this), function($value){
            return null !== $value;
        });
        return json_encode($data);
    }

}