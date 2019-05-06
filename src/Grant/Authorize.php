<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/6
 * Time: 16:44
 */

namespace XsPkg\PassportClient\Grant;

use XsPkg\PassportClient\Http\HttpRequest;

/**
 * 访问令牌授权
 * Class Authorize
 * @package XsPkg\PassportClient\Grant
 */
class Authorize extends HttpRequest
{

    public function redirect(callable $callback)
    {

    }
}