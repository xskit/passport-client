<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/7
 * Time: 9:59
 */

namespace XsPkg\PassportClient\Http;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use XsPkg\PassportClient\Contracts\HttpRequestAsyncContract;
use XsPkg\PassportClient\Exceptions\HttpRequestException;

class HttpRequestAsync extends AbstractRequest implements HttpRequestAsyncContract
{
    protected $promise;

    /**
     * @param callable|null $onFulfilled
     * @param callable|null $onRejected
     * @return $this
     */
    public function get(callable $onFulfilled = null, callable $onRejected = null)
    {
        return $this->send('GET', $onFulfilled, $onRejected);
    }

    /**
     * @param callable|null $onFulfilled
     * @param callable|null $onRejected
     * @return $this
     */
    public function post(callable $onFulfilled = null, callable $onRejected = null)
    {
        return $this->send($onFulfilled, $onRejected, 'GET');
    }

    /**
     * @param callable|null $onFulfilled
     * @param callable|null $onRejected
     * @return $this
     */
    public function put(callable $onFulfilled = null, callable $onRejected = null)
    {
        return $this->send($onFulfilled, $onRejected, 'PUT');
    }

    /**
     * @param callable|null $onFulfilled
     * @param callable|null $onRejected
     * @return $this
     */
    public function delete(callable $onFulfilled = null, callable $onRejected = null)
    {
        return $this->send($onFulfilled, $onRejected, 'DELETE');
    }

    /**
     * @param callable|null $onFulfilled
     * @param callable|null $onRejected
     * @return $this
     */
    public function options(callable $onFulfilled = null, callable $onRejected = null)
    {
        return $this->send($onFulfilled, $onRejected, 'OPTIONS');
    }

    /**
     * @param callable|null $onFulfilled
     * @param callable|null $onRejected
     * @return $this
     */
    public function head(callable $onFulfilled = null, callable $onRejected = null)
    {
        return $this->send($onFulfilled, $onRejected, 'HEAD');
    }

    /**
     * @param callable|null $onFulfilled
     * @param callable|null $onRejected
     * @return $this
     */
    public function patch(callable $onFulfilled = null, callable $onRejected = null)
    {
        return $this->send($onFulfilled, $onRejected, 'PATCH');
    }

    /**
     * @param callable|null $onFulfilled
     * @param callable|null $onRejected
     * @param string $method
     * @return $this
     */
    public function send(callable $onFulfilled = null, callable $onRejected = null, $method = '')
    {
        $httpResponse = new HttpResponse();

        if ($this->request) {
            $this->promise = $this->client->sendAsync($this->request);
        } else {
            $this->promise = $this->client->requestAsync($method, $this->query, $this->guzzleOptions);
        }

        $this->promise->then(function (ResponseInterface $res) use ($httpResponse, $onFulfilled) {
            $onFulfilled && call_user_func($onFulfilled, $httpResponse->receive($res));
        }, function (RequestException $e) use ($httpResponse, $onRejected) {
            $onRejected && call_user_func($onRejected, $httpResponse->throwException($e));
        });
        return $this;
    }

    public function promise(): PromiseInterface
    {
        if (empty($this->promise)) {
            throw new HttpRequestException('Before calling promise(), call getAsync() or other async methods');
        }
        return $this->promise;
    }
}