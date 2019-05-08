# passport-client
基于Laravel passport 的授权API访问 Http客户端

## 安装
```bash
composer require xspkg/passport-client
```
## 使用

### 配置
发布配置文件
```bash
$ php artisan vendor:publish --tag=passport-client-config
```

只有一个服务端时，使用 `.env` 配置就可以了，要是有多个，需要 `config/passport_client.php` 配置目录,添加 其它 驱动配置

### 引入PassportClient 实例
首先通过容器依赖注入 或 PassportClient Facade 获取到 PassportClient 实例  

大部分请求都返回 实现了 `\XsPkg\PassportClient\ContractsHttpResponseContract` 的实例 

### 使用 Facade 的说明

- 访问驱动，使用默认驱动时，可以不调用该方法
```php
/**
 * @param $nama : 驱动名
 * @return $this
 */
PassportClient::driver($name);
```
#### 获取授权令牌
- 获取授权码访问授权令牌
```php
// 授权时的重定向
return PassportClient::grantAuthorize()->redirect();
// 将授权码转换为访问令牌
$response = PassportClient::grantAuthorize()->setCode($code)->accessToken();
// $response 是一个实现了 \XsPkg\PassportClient\ContractsHttpResponseContract 的实例
```
- 机器授权令牌
```php
$response = PassportClient::grantMachine()->accessToken();
// $response 是一个实现了 \XsPkg\PassportClient\ContractsHttpResponseContract 的实例
```
- 获取密码授权令牌
```php
$response = PassportClient::grantPassword()->signIn($username,$password)->accessToken();
// $response 是一个实现了 \XsPkg\PassportClient\ContractsHttpResponseContract 的实例

```
#### 使用token 访问授权API
- 创建自己的业务api  
例如 创建  RestFULL风格的个人信息 SDK

一、 创建一个 实现 `XsPkg\Contracts\ApiContract` 的类，比如这样:
```php
class UserInfo implements ApiContract
{
        /**
         * 返回 查询地址 (必须)
         * @return string
         */
        public function query(){
            //服务端 API 接口路由地址
            return '/api/user_info'
        }
    
        /**
         * 返回 查询参数
         * @return array
         */
        public function param(){
            //不需要可以为空，可稍后动态调用 HttpRequest 实例的 param()方法 替换 和 新增 参数
        }
    
        /**
         * 返回 访问凭证
         * @return string
         */
        public function token(){
            //不需要可以为空，可稍后动态调用 HttpRequest 实例的 token()方法 设置
        }
}
```

```php
// RestFull
//新增用户,同步 POST 请求,并设置 Guzzle 请求选项
PassportClient::request(new UserInfo(),['timeout' => 2])->param(['username' => 'account','password' => 'secret'])->post();
//如果要异步 POST 请求，只需要把 request 换成 requestAsync
PassportClient::requestAsync(new UserInfo())->param(['username' => 'account','password' => 'secret'])->post();
// 获取用户信息
PassportClient::request(new UserInfo())->get();
// 修改用户信息
PassportClient::request(new UserInfo())->param(['username' => 'account','password' => 'secret'])->put();
//删除用户信息
PassportClient::request(new UserInfo())->param(['username' => 'account','password' => 'secret'])->delete();

```

二、 使用 PSR-7 Request `GuzzleHttp\Psr7\Request`
```php 
//同步，返回 XsPkg\PassportClient\Contracts\HttpResponseContract
PassportClient::send(new Request('GET'),['timeout' => 2]) : HttpResponseContract;

// 异步 
// $onFulfilled 请求成功回调 
// $onRejected  请求失败回调
// 返回 GuzzleHttp\Promise\PromiseInterface
PassportClient::sendAsync(new Request('POST'),callable $onFulfilled,callable $onRejected, array $guzzle = []):PromiseInterface
```

#### 请求响应说明
`\XsPkg\PassportClient\Http\HttpResponse` 实现了 `\XsPkg\PassportClient\ContractsHttpResponseContract`
```php
// 判断请求是否成功
$response->isOk();
// 自定义判断请求是否成功,回调返回 true 时 isOk() 返回true
$response->isOk(function(\XsPkg\PassportClient\Http\HttpResponse $response){
    $response->getResponse(); //获取 PSR-7 response 实例,请求失败时为null
    $response->getException(); //获取 GuzzleHttp\Exception\TransferException 异常，请求成功时为 null
});
// 判断请求是否失败,失败时，返回 true
$response->isErr();
//回调返回 false 时 isErr 返回 true
$response->isErr(function(\XsPkg\PassportClient\Http\HttpResponse $response){
      $response->getResponse(); //获取 PSR-7 response 实例,请求失败时为null
      $response->getException(); //获取 GuzzleHttp\Exception\TransferException 异常，请求成功时为 null
  });

// 获取请求成功或失败时的 消息 和 状态码 ，默认为 http 请求状态码 和 短语
$response->getCode(); //状态码
$response->getMessage();//消息

// 获取 接收到的响应为JSON格式的 转 array 类型
$response->toArray();
// 可直接访问 array 类型的数据
$response['key'];
// 轮循 array 类型的数据
foreach($response as $item){

}

//获取原数据体
$response->getResponse()->getBody();

```
* 服务端 控制 code、 message 和 数据实体 的值，需要定义数据体为json字符串:
{
    "data":"数据实体"，
    "code":"状态码",
    "message":"消息"
}
然后可以，可以通过 PSR-7 response 实例，获取 http 请求信息，比如获取请求状态码
```php
$response->getResponse()->getCode()
```
