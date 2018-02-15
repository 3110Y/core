<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 19:05
 */

namespace application\client;

use \core\application\{
    ARouter,
    IRouter
};


final class router extends ARouter implements IRouter
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
    protected $redirectPage = '404';

}