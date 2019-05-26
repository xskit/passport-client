<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/22
 * Time: 9:08
 */

namespace XsKit\PassportClient\Http;


use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use XsKit\PassportClient\Contracts\ResponseHandleContract;

class ResponseHandle implements ResponseHandleContract
{
    public static function parseData(): \Closure
    {
        return function (ResponseInterface $response) {
            $this->code = Arr::get($this->data, 'code', $response->getStatusCode());
            $this->message = Arr::get($this->data, 'message',  $response->getReasonPhrase());
            $this->data = Arr::get($this->data, 'data', $this->data);
        };
    }
}