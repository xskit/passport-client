<?php

namespace XsPkg\PassportClient;

use Illuminate\Support\Arr;
use XsPkg\PassportClient\Contracts\ApiContract;
use XsPkg\PassportClient\Contracts\HttpResponseContract;

/**
 * Class PassportClient
 * @package XsPkg\PassportClient
 */
class PassportClient
{

    private $config;

    private $baseUri;

    public function __construct($config)
    {
        $this->config = $config;
    }

    private function getConfig($driver, $grant)
    {
        $driver_name = $driver ?? $this->config['default'];
        $config = Arr::get($this->config, $driver_name);
        $this->baseUri = Arr::get($config, 'base_uri');
        return Arr::get($config, $grant, []);
    }

    /**
     * 授权码授权
     * @param null $driver
     */
    public function grantAuthorize($driver = null)
    {
        $config = $this->getConfig($driver, 'authorize_grant');

    }

    /**
     * 机器授权
     * @param null $driver
     */
    public function grantMachine($driver = null)
    {
        $config = $this->getConfig($driver, 'machine_grant');

    }

    /**
     * 密码授权
     * @param null $driver
     */
    public function grantPassword($driver = null)
    {
        $config = $this->getConfig($driver, 'password_grant');

    }

    /**
     * 个人令牌授权
     * @param null $driver
     */
    public function grantPersonal($driver = null)
    {
        $config = $this->getConfig($driver, 'personal_grant');

    }

    /**
     * @param ApiContract $api
     * @return HttpResponseContract
     */
    public function invoke(ApiContract $api): HttpResponseContract
    {

    }

}