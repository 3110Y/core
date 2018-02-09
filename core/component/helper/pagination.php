<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 16.01.2018
 * Time: 19:48
 */

namespace core\component\helper;

/**
 * Trait pagination
 * @package core\component\helper
 * @version 1.1
 */
trait pagination
{
    /**
     * Добавляет пагинатор
     *
     * @param string $getPageURL путь
     * @param int $pageNow нынешняя страница
     * @param int $count всего записей
     * @param int $onPage на странице
     * @param string $right стрелка вправо
     * @param string $left стрелка влево
     *
     * @example class myClass { use \core\component\helper\pagination; }
     *
     * @access public
     * @static
     *
     * @return array|bool array - результат, false - нет такой страницы
     */
    public static function getPagination(string $getPageURL, $pageNow = 1, int $count, int $onPage = 10, string $right = '>', string $left = '<')
    {
        $totalPage   =  ceil($count / $onPage);
        if (!ctype_digit((string) $pageNow)) return false;
        $pageNow = (int) $pageNow;
        if ($pageNow < 1) {
            return false;
        }
        if ($totalPage == 0) {
            return Array();
        } elseif ($pageNow > $totalPage) {
            return false;
        }
        $page = Array();
        if ($totalPage  <= 7) {
            for ($i=1; $i <= $totalPage; $i++) {
                $page[$i] = Array(
                    'LINK'      =>  $getPageURL . '/' . $i,
                    'I'         =>  $i,
                    'ACTIVE'    =>  $i == $pageNow    ?  'active' :   ''
                );
            }
        } elseif ($pageNow <= 4) {
            for ($i=1; $i <= 5; $i++) {
                $page[] = Array(
                    'LINK'          =>   $getPageURL . '/' . $i ,
                    'I'             =>  $i,
                    'ACTIVE'        =>  $i == $pageNow    ?  'active' :   ''
                );
            }
            $page[] = Array(
                'LINK'          =>  '#',
                'I'             =>  '...',
                'ACTIVE'        =>  ''
            );
            $page[] = Array(
                'LINK'          =>  $getPageURL . '/' . $totalPage ,
                'I'             =>  $totalPage,
                'ACTIVE'        =>  ''
            );
            $page[] = Array(
                'LINK'      =>  $getPageURL . '/' . ($pageNow + 1) ,
                'I'         =>  $right,
                'ACTIVE'    =>  ''
            );
        } elseif ($pageNow >= $totalPage - 3) {
            $page[] = Array(
                'LINK'      =>  $getPageURL . '/' . ($pageNow - 1) ,
                'I'         =>  $left,
                'ACTIVE'    =>  ''
            );
            $page[] = Array(
                'LINK'          =>  $getPageURL . '/1' ,
                'I'             =>  1,
                'ACTIVE'        =>  ''
            );
            $page[] = Array(
                'LINK'          =>  '#',
                'I'             =>  '...',
                'ACTIVE'        =>  ''
            );
            for ($i=$totalPage -4; $i <= $totalPage ; $i++) {
                $page[] = Array(
                    'LINK'          =>  $getPageURL . '/' . $i ,
                    'I'             =>  $i,
                    'ACTIVE'        =>  $i == $pageNow    ?  'active' :   ''
                );
            }
        } else {
            $page[] = Array(
                'LINK'      =>   $getPageURL . '/' . ($pageNow - 1) ,
                'I'         =>  $left,
                'ACTIVE'    =>  ''
            );
            $page[] = Array(
                'LINK'          =>  $getPageURL . '/1' ,
                'I'             =>  1,
                'ACTIVE'        =>  ''
            );
            $page[] = Array(
                'LINK'          =>  '#',
                'I'             =>  '...',
                'ACTIVE'        =>  ''
            );
            for ($i=$pageNow - 2; $i <= $pageNow + 2; $i++) {
                $page[] = Array(
                    'LINK'          =>  $getPageURL . '/' . $i  ,
                    'I'             =>  $i,
                    'ACTIVE'        =>  $i == $pageNow    ?  'active' :   ''
                );
            }
            $page[] = Array(
                'LINK'          =>  '#',
                'I'             =>  '...',
                'ACTIVE'        =>  ''
            );
            $page[] = Array(
                'LINK'          =>  $getPageURL . '/' . $totalPage  ,
                'I'             =>  $totalPage,
                'ACTIVE'        =>  ''
            );
            $page[] = Array(
                'LINK'      =>  $getPageURL . '/' . ($pageNow + 1)  ,
                'I'         =>  $right,
                'ACTIVE'    =>  ''
            );
        }
        return $page;
    }
}