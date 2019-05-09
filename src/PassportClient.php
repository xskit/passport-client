<?php

namespace XsPkg\PassportClient;

use Illuminate\Support\Arr;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use XsPkg\PassportClient\Contracts\ApiContract;
use XsPkg\PassportClient\Contracts\HttpRequestAsyncContract;
use XsPkg\PassportClient\Contracts\HttpRequestContract;
use XsPkg\PassportClient\Contracts\HttpResponseContract;
use XsPkg\PassportClient\Contracts\ShouldRefreshTokenContract;
use XsPkg\PassportClient\Grant\Authorize;
use XsPkg\PassportClient\Grant\Machine;
use XsPkg\PassportClient\Grant\Password;
use XsPkg\PassportClient\Http\HttpRequest;
use XsPkg\PassportClient\Http\HttpRequestAsync;

/**
 * Class Client
 * @package XsPkg\Client
 */
class PassportClient implements ShouldRefreshTokenContract
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
     * @return Client
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
    public function request(ApiContract $api, array $guzzle = []): HttpRequestContract
    {
        if ($name = $api->driver()) {
            //修改驱动
            $this->driver($name);
        }
        $http = new HttpRequest($this->getBaseUri(), $guzzle);

        return $http->query($api->query())
            ->param($api->param())
            ->token($api->token());
    }

    /**
     * 发启异步请求
     * @param ApiContract $api 接口对象
     * @param array $guzzle
     * @return HttpRequestAsyncContract
     */
    public function requestAsync(ApiContract $api, array $guzzle = []): HttpRequestAsyncContract
    {
        if ($name = $api->driver()) {
            //修改驱动
            $this->driver($name);
        }
        $http = new HttpRequestAsync($this->getBaseUri(), $guzzle);

        return $http->query($api->query())
            ->param($api->param())
            ->token($api->token());
    }

    /**
     * 发送请求
     * @param RequestInterface $request Psr-7 Request 对象
     * @param array $guzzle
     * @return HttpResponseContract
     */
    public function send(RequestInterface $request, array $guzzle = []): HttpResponseContract
    {
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
        return (new HttpRequestAsync($request, $guzzle))->sendAsync($onFulfilled, $onRejected)->promise();
    }

}