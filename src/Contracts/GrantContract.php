<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/6
 * Time: 16:44
 */

namespace XsPkg\PassportClient\Contracts;


interface GrantContract
{
    /**
     * 返回访问令牌
     * @return mixed
     */
    public function accessToken();

    /**
     * 刷新访问令牌
     * @return mixed
     */
    public function refreshToken();

}