<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/7
 * Time: 9:48
 */

namespace XsKit\PassportClient\Contracts;


use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;

interface HttpRequestAsyncContract
{

    /**
     * 使用psr-7 对象请求
     * @param RequestInterface $request
     * @return mixed
     */
    public function requestPsr7(RequestInterface $request);

    /**
     * 设置基本地址
     * @param $base_uri
     * @return $this
     */
    public function baseUri($base_uri);


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

    /**
     * @param callable|null $onFulfilled
     * @param callable|null $onRejected
     * @param string $method
     * @return $this
     */
    public function send(callable $onFulfilled = null, callable $onRejected = null, $method = '');
}