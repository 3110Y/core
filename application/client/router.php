<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 19:05
 */

namespace application\client;

use \core\component\application as application;


final class router extends application\ARouter implements application\IRouter
{
    /**
     * @var mixed table
     */
    protected static $table = 'client_page';

    /**
     * @var mixed fields
     */
    protected static $fields = '*';

    /**
     * @var mixed where
     */
    protected static $where = Array(
        'status' => '1'
    );

    /**
     * @var mixed order
     */
    protected static $order = '`order_in_menu` ASC';

    /**
     * @var mixed configDB
     */
    protected static $configDB = 'db.common';

    /**
     * @var mixed configDB
     */
    protected static $redirectPage = '404';

}