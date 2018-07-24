<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 19:05
 */

namespace application\cron;

use \core\component\application\{
    ARouter,
    IRouter
};


final class router extends ARouter implements IRouter
{
    /** @var mixed table */
    protected $table = 'cron_page';

    /** @var mixed configDB */
    protected $redirectPage = 'error';

}