<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/6
 * Time: 14:46
 */

namespace XsPkg\PassportClient\Contracts;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use IteratorAggregate;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Interface HttpResponseContract
 * @package XsPkg\PassportClient\Contracts
 */
interface HttpResponseContract extends Arrayable, IteratorAggregate
{
    /**
     * @return Response|null
     */
    public function getResponse();

    /**
     * @return RequestException|null
     */
    public function getException();

    /**
     * 返回 状态码
     * @return mixed
     */
    public function getCode();

    /**
     * 返回 消息
     * @return mixed
     */
    public function getMessage();

    /**
     * 返回 数据体
     * @return mixed
     */
    public function getBody();


    /**
     * 请求是否成功
     * @param callable|null $closure
     * @return bool
     */
    public function isOk(callable $closure = null): bool;

    /**
     * 请求是否失败
     * @param callable|null $closure
     * @return bool
     */
    public function isErr(callable $closure = null): bool;

}