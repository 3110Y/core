<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 14:39
 */

namespace application\admin;


use \core\component\application as application;


/**
 * Class router
 * @package application\admin
 */
final class router extends application\ARouter implements application\IRouter
{
    /**
     * @var mixed table
     */
    protected static $table = 'admin_page';

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
    protected static $redirectPage = 'enter';

}
