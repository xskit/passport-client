<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/7
 * Time: 9:48
 */

namespace XsKit\PassportClient\Contracts;


use GuzzleHttp\Promise\PromiseInterface;

interface HttpRequestAsyncContract
{
    /**
     * @param $value
     * @return $this
     */
    public function query($value);

    /**
     * 设置查询参数
     * @param string|array $key
     * @param array $value
     * @return $this
     */
    public function param($key, $value = null);

    /**
     * @param string $value 凭证
     * @return $this
     */
    public function token($value);

    /**
     * @param callable|null $onFulfilled
     * @param callable|null $onRejected
     * @return $this
     */
    public function get(callable $onFulfilled = null, callable $onRejected = null);

    public function post(callable $onFulfilled = null, callable $onRejected = null);

    public function put(callable $onFulfilled = null, callable $onRejected = null);

    public function delete(callable $onFulfilled = null, callable $onRejected = null);

    public function options(callable $onFulfilled = null, callable $onRejected = null);

    public function head(callable $onFulfilled = null, callable $onRejected = null);

    public function patch(callable $onFulfilled = null, callable $onRejected = null);

    public function promise(): PromiseInterface;
}