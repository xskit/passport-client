<?php

namespace XsKit\PassportClient;

use Illuminate\Support\Arr;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use XsKit\PassportClient\Contracts\ApiContract;
use XsKit\PassportClient\Contracts\HttpRequestAsyncContract;
use XsKit\PassportClient\Contracts\HttpRequestContract;
use XsKit\PassportClient\Contracts\HttpResponseContract;
use XsKit\PassportClient\Contracts\ShouldRefreshTokenContract;
use XsKit\PassportClient\Exceptions\HttpRequestException;
use XsKit\PassportClient\Grant\Authorize;
use XsKit\PassportClient\Grant\Machine;
use XsKit\PassportClient\Grant\Password;
use XsKit\PassportClient\Http\HttpRequest;
use XsKit\PassportClient\Http\HttpRequestAsync;

/**
 * Class Client
 * @package XsKit\PassportClient
 */
class Client implements ShouldRefreshTokenContract
{

    private $config;

    private $driver;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 设置 驱动
     * @param string $name
     * @return $this
     */
    public function driver($name)
    {
        $this->driver = $name;
        return $this;
    }

    public function getDriver()
    {
        return $this->driver ?? $this->config['default'];
    }

    public function getBaseUri(): string
    {
        return rtrim(Arr::get($this->getConfig(), 'base_uri'), '/');
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return Arr::get($this->config, $this->getDriver(), []);
    }

    /**
     * 授权码授权
     * @return Authorize
     */
    public function grantAuthorize()
    {
        return new Authorize($this->getBaseUri(), $this->getConfig());
    }

    /**
     * 机器授权
     * @return Machine
     */
    public function grantMachine()
    {
        return new Machine($this->getBaseUri(), $this->getConfig());
    }

    /**
     * 密码授权
     * @return Password
     */
    public function grantPassword()
    {
        return new Password($this->getBaseUri(), $this->getConfig());
    }

    /**
     * 刷新访问令牌
     * @param string $token
     * @return HttpResponseContract
     */
    public function refreshToken($token)
    {
        $client = new HttpRequest($this->getBaseUri() . Arr::get($this->getConfig(), 'query'));
        return $client->param([
            'grant_type' => 'refresh_token',
            'refresh_token' => $token,
            'client_id' => Arr::get($this->getConfig(), 'client_id'),
            'client_secret' => Arr::get($this->getConfig(), 'client_secret'),
            'scope' => '',
        ])->post();
    }

    /**
     * 发启请求
     * @param ApiContract $api 接口对象
     * @param array $guzzle
     * @return HttpRequestContract
     */
    public function request(ApiContract $api = null, array $guzzle = []): HttpRequestContract
    {
        if (empty($api)) {
            return new HttpRequest($this->getBaseUri(), $guzzle);
        }
        return $this->handleApi(new HttpRequest($api->baseUri() ?? $this->getBaseUri(), $guzzle), $api);
    }

    /**
     * 发启异步请求
     * @param ApiContract $api 接口对象
     * @param array $guzzle
     * @return HttpRequestAsyncContract
     */
    public function requestAsync(ApiContract $api = null, array $guzzle = []): HttpRequestAsyncContract
    {
        if (empty($api)) {
            return new HttpRequestAsync($this->getBaseUri(), $guzzle);
        }

        return $this->handleApi(new HttpRequestAsync($api->baseUri() ?? $this->getBaseUri(), $guzzle), $api);
    }

    /**
     * 使用 api实例 发启请求
     * @param ApiContract $api
     * @param array $guzzle
     * @return HttpResponseContract
     */
    public function api(ApiContract $api, array $guzzle = []): HttpResponseContract
    {
        if (empty($api)) {
            throw new HttpRequestException('The required ApiContract instance is missing');
        }
        return $this->handleApi(new HttpRequest($api->baseUri() ?? $this->getBaseUri(), $guzzle), $api)->send($api->method());
    }

    /**
     * 使用 api实例 发启异步请求
     * @param ApiContract $api Psr-7 Request 对象
     * @param callable $onFulfilled
     * @param callable $onRejected
     * @param array $guzzle
     * @return PromiseInterface
     */
    public function apiAsync(ApiContract $api, $onFulfilled, $onRejected, array $guzzle = []): PromiseInterface
    {
        if (empty($api)) {
            throw new HttpRequestException('The required ApiContract instance is missing');
        }
        return $this->handleApi(new HttpRequestAsync($api->baseUri() ?? $this->getBaseUri(), $guzzle), $api)
            ->send($onFulfilled, $onRejected, $api->method())
            ->promise();

    }


    /**
     * 处理 API请求实例
     * @param HttpRequestAsync|HttpRequest $http
     * @param ApiContract $api
     * @return HttpRequestAsync|HttpRequest
     */
    private function handleApi($http, ApiContract $api)
    {
        if (empty($api)) {
            return $http;
        }

        //修改驱动
        $api->driver() && $this->driver($api->driver());

        $api->query() && $http->query($api->query());

        $api->param() && $http->param($api->param());

        $api->token() && $http->token($api->token());

        return $http;
    }

    /**
     * 发送请求
     * @param RequestInterface $request Psr-7 Request 对象
     * @param array $guzzle
     * @return HttpResponseContract
     */
    public function send(RequestInterface $request, array $guzzle = []): HttpResponseContract
    {
        if (empty($request)) {
            throw new HttpRequestException('The required RequestInterface instance is missing');
        }
        return (new HttpRequest($request, $guzzle))->send();
    }

    /**
     * 发送请求
     * @param RequestInterface $request Psr-7 Request 对象
     * @param $onFulfilled
     * @param $onRejected
     * @param array $guzzle
     * @return PromiseInterface
     */
    public function sendAsync(RequestInterface $request, $onFulfilled, $onRejected, array $guzzle = []): PromiseInterface
    {
        if (empty($request)) {
            throw new HttpRequestException('The required RequestInterface instance is missing');
        }
        return (new HttpRequestAsync($request, $guzzle))->send($onFulfilled, $onRejected)->promise();
    }

}