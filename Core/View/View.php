<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 22.04.18
 * Time: 14:20
 */

namespace Core\View;

use Core\File\File;

class View
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $template;

    /**
     * @var string
     */
    private $content = '';

    /**
     * @var string
     */
    private $dir = '';

    /**
     * @var array
     */
    private $method =   [
        'foreach'           =>  true,
        'for'               =>  true,
        'if'                =>  true,
        'section'           =>  true,
        'include'           =>  true,
        'comment'           =>  true,
        'debug'             =>  true
    ];

    /**
     * @param string $template
     * @param array $data
     * @return view
     */
    public static function view(string $template = '', array $data = []): view
    {
        return new self($template, $data);
    }

    /**
     * view constructor.
     * @param string $template
     * @param array $data
     */
    public function __construct(string $template = '', array $data = [])
    {
        $this->setDir('view');
        $this->setTemplate($template);
        $this->setData($data);
    }

    /**
     * @return string
     */
    public function render():   string
    {
        foreach ($this->method as $class => $active) {
            if ($active && $class instanceof IMethod) {
                /** @var IMethod $class */
                $class       =   new $class($this->content, $this->data, $this->template);
                $class->prepareData();
                $class->prepareTemplate();
                $class->render();
                $this->data     =   $class->getData();
                $this->content  =   $class->getContent();
            }
        }

        return $this->content;
    }


    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template): void
    {
        $this->template = $template;
        if ($this->template !== '') {
            $this->content = template::toHTML($this->template);
        }
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return array
     */
    public function getMethod(): array
    {
        return $this->method;
    }

    /**
     * @param array $method
     */
    public function setMethod(array $method): void
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getDir(): string
    {
        return $this->dir;
    }

    /**
     * @param string $dir
     */
    public function setDir(string $dir): void
    {
        $this->dir = File::getDir($dir);
    }

}