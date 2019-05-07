<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/7
 * Time: 14:29
 */

namespace XsPkg\PassportClient;

use Illuminate\Support\Facades\Facade as BasicFacade;

/**
 * Class Facade
 * @package XsPkg\PassportClient
 * @method public function driver($name):$this
 */
class Facade extends BasicFacade
{
    protected static function getFacadeAccessor()
    {
        return PassportClient::class;
    }
}