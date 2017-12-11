<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 11.12.2017
 * Time: 11:55
 */

namespace core\component\CForm;



/**
 * Class AButton
 * @package core\component\CForm
 */
class AButton extends ACForm
{


    /**
     * @var array
     */
    protected $configButton = Array();

    /**
     * @var string
     */
    protected $answer = '';

    /**
     * @var string
     */
    protected $style = '';

    /**
     * @var string
     */
    protected $class = '';

    /**
     * @var string
     */
    protected $template = 'template/template.tpl';

    /**
     * @var string
     */
    protected $idButton = '';

    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var int
     */
    protected static $iterator = 0;

    /**
     * @var string
     */
    protected  $icon = '';

    /**
     * @var string
     */
    protected  $title = '';

    /**
     * @var string
     */
    protected  $text = '';

    /**
     * @var array
     */
    protected $row = Array();


    /**
     * AButton constructor.
     * @param $button
     * @param $row
     */
    public function __construct($button, $row)
    {
        self::$iterator++;
        $this->row          =   $row;
        $this->idButton     =   $button['field']        ?? 'button_' . __CLASS__ . '_' .  self::$iterator . '_' .  uniqid();
        $this->url          =   $button['url']          ?? '#';
        $this->title        =   $button['title']        ?? '';
        $this->text         =   $button['text']         ?? '';
        if (isset($button['icon'])) {
            $this->icon     =   "<span uk-icon='icon: {$button['icon']}'></span>";
        }

        if (isset($button['class'])) {
            if (!is_array($button['class'])) {
                $this->class        =   $button['class'];
            } else {
                $this->class        =   implode(' ', $button['class']);
            }
        }
        if (isset($button['style'])) {
            $style  =   '';
            foreach ($button['style'] as $name => $value) {
                $style .= "{$name}: {$value}";
            }
            $this->style = " style='{$style}' ";
        }
        unset($button['url'], $button['class'], $button['style'], $button['icon'], $button['title'], $button['text']);
        $this->configButton         =   $button;
    }


    /**
     * @return mixed|string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    public function getButton()
    {
        $this->configButton['title']     =      $this->title;
        $this->configButton['icon']      =      $this->icon;
        $this->configButton['id']        =      $this->idButton;
        $this->configButton['url']       =      $this->url;
        $this->configButton['class']     =      $this->class;
        $this->configButton['style']     =      $this->style;
        return $this->configButton;
    }

}