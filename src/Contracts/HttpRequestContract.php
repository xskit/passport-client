<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/6
 * Time: 14:44
 */

namespace XsPkg\PassportClient\Contracts;

/**
 * Interface HttpRequestContract
 * @package XsPkg\PassportClient\Contracts
 */
interface HttpRequestContract
{
    public function query($value);

    public function params(array $value);

    public function token($value);

    public function get(): HttpResponseContract;

    public function post(): HttpResponseContract;

    public function put(): HttpResponseContract;

    public function delete(): HttpResponseContract;

    public function options(): HttpResponseContract;

    public function head(): HttpResponseContract;

    public function patch(): HttpResponseContract;
}