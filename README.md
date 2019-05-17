# 自用docker环境
## 说明
基于[此仓库](https://github.com/yeszao/dnmp)修改，感谢贡献这么优秀的源码。重新打包了php7.2，集成常用扩展redis、swoole、amqp，方便使用。

## Docker compose所包含的镜像介绍
 - [warneryang/php72](https://hub.docker.com/r/warneryang/php72) 自建php7.2镜像，集成常用redis、swoole、amqp等扩展
 - nginx  nginx官方镜像
 - redis  redis官方镜像
 - mysql mysql官方镜像
 - rabbitmq rabbitmq官方镜像
  - phpmyadmin/phpmyadmin 数据库web管理（默认去掉，可用桌面端代替）
 - erikdubbelboer/phpredisadmin redis的web管理（默认去掉，可用桌面端代替）


## 如何使用
- 启动之前请先关闭本地的 nginx apache 和 redis 防止端口占用启动失败

1. 本地安装`git`、`docker`和`docker-compose`。
2. `clone`项目：
    ```
    $ git clone https://github.com/WarnerYang/dnmp
    ```
3. 如果不是`root`用户，还需将当前用户加入`docker`用户组：（windows 下 忽略）
    ```
    $ sudo gpasswd -a ${USER} docker
    ```
4. 拷贝环境配置文件`env.sample`为`.env`，启动：
    ```
    $ cd dnmp
    $ cp env.sample .env   # Windows系统请用copy命令，或者用编辑器打开后另存为.env
    $ docker-compose up

    本人建议将.env中修改为SOURCE_DIR=../wwwroot，就是把网站根目录移出去，目录层级过多不方便查看
    ```

5. 访问在浏览器中访问：

 - [http://localhost](http://localhost)： 默认*http*站点
 - [https://localhost](https://localhost)：自定义证书*https*站点，访问时浏览器会有安全提示，忽略提示访问即可

两个站点使用同一PHP代码：`./www/localhost/index.php`。

要修改端口、日志文件位置、以及是否替换source.list文件等，请修改.env文件，然后重新构建：
```
docker-composer up
```
## 目录结构
```
├── conf                    配置文件目录
│   ├── conf.d              Nginx用户站点配置目录
│   ├── nginx.conf          Nginx默认配置文件
│   ├── mysql.cnf           MySQL用户配置文件
│   ├── php-fpm.conf        PHP-FPM配置文件（部分会覆盖php.ini配置）
│   └── php.ini             PHP默认配置文件
├── Dockerfile              PHP镜像构建文件
├── extensions              PHP扩展源码包
├── log                     Nginx日志目录
├── mysql                   MySQL数据目录
├── www                     PHP代码目录
└── source.list             Debian源文件（不在使用dockerfile）
```
## phpmyadmin 
phpMyAdmin容器映射到主机的端口地址是：`8080`，所以主机上访问phpMyAdmin的地址是：
```
http://localhost:8080
```
mysql连接信息如下：
- host: (本项目的mysql容器网络)  代码中不在填写host和ip地址，直接填写 mysql
- port: `3306`
##  phpRedisAdmin
phpRedisAdmin容器映射到主机的端口地址是：`8081`，所以主机上访问phpMyAdmin的地址是：
```
http://localhost:8081
```
Redis连接信息如下：
- host: (本项目的Redis容器网络) 代码中不在填写host和ip地址，直接填写 redis
- port: `6379`
##  rabbitmq
rabbitmq容器映射到主机的wen管理端口地址是：`15672`，所以主机上访问prabbitmq管理页面的地址是：
```
http://localhost:15672
```
rabbitmq连接信息如下：
- host: (本项目的rabbitmq容器网络) 代码中不在填写host和ip地址，直接填写 rabbitmq
- port: `5672`
- user：guest
- pwd：guest

## 常用命令

windows 强烈建议用 PowerShell
```bash
docker-compose start
docker-compose restart
docker-compose stop
docker images  查看本地镜像
docker ps 查看正在运行的容器
docker ps -a  查看所有容器 

#执行容器内的 命令
docker exec -it  dnmp_php72_1 /bin/bash -c 'cd /var/www/html/localhost && php test.php'

# 重启nginx
docker exec -it dnmp_nginx_1 nginx -s reload

# 进入php容器
docker exec -it  dnmp_php72_1

# 查看容器ip
docker inspect 容器名称或 id
如：docker inspect dnmp_php72_1

# 端口映射 可直接在docker-compose.yml对应容器中加入以下参数，docker-compose up 即可
    ports:
      - "9502:9502"

```