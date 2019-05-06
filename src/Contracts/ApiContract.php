<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/6
 * Time: 19:50
 */

namespace XsPkg\PassportClient\Contracts;


interface ApiContract
{
    /**
     * 返回 使用的驱动,为空时，使用默认配置中的驱动
     * @return string|null
     */
    public function useDriver();

    /**
     * 查询地址
     * @return string
     */
    public function query(): string;

    /**
     * 查询参数
     * @return array
     */
    public function params(): array;


}