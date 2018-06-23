<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 14.6.2017
 * Time: 14:30
 */

namespace core\component\image;


use core\component\fileCache\fileCache;
use core\component\dir\dir;

/**
 * Class image
 *
 * @package core\component\image
 */
class image
{
	/**
	 * @var string
	 */
	private static $urlImage    = '';
	/**
	 * @var string
	 */
	private static $urlImageDR  = '';
	/**
	 * @var \Imagick thumbnail
	 */
	private static $thumbnail   = '';
	/**
	 * @var string
	 */
	private static $key         = '';
	/**
	 * @var string
	 */
	private static $ext         = '';
    /**
     * @var
     */
    private static $quality     = 65;


    /** @noinspection DeprecatedConstructorStyleInspection
	 * @param string $urlImage
	 * @param array  $option
	 *
	 * @return bool|string
	 */
	public static function image(string $urlImage, array $option = Array())
	{
		if ($urlImage === '') {
			$urlImage   =   'filecache/no.png';
		}
        if (!\extension_loaded('imagick')) {
            return $urlImage;
        }
		self::$key          =   md5($urlImage . base64_encode(serialize($option)));
		self::$urlImage     =   $urlImage;
		$tmp                =   explode('.', self::$urlImage);
		self::$ext          =   end($tmp);
		self::$urlImageDR   =   dir::getDR(true) . $urlImage;
        if (!file_exists(self::$urlImageDR)) {
            self::$urlImageDR   =   dir::getDR(false) . $urlImage;

            if (!file_exists(self::$urlImageDR)) {
                return false;
            }
        }
		$new_file = '/filecache/cache/' . self::$key . '.' . self::$ext;
		if (file_exists(dir::getDR(true) .  $new_file)) {
			return $new_file;
		}
		/** @var \Imagick thumbnail */
		self::$thumbnail              =   new \Imagick(self::$urlImageDR);
		foreach ($option as $value) {
			if (isset($value['action'])) {
				$action =   $value['action'];
				self::$action($value);
			}
		}
		fileCache::checkDir('cache');
		if (self::$ext === 'jpg' || self::$ext === 'jpeg' || self::$ext === 'JPG' || self::$ext === 'JPEG') {
			self::$thumbnail->setImageCompression(\Imagick::COMPRESSION_JPEG);
			self::$thumbnail->setImageCompressionQuality(self::$quality);
			self::$thumbnail->stripImage();
		}
		self::$thumbnail->writeImages(dir::getDR(true) .  $new_file, true);
		return $new_file;
	}

    /**
	 * @param array $option
	 */
	protected static function resize(array $option = Array()): void
    {
		$width  = $option['width'] ?? 0;
		$height = $option['height'] ?? 0;
		self::$thumbnail->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1);
	}

	/**
	 * @param array $option
	 */
    protected static function quality(array $option = Array()): void
    {
		self::$quality  = $option['quality'] ?? self::$quality;
	}

	private static function adaptiveResize(array $options, callable $ratioFunction): void
    {
        $width  = $option['width'] ?? 0;
        $height = $option['height'] ?? 0;
        [$original_width,$original_height] = getimagesize(self::$urlImageDR);
        $x_ratio 		        =   $width / $original_width;
        $y_ratio 		        =   $height / $original_height;
        $ratio       	        =   $ratioFunction($x_ratio, $y_ratio);
        $use_x_ratio 	        =   ($x_ratio === $ratio);
        $new_width   = $use_x_ratio  ? $width     : floor($original_width * $ratio);
        $new_height  = !$use_x_ratio ? $height     : floor($original_height * $ratio);
        self::$thumbnail->resizeImage($new_width, $new_height, \Imagick::FILTER_LANCZOS,1);
    }

	/**
	 * @param array $option
	 */
    protected static function adapriveResizeMax(array $option = Array()): void
    {
		self::adaptiveResize($option,'min');
	}

	/**
	 * @param array $option
	 */
    protected static function adapriveResizeMin(array $option = Array()): void
    {
        self::adaptiveResize($option,'max');
	}

	/**
	 * @param array $option
	 */
    protected static function contain(array $option = Array()): void
    {
		self::adaptiveResize($option,'min');
	}

	/**
	 * @param array $option
	 */
    protected static function cover(array $option = Array()): void
    {
        self::adaptiveResize($option,'max');
	}

	/**
	 * @param array $option
	 */
    protected static function crop(array $option = Array()): void
    {
        $width  = $option['width'] ?? 0;
        $height = $option['height'] ?? 0;
        if (isset($option['x'])) {
            $x = $option['x'];
        } else {
            $widthNow       = self::$thumbnail->getImageWidth();
            $x 		        =   ($widthNow - $width) / 2;
        }
        if (isset($option['y'])) {
            $y = $option['y'];
        } else {
            $heightNow = self::$thumbnail->getImageHeight();
            $y 		        =   ($heightNow - $height) / 2;
        }
        self::$thumbnail->cropImage($width, $height, $x, $y);
	}
}