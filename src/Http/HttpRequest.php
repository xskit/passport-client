<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/6
 * Time: 16:30
 */

namespace XsPkg\PassportClient\Http;

use XsPkg\PassportClient\Contracts\HttpRequestContract;
use XsPkg\PassportClient\Contracts\HttpResponseContract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class HttpRequest implements HttpRequestContract
{
    protected $baseUri;

    protected $config;

    protected $guzzleOptions;

    protected $client;

    protected $query;

    public function __construct($base_uri, $config, array $guzzle = [])
    {
        $this->baseUri = $base_uri;

        $this->config = $config;

        $this->client = new Client([
                'base_uri' => $base_uri
            ] + $guzzle
        );
    }

    public function query($url)
    {
        $this->query = $url;
        return $this;
    }

    public function params(array $value)
    {
        $this->guzzleOptions['query'] = $value;
        return $this;
    }

    public function token($value)
    {
        if ($value) {
            $this->guzzleOptions['headers'] = [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $value,
            ];
        }
        return $this;
    }

    /**
     * @return HttpResponseContract
     */
    public function get(): HttpResponseContract
    {
        return $this->send('GET');
    }

    /**
     * @return HttpResponseContract
     */
    public function post(): HttpResponseContract
    {
        return $this->send('POST');
    }

    /**
     * @return HttpResponseContract
     */
    public function put(): HttpResponseContract
    {
        return $this->send('PUT');
    }

    /**
     * @return HttpResponseContract
     */
    public function delete(): HttpResponseContract
    {
        return $this->send('DELETE');
    }

    /**
     * @return HttpResponseContract
     */
    public function options(): HttpResponseContract
    {
        return $this->send('OPTIONS');
    }

    /**
     * @return HttpResponseContract
     */
    public function patch(): HttpResponseContract
    {
        return $this->send('PATCH');
    }

    /**
     * @return HttpResponseContract
     */
    public function head(): HttpResponseContract
    {
        return $this->send('HEAD');
    }

    /**
     * @param $method
     * @return HttpResponse
     */
    public function send($method)
    {
        $httpResponse = new HttpResponse();
        try {
            $res = $this->client->request($method, $this->query, $this->guzzleOptions);
            $httpResponse->receive($res);
        } catch (GuzzleException  $e) {
            $httpResponse->throwException($e);
        } finally {
            return $httpResponse;
        }
    }
}