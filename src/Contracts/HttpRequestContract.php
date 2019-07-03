<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/6
 * Time: 14:44
 */

namespace XsKit\PassportClient\Contracts;

use Psr\Http\Message\RequestInterface;

/**
 * Interface HttpRequestContract
 * @package XsKit\PassportClient\Contracts
 */
interface HttpRequestContract
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
     * 设置查询url
     * @param string $value
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
     * 添加请求头
     * @param array $value
     * @return $this
     */
    public function withHeaders(array $value);

    /**
     * 设置请求头
     * @param array $value
     * @return $this
     */
    public function setHeaders(array $value);

    /**
     * @param string $value
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

    /**
     * @param $method
     * @return HttpResponseContract
     */
    public function send($method = ''): HttpResponseContract;

}