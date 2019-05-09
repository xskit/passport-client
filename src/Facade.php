<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/5/7
 * Time: 14:29
 */

namespace XsPkg\PassportClient;

use Illuminate\Support\Facades\Facade as BasicFacade;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use XsPkg\PassportClient\Contracts\ApiContract;
use XsPkg\PassportClient\Contracts\HttpRequestAsyncContract;
use XsPkg\PassportClient\Contracts\HttpRequestContract;
use XsPkg\PassportClient\Contracts\HttpResponseContract;
use XsPkg\PassportClient\Grant\Authorize;
use XsPkg\PassportClient\Grant\Machine;
use XsPkg\PassportClient\Grant\Password;

/**
 * Class Facade
 * @package XsPkg\PassportClient
 * @method static PassportClient driver(string $name) 设置当前驱动
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
 */
class Facade extends BasicFacade
{
    protected static function getFacadeAccessor()
    {
        return PassportClient::class;
    }
}