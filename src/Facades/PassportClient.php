<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/7
 * Time: 14:29
 */

namespace XsKit\PassportClient\Facades;

use Illuminate\Support\Facades\Facade;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use XsKit\PassportClient\Contracts\ApiContract;
use XsKit\PassportClient\Contracts\HttpRequestAsyncContract;
use XsKit\PassportClient\Contracts\HttpRequestContract;
use XsKit\PassportClient\Contracts\HttpResponseContract;
use XsKit\PassportClient\Grant\Authorize;
use XsKit\PassportClient\Grant\Machine;
use XsKit\PassportClient\Grant\Password;
use XsKit\PassportClient\Client;

/**
 * Class ClientFacade
 * @package XsKit\PassportClient\Facades
 *
 * @method static Client driver(string $name) 设置当前驱动
 * @method static string getDriver() 返回当前驱动名
 * @method static string getBaseUri() 返回当前驱动基础uri
 * @method static array getConfig() 返回当前驱动配置
 * @method static Authorize grantAuthorize() 授权码授权方式
 * @method static Machine grantMachine() 机器授权方式
 * @method static Password grantPassword() 密码授权方式
 * @method static HttpResponseContract refreshToken(string $token) 使用刷新凭证去刷新访问授权凭证
 * @method static HttpRequestContract request(ApiContract $api, array $guzzle = []) api请求
 * @method static HttpRequestAsyncContract requestAsync(ApiContract $api, array $guzzle = []) api异步请求
 * @method static HttpResponseContract send(RequestInterface $request, array $guzzle = []) psr-7 请求
 * @method static PromiseInterface sendAsync(RequestInterface $request, $onFulfilled, $onRejected, array $guzzle = []) psr-7 异步请求
 *
 */
class PassportClient extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Client::class;
    }
}