<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 09.07.2018
 * Time: 17:00
 */

namespace application\client\model;


use core\component\application\AClass;

class MetaData extends AClass
{
    /** @var string */
    private static $title;

    /** @var string */
    private static $description;

    /** @var string */
    private static $keywords;

    /** @var string */
    private static $image;

    private static function getRequestURL() : string
    {
        $requestProtocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $requestDomain = $_SERVER['HTTP_HOST'] ?? '';
        return $requestProtocol . '://' . $requestDomain;
    }

    /**
     * @param array $content
     */
    public static function headMetadata(array &$content): void
    {
        $requestPath = $_SERVER['REQUEST_URI'] ?? '';

        $settings = Settings::getInstance();

        $data = [
            'title' =>
                self::$title
                    ?: self::$page['meta_title']
                    ?: $settings->getConfiguration('meta_title')
                    ?: self::$page['name'],
            'description' =>
                self::$description
                    ?: self::$page['meta_description']
                    ?: $settings->getConfiguration('meta_description')
                    ?: self::$page['content'],
            'keywords'     =>
                self::$keywords
                    ?: self::$page['meta_keywords']
                    ?: $settings->getConfiguration('meta_keywords'),
            'image_uri'    =>
                self::$image
                    ?: self::getRequestURL() . $settings->getConfiguration('og_image'),
            'page_url'     =>  self::getRequestURL() . $requestPath,
        ];
        foreach ($data as $index => $datum) {
            $content['METADATA_' . strtoupper($index)] = mb_substr(preg_replace('/[.]?\s{2,}/u', '.&nbsp;', trim(htmlentities(strip_tags($datum)))),0,300);
        }
    }

    /**
     * @param string $title
     */
    public static function setTitle(string $title): void
    {
        self::$title = $title;
    }

    /**
     * @param string $description
     */
    public static function setDescription(string $description): void
    {
        self::$description = $description;
    }

    /**
     * @param string $keywords
     */
    public static function setKeywords(string $keywords): void
    {
        self::$keywords = $keywords;
    }

    /**
     * @param string $image
     */
    public static function setImage(string $image): void
    {
        self::$image = self::getRequestURL() . $image;
    }
}