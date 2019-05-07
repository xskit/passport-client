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
    public function getAsync(callable $onFulfilled = null, callable $onRejected = null)
    {
        return $this->sendAsync('GET', $onFulfilled, $onRejected);
    }

    /**
     * @param callable|null $onFulfilled
     * @param callable|null $onRejected
     * @return $this
     */
    public function postAsync(callable $onFulfilled = null, callable $onRejected = null)
    {
        return $this->sendAsync($onFulfilled, $onRejected,'GET');
    }

    /**
     * @param callable|null $onFulfilled
     * @param callable|null $onRejected
     * @return $this
     */
    public function putAsync(callable $onFulfilled = null, callable $onRejected = null)
    {
        return $this->sendAsync($onFulfilled, $onRejected,'PUT');
    }

    /**
     * @param callable|null $onFulfilled
     * @param callable|null $onRejected
     * @return $this
     */
    public function deleteAsync(callable $onFulfilled = null, callable $onRejected = null)
    {
        return $this->sendAsync($onFulfilled, $onRejected,'DELETE');
    }

    /**
     * @param callable|null $onFulfilled
     * @param callable|null $onRejected
     * @return $this
     */
    public function optionsAsync(callable $onFulfilled = null, callable $onRejected = null)
    {
        return $this->sendAsync($onFulfilled, $onRejected,'OPTIONS');
    }

    /**
     * @param callable|null $onFulfilled
     * @param callable|null $onRejected
     * @return $this
     */
    public function headAsync(callable $onFulfilled = null, callable $onRejected = null)
    {
        return $this->sendAsync($onFulfilled, $onRejected,'HEAD');
    }

    /**
     * @param callable|null $onFulfilled
     * @param callable|null $onRejected
     * @return $this
     */
    public function patchAsync(callable $onFulfilled = null, callable $onRejected = null)
    {
        return $this->sendAsync($onFulfilled, $onRejected,'PATCH');
    }

    /**
     * @param $method
     * @param callable|null $onFulfilled
     * @param callable|null $onRejected
     * @return $this
     */
    public function sendAsync(callable $onFulfilled = null, callable $onRejected = null,$method = '')
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