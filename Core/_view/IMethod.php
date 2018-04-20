<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 11.04.18
 * Time: 15:54
 */

namespace Core\_view;


interface IMethod
{
    /**
     * IMethod constructor.
     * @param string $content
     * @param array $data
     * @param string $template
     */
    public function __construct(string $content, array $data, string $template);

    /**
     * @return void
     */
    public function prepareData(): void;

    /**
     * @return void
     */
    public function prepareTemplate(): void;

    /**
     * @return mixed
     */
    public function getData():  array ;

    /**
     * @return mixed
     */
    public function getContent():   string ;

    /**
     *
     */
    public function render():   void ;
}