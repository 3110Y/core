<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 20.04.18
 * Time: 13:17
 */
namespace Core\Autoloader\Tests;


use Core\Autoloader\Autoloader;


/**
 * Class mockAutoloader
 *
 * @package core\autoloader
 */
class MockAutoloader extends Autoloader
{
    protected $files = array();

    public function setFiles(array $files): void
    {
        $this->files = $files;
    }

    protected function requireFile($file): bool
    {
        return \in_array($file, $this->files, true);
    }

}