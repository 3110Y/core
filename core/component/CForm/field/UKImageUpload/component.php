<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 16.12.2017
 * Time: 19:12
 */

namespace core\component\CForm\field\UKImageUpload;

use \core\component\{
    CForm,
    templateEngine\engine\simpleView,
    fileCache\fileCache,
    library as library,
    image\component as image
};


/**
 * Class component
 * @package core\component\CForm\field\UKImageUpload
 */
class component extends CForm\AField implements CForm\IField
{
    /**
     * @const float Версия
     */
    const VERSION   =   2.0;

    /**
     * @var string
     */
    private $path = '';

    /**
     * @var array
     */
    private $option = Array();

    private $templatePhoto = '';

    public function init()
    {
        parent::init();
        if (isset($this->configField['path'])) {
            $this->path = $this->configField['path'];
            unset($this->configField['path']);
        } else {
            $this->path = $this->idField;
        }
        fileCache::checkDir($this->path);
        $data['PARENT_URL']             =   parent::$id;
        $data['URL']                    =   self::$controller::getPageURL();
        $data['TD']                     =   '';
        $data['GRID']                   =   1;
        $data['PLACEHOLDER']            =   '';
        foreach ($this->configField as $key =>  $field) {
            $data[mb_strtoupper($key)] =  $field;
        }
        foreach ($this->row as $key =>  $field) {
            $data['ROW_' . mb_strtoupper($key)] =  $field;
        }
        $data['VALUE']          =   $this->value;
        $data['MODE_FIELD']     =   $this->modeField;
        $data['LABEL']          =   $this->labelField['TEXT'];
        $data['STYLE']          =   $this->style;
        $data['CLASS']          =   $this->class;
        $data['ID']             =   $this->idField;
        $data['HREF']           =   isset($data['HREF'])        ?  "<a href='{$data['HREF']}'"    :  '<span>';
        $data['HREF_TWO']       =   $data['HREF'] == '<span>'   ?    '</span>'                    :   '</a>';
        $data['HREF']           =   simpleView\component::replace(false, $data, $data['HREF']);

        $data['IMG']            =   image::image($this->value, $this->option);
        $data['IMG_BIG']        =   $this->value;

        //UIkitUpload
        /** @var \core\component\library\vendor\UIkitUpload\component $UIkitUpload */
        $UIkitUpload    =   library\component::connect('UIkitUpload');
        $UIkitUpload->setCss(self::$controller);
        $UIkitUpload->setJS(self::$controller);
        $data['INIT']           =   $UIkitUpload->returnInit($data);

        $data['VALUE']          =   simpleView\component::replace(self::getTemplate($this->templatePhoto, __DIR__), $data);
        $this->answer           =   simpleView\component::replace($this->template, $data);
    }

    public function view()
    {
        $this->option = Array(
            Array(
                'action'    => 'adapriveResizeMax',
                'width'     => '100',
                'height'    => '100'
            ),
        );
        $this->templatePhoto = 'template/photo_view.tpl';
        $this->template         =   self::getTemplate('template/view.tpl', __DIR__);

    }

    public function save($id = 0)
    {
        var_dump($id);
        die('fghfhgf');

    }

    public function edit()
    {
        $this->option = Array(
            Array(
                'action'    => 'adapriveResizeMin',
                'width'     => '200',
                'height'    => '200'
            ),
            Array(
                'action'    => 'crop',
                'width'     => '200',
                'height'    => '200'
            ),
        );
        $this->templatePhoto    =   'template/photo_view.tpl';
        if ($this->value == '') {
            $this->templatePhoto = 'template/photo_no.tpl';
        } else {
            $this->templatePhoto = 'template/photo.tpl';
        }
        $this->template     =   self::getTemplate('template/edit.tpl', __DIR__);

    }

    public function preInsert()
    {
        return $this->required && $this->value === '';
    }

    public function preUpdate()
    {
        return $this->required && $this->value === '';
    }
}