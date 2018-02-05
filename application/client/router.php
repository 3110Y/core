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
    protected $table = 'client_page';

    /**
     * @var mixed fields
     */
    protected $fields = '*';

    /**
     * @var mixed where
     */
    protected $where = Array(
        'status' => '1'
    );

    /**
     * @var mixed order
     */
    protected $order = '`order_in_menu` ASC';

    /**
     * @var mixed configDB
     */
    protected $configDB = 'db.common';

    /**
     * @var mixed configDB
     */
    protected $redirectPage = '404';

}