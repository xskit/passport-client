# passport-client
基于Laravel passport 的授权API访问的 Http客户端包装器
## 功能：
- 简单的获取服务授权的凭证和 API请求
- 支持自定义处理返回的数据的响应
- 自定义 SDK 的请求的包装，需要实现 `XsKit\Contracts\ApiContract` 接口
- 支持 同步 或 异步 请求 API服务，可根据 accessToken 访问授权的 api （推荐使用RESTFull 风格的接口）

## 安装
```bash
composer require xskit/passport-client
```
## 引入
### Laravel
安装后使用 Laravel 自动发现,包将自动注册自己。

发布配置文件
```bash
$ php artisan vendor:publish --tag=passport-client-config
# 如果已经有配置文件，强制覆盖配置
$ php artisan vendor:publish --tag=passport-client-config --force
```
### Lumen
对于Lumen的使用，服务提供者应该手动注册，`bootstrap/app.php` 如下面所示:
```php
$app->register(\XsKit\PassportClient\PassportClientServiceProvider::class);
```
手动复制配置文件 `passport_clinet.php` 到 config 目录下。

> 只有一个服务端时，使用 `.env` 配置就可以了，要是有多个，需要 `config/passport_client.php` 配置目录,添加 其它 驱动配置

### 引入PassportClient 实例
有两种方式：
1. 首先通过容器依赖注入`\XsKit\PassportClient\Client`
2. PassportClient Facade 静态调用的方式使用

大部分请求都返回 实现了 `\XsKit\PassportClient\ContractsHttpResponseContract` 的实例 

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
// $response 是一个实现了 \XsKit\PassportClient\ContractsHttpResponseContract 的实例
```
- 机器授权令牌
```php
$response = PassportClient::grantMachine()->accessToken();
// $response 是一个实现了 \XsKit\PassportClient\ContractsHttpResponseContract 的实例
```
- 获取密码授权令牌
```php
$response = PassportClient::grantPassword()->signIn($username,$password)->accessToken();
// $response 是一个实现了 \XsKit\PassportClient\ContractsHttpResponseContract 的实例

```
#### 一、快速请求
```php
//支持的请求方式
PassportClient::request()->get();
PassportClient::request()->post();
PassportClient::request()->put();
PassportClient::request()->delete();
PassportClient::request()->head();
PassportClient::request()->patch();
PassportClient::request()->options();

//带参数的post 请求
PassportClient::request()->query('api/info')->param(['key1'=>'value1','key2'=>'value2'])->post();
PassportClient::request()->query('api/info')->param('key','value')->post();

//修改配置的 base_uri 选项
PassportClient::request('http://example.com')->query('api/info')->get();
PassportClient::request()->baseUri('http://example.com')->query('api/info')->get();
//带授权凭证的访问
PassportClient::request()->query('api/info')->token('你的凭证')->get();

//异步请求功能同上,修改如下
PassportClient::requestAsync()->get();
PassportClient::requestAsync()->get(callable $onFulfilled);
PassportClient::requestAsync()->get(callable $onFulfilled, callable $onRejected);

//PSR-7 Request 请求对象的使用
PassportClient::send(new Request('GET','url'));

PassportClient::sendAsync(new Request('GET','url'));
```
- query() 方法参数说明  
这里有一些关于 base_uri 的快速例子：

|base_uri|	     query(URI)|        	结果|
|:--------|:----------|:--------------------- |
|http://foo.com	| /bar |	http://foo.com/bar|
|http://foo.com/foo | /bar |http://foo.com/bar|
|http://foo.com/foo | bar |http://foo.com/bar|
|http://foo.com/foo/ |	bar	|http://foo.com/foo/bar|
|http://foo.com	| http://baz.com	| http://baz.com|
|http://foo.com/?bar|	bar	| http://foo.com/bar|

#### 二、API 的封装
- 创建自己的业务api  
例如 创建  RestFULL风格的个人信息 SDK

一、 创建一个 实现 `XsKit\Contracts\ApiContract` 的类，比如这样:
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
         * 返回 请求方式
         */
        public function method(){
            return 'GET';
        }
        
       /**
        * 返回 要修改的 基础 uri， 使用配置文件可以返回 void
        * @return string|void
        */
        public function baseUri(){
            
        }
    
        /**
         * 返回 查询参数
         * @return array
         */
        public function param(){
            //不需要可以为空，也可稍后动态调用 HttpRequest 实例的 param()方法 替换 和 新增 参数
        }
    
        /**
         * 返回 访问凭证
         * @return string
         */
        public function token(){
            //不需要可以为空，也可稍后动态调用 HttpRequest 实例的 token()方法 设置
        }
}
```

