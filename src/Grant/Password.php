<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/7
 * Time: 15:01
 */

namespace XsPkg\PassportClient\Grant;

use Illuminate\Support\Arr;
use XsPkg\PassportClient\Contracts\ShouldAccessTokenContract;
use XsPkg\PassportClient\Contracts\HttpResponseContract;
use XsPkg\PassportClient\Http\HttpRequest;

/**
 * Class Password
 * @package XsPkg\PassportClient\Grant
 */
class Password implements ShouldAccessTokenContract
{
    private $baseUrl;

    private $config;

    private $account;

    private $password;

    public function __construct($base_url, $config)
    {
        $this->baseUrl = $base_url;
        $this->config = $config;
    }

    /**
     * 授权 账号密码
     * @param string $account 账号
     * @param string $password 密码
     */
    public function signIn($account, $password)
    {
        $this->account = $account;

        $this->password = $password;
    }

    /**
     * 返回访问令牌 使用授权码换访问令牌
     * @return HttpResponseContract
     */
    public function accessToken()
    {
        $client = new HttpRequest($this->baseUrl . 'oauth/token');
        return $client->param([
            'grant_type' => 'password',
            'client_id' => Arr::get($this->config, 'client_id'),
            'client_secret' => Arr::get($this->config, 'client_secret'),
            'username' => $this->account,
            'password' => $this->password,
            'scope' => Arr::get($this->config, 'password.scope', '*'),
        ])->post();
    }
}