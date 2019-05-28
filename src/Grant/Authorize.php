<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/6
 * Time: 16:44
 */

namespace XsKit\PassportClient\Grant;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use XsKit\PassportClient\Client;
use XsKit\PassportClient\ClientOptions;
use XsKit\PassportClient\Contracts\ShouldAccessTokenContract;
use XsKit\PassportClient\Contracts\HttpResponseContract;
use XsKit\PassportClient\Http\HttpRequest;

/**
 * 访问令牌授权
 * Class Authorize
 * @package XsKit\ClientFacade\Grant
 */
class Authorize implements ShouldAccessTokenContract
{
    private $options;

    private $config;

    private $code;

    public function __construct(ClientOptions $options)
    {
        $this->options = $options;

        $this->config = $options->getAll();
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
        $client = new HttpRequest($this->options);
        return $client->query(Arr::get($this->config, 'query'))->param([
            'grant_type' => 'authorization_code',
            'client_id' => Arr::get($this->config, 'authorize_grant.client_id'),
            'client_secret' => Arr::get($this->config, 'authorize_grant.client_secret'),
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
        return Redirect::to($this->options->getBaseUri() . Arr::get($this->config, 'authorize_redirect') . '?' . http_build_query([
                'client_id' => Arr::get($this->config, 'authorize_grant.client_id'),
                'redirect_uri' => Arr::get($this->config, 'authorize_grant.redirect_uri'),
                'response_type' => $implicit ? 'token' : 'code',
                'scope' => Arr::get($this->config, 'authorize_grant.scope', ''),
            ]));
    }
}