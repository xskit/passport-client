<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/7
 * Time: 15:24
 */

namespace XsPkg\PassportClient\Grant;

use Illuminate\Support\Arr;
use XsPkg\PassportClient\Contracts\ShouldAccessTokenContract;
use XsPkg\PassportClient\Http\HttpRequest;

/**
 * 机器对机器授权
 * Class Machine
 * @package XsPkg\ClientFacade\Grant
 */
class Machine implements ShouldAccessTokenContract
{
    private $baseUrl;

    private $config;

    public function __construct($base_url, $config)
    {
        $this->baseUrl = $base_url;
        $this->config = $config;
    }

    public function accessToken()
    {
        $client = new HttpRequest($this->baseUrl . Arr::get($this->config, 'query'));
        return $client->param([
            'grant_type' => 'client_credentials',
            'client_id' => Arr::get($this->config, 'client_id'),
            'client_secret' => Arr::get($this->config, 'client_secret'),
            'scope' => Arr::get($this->config, 'password.scope', '*'),
        ])->post();
    }

}