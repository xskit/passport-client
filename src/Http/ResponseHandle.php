<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/22
 * Time: 9:08
 */

namespace XsKit\PassportClient\Http;


use Psr\Http\Message\ResponseInterface;
use XsKit\PassportClient\Contracts\ResponseHandleContract;

class ResponseHandle implements ResponseHandleContract
{
    public static function parseData(): \Closure
    {
        return function (ResponseInterface $response) {
            isset($this->data['data']) and $this->data = $this->data['data'];
            isset($this->data['code']) and $this->code = $this->data['code'];
            isset($this->data['message']) and $this->message = $this->data['message'];
        };
    }
}