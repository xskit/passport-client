<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/7
 * Time: 15:14
 */

namespace XsPkg\PassportClient\Contracts;


interface ShouldRefreshTokenContract
{
    /**
     * 刷新访问令牌
     * @param string $token
     * @return HttpResponseContract
     */
    public function refreshToken($token);
}