```php
// RestFull
//新增用户,同步 POST 请求,并设置 Guzzle 请求选项
PassportClient::request(new UserInfo(),['timeout' => 2])->param(['username' => 'account','password' => 'secret'])->post();
//如果 Userinfo 不需要动态修改参数和请求方法，可以更简单的发启请求
PassportClient::api(new UserInfo());
//修改默认 guzzle 请求选项
PassportClient::api(new UserInfo(),['timeout' => 2]);

//如果要异步 POST 请求，只需要把 request 换成 requestAsync
PassportClient::requestAsync(new UserInfo())->param(['username' => 'account','password' => 'secret'])->post();
// 获取用户信息
PassportClient::request(new UserInfo())->get();
// 修改用户信息
PassportClient::request(new UserInfo())->param(['username' => 'account','password' => 'secret'])->put();
//删除用户信息
PassportClient::request(new UserInfo())->param(['username' => 'account','password' => 'secret'])->delete();

```

#### 三、 使用 PSR-7 Request `GuzzleHttp\Psr7\Request`
```php 
//同步，返回 XsKit\PassportClient\Contracts\HttpResponseContract
PassportClient::send(new Request('GET'),['timeout' => 2]) : HttpResponseContract;

// 异步 
// $onFulfilled 请求成功回调 
// $onRejected  请求失败回调
// 返回 GuzzleHttp\Promise\PromiseInterface
PassportClient::sendAsync(new Request('POST'),callable $onFulfilled,callable $onRejected, array $guzzle = []):PromiseInterface
```

### 请求响应说明
`\XsKit\PassportClient\Http\HttpResponse` 实现了 `\XsKit\PassportClient\ContractsHttpResponseContract`
```php
// 判断请求是否成功
$response->isOk();
// 自定义判断请求是否成功,回调返回 true 时 isOk() 返回true
$response->isOk(function(\XsKit\PassportClient\Http\HttpResponse $response){
    $response->getResponse(); //获取 PSR-7 response 实例,请求失败时为null
    $response->getException(); //获取 GuzzleHttp\Exception\TransferException 异常，请求成功时为 null
});
// 判断请求是否失败,失败时，返回 true
$response->isErr();
// 定义自定义回调处理响应，返回 false 时 isErr() 返回 true
$response->isErr(function(\XsKit\PassportClient\Http\HttpResponse $response){
      $response->getResponse(); //获取 PSR-7 response 实例,请求失败时为null
      $response->getException(); //获取 GuzzleHttp\Exception\TransferException 异常，请求成功时为 null
  });

// 获取请求成功或失败时的 消息 和 状态码 ，默认为 http 请求状态码 和 短语
$response->getCode(); //状态码
$response->getMessage();//消息

//获取原数据体
$response->getBody();
```
// 获取响应数据
$response->getData();

#### 如果接收到的数据是 可转换为 array 类型时,可以使用以下方法

```
// 返回数组
$response->toArray();
// 返回集合对象
$response->toCollection();
// 可直接访问
$response['key'];
// 轮循数据
foreach($response as $item){

}

```

- 可以通过 PSR-7 Response 实例，获取 Http 请求信息，比如获取请求状态码

  ```php
  $response->getResponse()->getCode()
  ```

  
* 服务端返回数据为 json 字符串时， 数据的默认键名 code、 message 和 data 时，获取对应方法为 getCode() , getMessage() 和 getData() 或 toArray() 的值,如下:

  ```json
  {
    "data":"数据实体",
    "code":"状态码",
    "message":"消息"
  }
  ```

  ```php
  $response->getData();    //返回：数据实体
  $response->toArray();    //返回：数据实体（数组类型）
  $response->getCode();    //返回：状态码
  $response->getMessage(); // 返回：消息
  ```

  

* 自定义解析服务端返回数据配置： `response_handle` 配置项为一个现实 `XsKit\PassportClient\Contracts\ResponseHandleContract `接口的响应数据的处理类，对响应数据的控制，如下：
```php
// 可配置 自定义现实 XsKit\PassportClient\Contracts\ResponseHandleContract 接口的响应数据的处理类
// 处理类返回一个匿名函数,函数可用$this 指向是 XsKit\PassportClient\Http\HttpResponse 响应实例
// 该函数接收一个 Psr\Http\Message\ResponseInterface 响应实例
// 默认配置为
'response_handle' => XsKit\PassportClient\Http\ResponseHandle::class,
```
例如，写一个这样的自定义响应数据处理类,做为 `response_handle` 默认配置项的替换：
```php
use XsKit\PassportClient\Contracts\ResponseHandleContract;

class ResponseHandle implements ResponseHandleContract
{

    public static function parseData(): \Closure
    {
        return function (\Psr\Http\Message\ResponseInterface $response) {
            if (isset($this->data['data'], $this->data['status'], $this->data['code'])) {
                $this->data = $this->data['data'];
                $this->code = $this->data['code'];
                //与默认处理不一样的地方
                $this->message = $this->data['status'];
            }
        };
    }

}
```
