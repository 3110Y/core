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
    public $table = 'admin_page';

    /**
     * @var mixed fields
     */
    public $fields = '*';

    /**
     * @var mixed where
     */
    public $where = Array(
        'status' => '1'
    );

    /**
     * @var mixed order
     */
    public $order = '`order_in_menu` ASC';

    /**
     * @var mixed configDB
     */
    public $configDB = 'db.common';

    /**
     * @var mixed configDB
     */
    public $redirectPage = 'enter';

}
