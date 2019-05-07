<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/6
 * Time: 16:44
 */

namespace XsPkg\PassportClient\Grant;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use XsPkg\PassportClient\Contracts\ShouldAccessTokenContract;
use XsPkg\PassportClient\Contracts\HttpResponseContract;
use XsPkg\PassportClient\Http\HttpRequest;

/**
 * 访问令牌授权
 * Class Authorize
 * @package XsPkg\PassportClient\Grant
 */
class Authorize implements ShouldAccessTokenContract
{

    private $baseUrl;

    private $config;

    private $code;

    public function __construct($base_url, $config)
    {
        $this->baseUrl = $base_url;
        $this->config = $config;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * 返回访问令牌 使用授权码换访问令牌
     * @return HttpResponseContract
     */
    public function accessToken()
    {
        $client = new HttpRequest($this->baseUrl . Arr::get($this->config, 'query'));
        return $client->param([
            'grant_type' => 'authorization_code',
            'client_id' => Arr::get($this->config, 'client_id'),
            'client_secret' => Arr::get($this->config, 'client_secret'),
            'redirect_uri' => Arr::get($this->config, 'authorize_grant.redirect_uri'),
            'code' => $this->code,
        ])->post();
    }

    /**
     * 授权时的重定向
     * @param bool $implicit 是否隐式授权
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect($implicit = false)
    {
        return Redirect::to($this->baseUrl . 'oauth/authorize?' . http_build_query([
                'client_id' => Arr::get($this->config, 'client_id'),
                'redirect_uri' => Arr::get($this->config, 'redirect_uri'),
                'response_type' => $implicit ? 'token' : 'code',
                'scope' => Arr::get($this->config, 'scope'),
            ]));
    }
}