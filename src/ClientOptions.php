<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/28
 * Time: 11:21
 */

namespace XsKit\PassportClient;


use Illuminate\Support\Arr;
use XsKit\PassportClient\Http\ResponseHandle;

class ClientOptions
{

    protected $settings;

    /**
     * ClientOptions constructor.
     * @param array $settings 配置项
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    /**
     * 获取所有配置
     * @return array
     */
    public function getAll()
    {
        return $this->settings;
    }

    /**
     * 获取当前配置的基础uri
     * @return string
     */
    public function getBaseUri(): string
    {
        return rtrim(Arr::get($this->settings, 'base_uri'), '/');
    }

    /**
     * 获取 guzzle http 配置
     * @return array
     */
    public function getGuzzleOptions()
    {
        return Arr::get($this->settings, 'guzzle_options', []);
    }

    /**
     * 获取
     * @return mixed
     */
    public function getResponseHandle()
    {
        return Arr::get($this->settings, 'response_handle');
    }
}