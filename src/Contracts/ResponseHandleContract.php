<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/22
 * Time: 8:58
 */

namespace XsKit\PassportClient\Contracts;

use Psr\Http\Message\ResponseInterface;

/**
 * 响应的处理
 * Interface ResponseHandleContract
 * @package XsKit\PassportClient\Contracts
 */
interface ResponseHandleContract
{
    /**
     *  返回一个匿名函数,函数内 $this 指向是 XsKit\PassportClient\Http\HttpResponse 实例
     * @return \Closure
     */
    public static function parseData(): \Closure;
}