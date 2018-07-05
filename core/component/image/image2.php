<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 23.06.2018
 * Time: 17:38
 *
 * Usage:
 *
 *      #Выводим уменьшенное изобращение, не меньшее 100рх по измерениям,
 *      # обрезанное до 100рх х 100рх
 *      echo
 *          image::get($path)
 *              ->cover(100,100)
 *              ->crop();
 *
 *  ------------------------------------------------------------------------
 *
 *      #Сохраняем настройки изображения (размер не более 150х200 и качество jpeg - 90)
 *      # с ключом 'item_preview'
 *      image::set('item_preview')
 *          ->contain(['width' => 150, 'height' => 200])
 *          ->quality(90);
 *
 *      #Изменяем изображение с использованием раннее заданных настроек, но с качеством 65
 *      echo image::get($path,'item_preview')
 *              ->quality(65);
 *
 *  ------------------------------------------------------------------------
 *
 *      #Старый синтаксис тоже поддерживается
 *      $options = [
 *          [
 *              'action'    => 'crop',
 *              'width'     =>  300,
 *              'height'    =>  200,
 *          ]
 *      ];
 *      image::image('item_preview',$options);
 *
 *  ------------------------------------------------------------------------
 *
 * API:
 *      image->__toString() : string
 *           Возращает URI нового изображения, если не сможет - старого, если не найдёт - пустую строку
 *
 *      image::image(path[,options]) : string
 *           Возращает тоже самое что и image->__toString
 *
 *      image::get(path[,keyword]) : image
 *           path - путь до изображения
 *           keyword - ключевое слово сохраннёных настроек, еслине найдены - настройки будут пусты
 *           Вернёт экземпляр самого себя
 *
 *      image::set(keyword) : image
 *           defaultsKeyword - ключевое слово для сохраннёния настроек
 *           Вернёт экземпляр самого себя
 *
 *      image->resize(тут параметры перечислены или ассоциативный массив)
 *           простое сжатие изображения
 *
 *      image->crop(тут параметры перечислены или ассоциативный массив)
 *           простое обрезание изображения по размерам и координатам (по умолчанию вырезает центер)
 *
 *      image->quality(значение качества)
 *           меняем качество jpeg изображения
 *
 *      image->contain(тут параметры перечислены или ассоциативный массив)
 *           Контейнер=) Изображение будет не больше чем указанные размеры
 *           Как-то так: [картинка]
 *
 *      image->cover(тут параметры перечислены или ассоциативный массив)
 *           Ковёр=) Изображение будет не меньше чем указанные размеры
 *           Как-то так: К[АРТИНК]А
 *
 */

namespace core\component\image;


use core\component\dir\dir;
use core\component\fileCache\fileCache;

class image
{
    /** @var string Изображение по умолчанию */
    private static $noImageURI = 'filecache/no.png';

    /** @var string URI изображения  */
    private $uri;

    /** @var int Начальная ширина изображения */
    private $width;

    /** @var int Начальная высота изображения */
    private $height;

    /** @var \Imagick Изменённое изображение*/
    private $thumbnail;

    /** @var int Качество сохранения JPEG */
    private $quality = 65;

    /** @var array Настройки заданные по умолчанию */
    private static $defaults = [];

    /** @var array Стек модификаторов */
    private $stack;

    /**
     * Старый стиль
     *
     * @param string $uri
     * @param array $stack
     * @return string
     */
    public static function image(string $uri, array $stack = [])
    {
        return (string) new self($uri ?: static::$noImageURI, $stack);
    }

    /**
     * Получаем объект image
     *
     * @param string $uri
     * @param string|null $defaultsKey
     * @return image
     */
    public static function get(string $uri, ?string $defaultsKey = null): self
    {
        return new self($uri ?: static::$noImageURI, static::$defaults[$defaultsKey] ?? []);
    }

    /**
     * Устанавливаем настройки по ключу
     *
     * @param string $key
     * @return image
     */
    public static function set(string $key): self
    {
        $self = new self(null);
        static::$defaults[$key] = [];
        /** @noinspection PhpUndefinedFieldInspection */
        $self->defaultsKey = $key;
        return $self;
    }

