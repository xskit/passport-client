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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Psr\Http\Message\MessageInterface;
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

    protected static $responseHandle;

    public static function setResponseHandle(\Closure $closure)
    {
        self::$responseHandle = $closure;
    }

    public function receive(ResponseInterface $response)
    {
        $this->response = $response;

        $this->code = $response->getStatusCode();
        $this->message = $response->getReasonPhrase();

        $this->body = $body = (string)$response->getBody();

        //解析JSON
        if ($data = json_decode($body, true)) {
            $this->data = Arr::wrap(empty($data) ? null : $data);
        }

        //处理自定义响应配置
        $closure = Config::get('passport_client.response_handle', function (ResponseInterface $response) {
            if (isset($this->data['data'], $this->data['message'], $this->data['code'])) {
                $this->data = $this->data['data'];
                $this->code = $this->data['code'];
                $this->message = $this->data['message'];
            }
        });
        if ($closure instanceof \Closure) {
            $closure->call($this, $response);
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
     * 返回 数据体
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * 返回 状态码
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * 返回 消息
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * 返回 数据
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
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
     * 返回 转换后的数据集合
     * @return Collection
     */
    public function toCollection(): Collection
    {
        return Collection::make($this->toArray());
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

    public function offsetExists($offset)
    {
        return Arr::has($this->toArray(), $offset);
    }

    public function offsetSet($offset, $value)
    {
        if (is_array($this->data)) {
            $this->data[$offset] = $value;
        }
    }

    public function offsetGet($offset)
    {
        return Arr::get($this->toArray(), $offset);
    }

    public function offsetUnset($offset)
    {
        if (is_array($this->data)) {
            Arr::forget($this->data, $offset);
        }
    }
}