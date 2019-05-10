<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/6
 * Time: 16:44
 */

namespace XsKit\PassportClient\Contracts;


interface ShouldAccessTokenContract
{
    /**
     * 返回访问令牌
     * @return HttpResponseContract
     */
    public function accessToken();

}