    /**
     * image constructor.
     * @param null|string $uri
     * @param array|null $stack
     */
    public function __construct(?string $uri, ?array $stack = [])
    {
        $this->uri = $uri;
        $this->stack = $stack;
    }

    /** @noinspection MoreThanThreeArgumentsInspection
     * @param $action
     * @param array $params
     */
    private function stackPush($action, array $params): void
    {
        $option = [
            'action'    =>  $action,
            'width'     =>  $params['width'] ?? null,
            'height'    =>  $params['height'] ?? null,
            'x'         =>  $params['x'] ?? null,
            'y'         =>  $params['y'] ?? null,
            'quality'   =>  $params['quality'] ?? null,
        ];
        $this->stack[] = $option;
        if (isset($this->defaultsKey)) {
            if (!isset(self::$defaults[$this->defaultsKey])) {
                self::$defaults[$this->defaultsKey] = [];
            }
            self::$defaults[$this->defaultsKey][] = $option;
        }
    }

    /**
     * @param $width
     * @param int|null $height
     * @return image
     */
    public function resize($width,?int $height): self
    {
        if (\is_array($width) && null === $height) {
            $list = $width;
        } else {
            $list = [
                'width'   => (int) $width,
                'height'   => (int) $height
            ];
        }
        $this->stackPush('resize', $list);
        return $this;
    }

    /** @noinspection MoreThanThreeArgumentsInspection
     * @param int|array|null $width
     * @param int|null $height
     * @param int|null $x
     * @param int|null $y
     * @return image
     */
    public function crop($width = null, ?int $height = null, ?int $x = null, ?int $y = null): self
    {
        if (null === $width && null === $height && null === $x && null === $y && \count($this->stack)) {
            $list   = $this->stack[\count($this->stack) - 1];
        } elseif (\is_array($width) && null === $height && null === $x && null === $y) {
            $list = $width;
        } else {
            $list = [
                'width'     => $width,
                'height'    => $height,
                'x'         => $x,
                'y'         => $y
            ];
        }
        $this->stackPush('crop', $list);
        return $this;
    }

    /**
     * @param $width
     * @param int|null $height
     * @return image
     */
    public function contain($width,?int $height = null): self
    {
        if (\is_array($width) && null === $height) {
            $list = $width;
        } else {
            $list = [
                'width'   => (int) $width,
                'height'   => (int) $height
            ];
        }
        $this->stackPush('contain', $list);
        return $this;
    }

    /**
     * @param $width
     * @param int|null $height
     * @return image
     */
    public function cover($width,?int $height = null): self
    {
        if (\is_array($width) && null === $height) {
            $list = $width;
        } else {
            $list = [
                'width'   => (int) $width,
                'height'   => (int) $height
            ];
        }
        $this->stackPush('cover', $list);
        return $this;
    }

    /**
     * @param int|array $quality
     * @return image
     */
    public function quality($quality): self
    {
        $quality = $quality['quality'] ?? $quality;
        $this->stackPush('quality', [
            'quality'   => (int) $quality
        ]);
        return $this;
    }

    /**
     * Сохранить как JPEG
     *
     * @param string $backgroundColor
     * @return image
     */
    public function asJPEG(string $backgroundColor = 'white'): self
    {
        #TODO надо сделать
        return $this;
    }

