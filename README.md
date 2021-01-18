Weibo_web  
====================
基于Laravel6_x 基础上开发的一个简单的 微博网页项目

### 环境要求

> PHP >= 7.2.5
>
> BCMath PHP 拓展
>
> Ctype PHP 拓展
>
> JSON PHP 拓展
>
> Mbstring PHP 拓展
>
> OpenSSL PHP 拓展
>
> PDO PHP 拓展
>
> Tokenizer PHP 拓展
>
> XML PHP 拓展
>

## 2.快速使用
1. 本地安装
    - `git`
    - `node`
    - `npm`
    - `yarn`
2. `clone`项目：
    ```
    $ git clone https://github.com/H-pinke/weibo_dome.git
    ```
3.  安装依赖
    ```
    $ composer intsall
    $ yarn install
    $ npm run dev
    ```
4.  配置Laravel：
    ```
    $ cp .env.example .env   
    $ php artisan key:generate                                       
    
    ```
5.  配置数据库信息，数据库名称、用户名、密码都配置好
    ```
    $ php artisan migrate:refresh --seed    #填充测试数据                               
        
    ```
       
6. 在浏览器中访问：`http://localhost`或 本机IP 可以查看效果


## 3 问题
如果在 npm run dev的时候出错。 macos和Linux 用户忽略这个包cross-env。可以修改package.json
文件，去掉cross-env


遵循Apache2开源协议发布，并提供免费使用。
