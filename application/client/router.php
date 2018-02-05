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
    public $table = 'client_page';

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
    public $redirectPage = '404';

}