<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 14.6.2017
 * Time: 14:30
 */

namespace core\component\image;
use core\component\fileCache\fileCache;
use core\core;

/**
 * Class component
 *
 * @package core\component\image
 */
class component
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
	 * @const float
	 */
	const VERSION   =   1.4;


	/**
	 * @param string $urlImage
	 * @param array  $option
	 *
	 * @return bool|string
	 */
	public static function image(string $urlImage, array $option = Array())
	{
		if ($urlImage == '') {
			$urlImage   =   'filecache/no.png';
		}
		self::$key          =   md5($urlImage . base64_encode(serialize($option)));
		self::$urlImage     =   $urlImage;
		$tmp                =   explode('.', self::$urlImage);
		self::$ext          =   end($tmp);
		self::$urlImageDR   =   core::getDR(true) . $urlImage;
		if (!file_exists(self::$urlImageDR)) {
			return false;
		}
		$new_file = '/filecache/cache/' . self::$key . '.' . self::$ext;
		if (file_exists(core::getDR(true) .  $new_file)) {
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
		self::$thumbnail->writeImages(core::getDR(true) .  $new_file, true);
		return $new_file;
	}

	/**
	 * @param array $option
	 */
	private static function resize(array $option = Array())
	{
		$width  = $option['width'] ?? 0;
		$height = $option['height'] ?? 0;
		self::$thumbnail->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1);
	}

	/**
	 * @param array $option
	 */
	private static function adapriveResizeMax(array $option = Array())
	{
		$width  = $option['width'] ?? 0;
		$height = $option['height'] ?? 0;
		$original_size          =   getimagesize(self::$urlImageDR);
		$original_width 		=   $original_size[0];
		$original_height 		=   $original_size[1];
		$x_ratio 		        =   $width / $original_width;
		$y_ratio 		        =   $height / $original_height;
		$ratio       	        =   min($x_ratio, $y_ratio);
		$use_x_ratio 	        =   ($x_ratio === $ratio);
		$new_width   = $use_x_ratio  ? $width     : floor($original_width * $ratio);
		$new_height  = !$use_x_ratio ? $height     : floor($original_height * $ratio);
		self::$thumbnail->resizeImage($new_width, $new_height, \Imagick::FILTER_LANCZOS,1);
	}

	/**
	 * @param array $option
	 */
	private static function adapriveResizeMin(array $option = Array())
	{
		$width  = $option['width'] ?? 0;
		$height = $option['height'] ?? 0;
		$original_size          =   getimagesize(self::$urlImageDR);
		$original_width 		=   $original_size[0];
		$original_height 		=   $original_size[1];
		$x_ratio 		        =   $width / $original_width;
		$y_ratio 		        =   $height / $original_height;
		$ratio       	        =   max($x_ratio, $y_ratio);
		$use_x_ratio 	        =   ($x_ratio === $ratio);
		$new_width   = $use_x_ratio  ? $width     : floor($original_width * $ratio);
		$new_height  = !$use_x_ratio ? $height     : floor($original_height * $ratio);
		self::$thumbnail->resizeImage($new_width, $new_height, \Imagick::FILTER_LANCZOS,1);
	}

	/**
	 * @param array $option
	 */
	private static function crop(array $option = Array())
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