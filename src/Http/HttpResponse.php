<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/6
 * Time: 16:39
 */

namespace XsPkg\PassportClient\Http;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use XsPkg\PassportClient\Contracts\HttpResponseContract;

class HttpResponse implements HttpResponseContract
{

    /**
     * @var Response
     */
    protected $response;

    protected $exception;

    protected $message;

    protected $code;

    protected $body;

    protected $data;

    public function receive(ResponseInterface $response)
    {
        $this->response = $response;

        $this->body = (string)$response->getBody();
        //解析JSON
        if ($data = json_decode($this->body, true)) {
            $this->data = Arr::wrap(empty($this->body) ? null : $data);
        }

        if (isset($this->data['data'], $this->data['message'], $this->data['code'])) {
            $this->data = $this->data['data'];
            $this->code = $this->data['code'];
            $this->message = $this->data['message'];
        } else {
            $this->code = $response->getStatusCode();
            $this->message = $response->getReasonPhrase();
        }

        return $this;
    }

    public function throwException($e)
    {
        $this->handleException($e);
        return $this;
    }

    protected function handleException($e)
    {
        $this->exception = $e;
        if ($e instanceof ConnectException) {
            if ($e->hasResponse()) {
                $this->message = $e->getMessage();
            } else {
                $this->message = '网络错误';
            }
            $this->code = $e->getCode();
        } elseif ($e instanceof TransferException) {
            $this->code = $e->getCode();
            $this->message = $e->getMessage();
        } elseif ($e instanceof \Throwable) {
            $this->code = $e->getCode();
            $this->message = $e->getMessage();
        } else {
            $this->message = '未知异常';
            $this->code = 0;
        }
    }

    /**
     * @return Response|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return TransferException|null
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * 返回 状态码
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * 返回 消息
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * 判断请求
     * @param callable|null $closure
     * @param bool $direction
     * @return bool
     */
    protected function is(callable $closure = null, $direction = true): bool
    {
        $status = $this->exception ? false : Str::startsWith($this->response->getStatusCode(), ['20', '30']);

        if ($status && $closure) {
            //请求成功,执行回调进一步判断
            $status = call_user_func($closure, $this);
        }

        return $direction ? $status : !$status;
    }

    /**
     * 请求是否成功
     * @param callable|null $closure
     * @return bool
     */
    public function isOk(callable $closure = null): bool
    {
        return $this->is($closure, true);
    }

    /**
     * 请求是否失败
     * @param callable|null $closure
     * @return bool
     */
    public function isErr(callable $closure = null): bool
    {
        return $this->is($closure, false);
    }

    /**
     * 返回 Json 转 Array 的数据
     * @return array
     */
    public function toArray()
    {
        return empty($this->data) ? [] : Arr::wrap($this->data);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }
}