<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/7
 * Time: 9:58
 */

namespace XsKit\PassportClient\Http;

use GuzzleHttp\Client as Http;
use Illuminate\Support\Arr;
use Psr\Http\Message\RequestInterface;
use XsKit\PassportClient\ClientOptions;
use XsKit\PassportClient\Exceptions\HttpRequestException;

abstract class AbstractRequest
{
    protected $baseUri;

    protected $guzzleOptions;

    protected $http;

    protected $query;

    protected $param;

    /** @var RequestInterface $request */
    protected $request;

    /** @var ClientOptions $options */
    protected $options;

    /**
     * AbstractRequest constructor.
     * @param ClientOptions $options
     * @param array $guzzle
     */
    public function __construct(ClientOptions $options, array $guzzle = [])
    {
        $this->options = $options;
        $this->baseUri = $options->getBaseUri();
        $setting_guzzle = $options->getGuzzleOptions();
        $guzzle = [
                'base_uri' => $this->baseUri
            ] + $setting_guzzle + $guzzle;

        $this->http = new Http($guzzle);
    }

    public function requestPsr7(RequestInterface $request)
    {
        $this->request = $request;
        return $this;
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