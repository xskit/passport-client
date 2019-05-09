<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/7
 * Time: 9:48
 */

namespace XsPkg\PassportClient\Contracts;


use GuzzleHttp\Promise\PromiseInterface;

interface HttpRequestAsyncContract
{
    /**
     * @param $value
     * @return $this
     */
    public function query($value);

    /**
     * @param array $value
     * @return $this
     */
    public function param(array $value);

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
    public function getAsync(callable $onFulfilled = null, callable $onRejected = null);

    public function postAsync(callable $onFulfilled = null, callable $onRejected = null);

    public function putAsync(callable $onFulfilled = null, callable $onRejected = null);

    public function deleteAsync(callable $onFulfilled = null, callable $onRejected = null);

    public function optionsAsync(callable $onFulfilled = null, callable $onRejected = null);

    public function headAsync(callable $onFulfilled = null, callable $onRejected = null);

    public function patchAsync(callable $onFulfilled = null, callable $onRejected = null);

    public function promise(): PromiseInterface;
}