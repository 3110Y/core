<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 19:05
 */

namespace application\client;

use \core\component\application\handler\Web as applicationWeb;


final class router extends applicationWeb\ARouter implements applicationWeb\IRouter
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