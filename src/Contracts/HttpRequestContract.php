<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/6
 * Time: 14:44
 */

namespace XsKit\PassportClient\Contracts;

/**
 * Interface HttpRequestContract
 * @package XsKit\PassportClient\Contracts
 */
interface HttpRequestContract
{
    public function query($value);

    /**
     * 设置查询参数
     * @param string|array $key
     * @param array $value
     * @return $this
     */
    public function param($key, $value = null);

    /**
     * @param $value
     * @return $this
     */
    public function token($value);

    public function get(): HttpResponseContract;

    public function post(): HttpResponseContract;

    public function put(): HttpResponseContract;

    public function delete(): HttpResponseContract;

    public function options(): HttpResponseContract;

    public function head(): HttpResponseContract;

    public function patch(): HttpResponseContract;

    public function upload(): HttpResponseContract;


}