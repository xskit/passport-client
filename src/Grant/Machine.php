<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/7
 * Time: 15:24
 */

namespace XsKit\PassportClient\Grant;

use Illuminate\Support\Arr;
use XsKit\PassportClient\Contracts\ShouldAccessTokenContract;
use XsKit\PassportClient\Http\HttpRequest;

/**
 * 机器对机器授权
 * Class Machine
 * @package XsKit\ClientFacade\Grant
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
        $client = new HttpRequest($this->baseUrl, Arr::get($this->config, 'guzzle_options', []));
        return $client->query(Arr::get($this->config, 'query'))->param([
            'grant_type' => 'client_credentials',
            'client_id' => Arr::get($this->config, 'machine_grant.client_id'),
            'client_secret' => Arr::get($this->config, 'machine_grant.client_secret'),
            'scope' => Arr::get($this->config, 'machine_grant.scope', '*'),
        ])->post();
    }

}