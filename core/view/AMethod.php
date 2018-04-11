<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 11.04.18
 * Time: 16:06
 */

namespace core\view;


abstract class AMethod
{
    /** @var string */
    protected $content = '';

    /** @var array  */
    protected $data = [];

    /** @var string  */
    protected $template = '';

    /** @var string  */
    protected $path = '';

    /**
     * IMethod constructor.
     * @param string $content
     * @param array $data
     * @param string $template
     */
    public function __construct(string $content, array $data, string $template)
    {
        $this->template =   $template;
        $this->content  =   $content;
        $this->data     =   $data;
        if ($this->template !== '') {
            $this->path =   template::getPath($this->template);
            if ($this->content === '') {
                $this->content = template::toHTML($this->template);
            }
        }
    }

    /**
     *
     */
    public function render(): void
    {}


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
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

}