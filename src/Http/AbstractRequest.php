<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/7
 * Time: 9:58
 */

namespace XsPkg\PassportClient\Http;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Psr\Http\Message\RequestInterface;
use XsPkg\PassportClient\Exceptions\HttpRequestException;

abstract class AbstractRequest
{
    protected $baseUri;

    protected $guzzleOptions;

    protected $client;

    protected $query;

    protected $param;

    /**
     * @var $request
     */
    protected $request;

    public function __construct($request, array $guzzle = [])
    {
        if (is_string($request)) {
            $this->baseUri = $request;
            $guzzle = [
                    'base_uri' => $this->baseUri
                ] + $guzzle;
        } elseif ($request instanceof RequestInterface) {
            $this->request = $request;
        } else {
            throw new HttpRequestException('The requested parameters  [' . $request . '] are not supported');
        }

        $this->client = new Client($guzzle);
    }

    /**
     * 设置基本地址
     * @param $base_uri
     * @return $this
     */
    public function baseUri($base_uri)
    {
        $this->baseUri = $base_uri;
        return $this;
    }

    /**
     * 设置路由
     * @param $url
     * @return $this
     */
    public function query($url)
    {
        $this->query = $url;
        return $this;
    }

    /**
     * 设置查询参数
     * @param string|array|null $key
     * @param array $value
     * @return $this
     */
    public function param($key, $value = null)
    {
        if (is_array($key) && is_null($value)) {
            $this->param = $key;
        } else {
            if (empty($key)) {
                $this->param = [];
            } else {
                Arr::set($this->param, $key, $value);
            }
        }
        return $this;
    }

    /**
     * 设置访问凭证
     * @param $value
     * @return $this
     */
    public function token($value)
    {
        if ($value) {
            $this->guzzleOptions['headers'] = [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $value,
            ];
        } else {
            throw new HttpRequestException('An empty access certificate is set');
        }
        return $this;
    }
}