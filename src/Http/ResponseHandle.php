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
            if (isset($this->data['data'], $this->data['message'], $this->data['code'])) {
                $this->data = $this->data['data'];
                $this->code = $this->data['code'];
                $this->message = $this->data['message'];
            }
        };
    }
}