    /**
     * Возращает URL превью
     *
     * @return null|string
     */
    public function __toString()
    {
        if (null === $this->uri) {
            return '';
        }
        if (!\extension_loaded('imagick')) {
            /** @noinspection MagicMethodsValidityInspection */
            return $this->uri;
        }
        $imagePath   =   dir::getDR(true) . $this->uri;
        if (!file_exists($imagePath)) {
            $imagePath   =   dir::getDR(false) . $this->uri;

            /** @noinspection NotOptimalIfConditionsInspection */
            if (!file_exists($imagePath)) {
                return '';
            }
        }

        $imageExtension =  array_reverse(explode('.', $this->uri))[0];
        $thumbnailName  =   md5($this->uri . base64_encode(serialize($this->stack)));
        $thumbnailURI   =   '/filecache/cache/' . $thumbnailName . '.' . $imageExtension;

        if (file_exists(dir::getDR(true) .  $thumbnailURI)) {
            return $thumbnailURI;
        }
        $this->thumbnail              =   new \Imagick($imagePath);
        $this->width = $this->thumbnail->getImageWidth();
        $this->height = $this->thumbnail->getImageHeight();

        foreach ($this->stack as $value) {
            $function = [$this, 'image'.ucfirst($value['action'] ?? '')];
            if (isset($value['action']) && \is_callable($function)) {
                $this->$function($value);
            }
        }
        fileCache::checkDir('cache');
        if (\in_array(strtolower($imageExtension), ['jpg','jpeg'])) {
            $this->thumbnail->setImageCompression(\Imagick::COMPRESSION_JPEG);
            $this->thumbnail->setImageCompressionQuality($this->quality);
            $this->thumbnail->stripImage();
        }
        $this->thumbnail->writeImages(dir::getDR(true) .  $thumbnailURI, true);
        $this->thumbnail->destroy();
        return $thumbnailURI;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection
     * @param array $options
     */
    private function imageResize(array $options): void
    {
        $width  = $option['width'] ?? 0;
        $height = $option['height'] ?? 0;
        $this->thumbnail->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection
     * @param array $options
     */
    private function imageAdapriveResizeMax(array $options): void
    {
        $width  = $option['width'] ?? 0;
        $height = $option['height'] ?? 0;
        $x_ratio 		        =   $width / $this->width;
        $y_ratio 		        =   $height / $this->height;
        $ratio       	        =   max($x_ratio, $y_ratio);
        $new_width   = ($x_ratio === $ratio)  ? $width     : floor($this->width * $ratio);
        $new_height  = ($y_ratio === $ratio) ? $height     : floor($this->height * $ratio);
        $this->thumbnail->resizeImage($new_width, $new_height, \Imagick::FILTER_LANCZOS,1);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection
     * @param array $options
     */
    private function imageAdapriveResizeMin(array $options): void
    {
        $width  = $option['width'] ?? 0;
        $height = $option['height'] ?? 0;
        $x_ratio 		        =   $width / $this->width;
        $y_ratio 		        =   $height / $this->height;
        $ratio       	        =   min($x_ratio, $y_ratio);
        $new_width   = ($x_ratio === $ratio)  ? $width     : floor($this->width * $ratio);
        $new_height  = ($y_ratio === $ratio) ? $height     : floor($this->height * $ratio);
        $this->thumbnail->resizeImage($new_width, $new_height, \Imagick::FILTER_LANCZOS,1);

    }

    /** @noinspection PhpUnusedPrivateMethodInspection
     * @param array $options
     */
    private function imageCrop(array $options): void
    {
        $width  = $options['width'] ?? 0;
        $height = $options['height'] ?? 0;
        if (isset($options['x'])) {
            $x = $options['x'];
        } else {
            $widthNow       =   $this->thumbnail->getImageWidth();
            $x 		        =   ($widthNow - $width) / 2;
        }
        if (isset($options['y'])) {
            $y = $options['y'];
        } else {
            $heightNow = $this->thumbnail->getImageHeight();
            $y 		        =   ($heightNow - $height) / 2;
        }
        $this->thumbnail->cropImage($width, $height, $x, $y);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection
     * @param array $options
     */
    private function imageContain(array $options): void
    {
        $this->imageAdapriveResizeMin($options);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection
     * @param array $options
     */
    private function imageCover(array $options): void
    {
        $this->imageAdapriveResizeMax($options);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection
     * @param array $options
     */
    private function imageQuality(array $options): void
    {
        $this->quality = $options['quality'] ?? $this->quality;
    }
}