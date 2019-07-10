<?php
if (!is_file('.env')) die('请检查.env文件是否存在');
$env = parse_ini_file('.env', true);
define('WWWROOT', $env['SOURCE_DIR']);
define('CONF_DIR', __DIR__ . '/' . 'conf/conf.d/');
define('SUFFIX', '.test');
define('IGNORE_DIR', ['.', '..', '.DS_Store', '.vscode']);
define('RESOLVE_SON_DIR', ['php-framework']);
// sudo chmod 777 /private/etc/hosts
define('HOSTS_MAC', '/private/etc/hosts');
define('HOSTS_WINDOWS', '/C:\Windows\System32\drivers\etc\hosts');
$hosts = is_file(HOSTS_MAC) ? HOSTS_MAC : (is_file(HOSTS_WINDOWS) ? HOSTS_WINDOWS : die('请检查hosts文件是否存在'));
define('HOSTS', $hosts);

$domainArray = getDomainArray(WWWROOT);
create($domainArray);


function create($domainArray, $addHost = 1, $addConf = 1)
{
    foreach ($domainArray as $domain => $path) {
        if ($addHost) {
            $line = "\n127.0.0.1       $domain";
            file_put_contents(HOSTS, $line, FILE_APPEND);
            echo HOSTS . ' append success:' . $domain . PHP_EOL;
        }
        if ($addConf) {
            $template = 'server {
    listen       80;
    server_name  localhost;
    root   /var/www/html' . $path . ';
    index  index.php index.html index.htm;

    access_log /dev/null;
    #access_log  /var/log/nginx/nginx.' . $domain . '.access.log  main;
    error_log  /var/log/nginx/nginx.' . $domain . '.error.log  warn;

    #error_page  404              /404.html;

    error_page   500 502 503 504  /50x.html;
    location = /50x.html {
        root   /usr/share/nginx/html;
    }

    location ~ \.php$ {
        fastcgi_pass   php72:9000;
        fastcgi_index  index.php;
        include        fastcgi_params;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
    }
}';
            $confName = CONF_DIR . $domain . '.conf';
            if (is_dir(CONF_DIR) && !is_file($confName)) {
                file_put_contents($confName, $template);
                echo 'conf file create success:' . $confName . PHP_EOL;
            }
        }
    }
}

function getDomainArray($dir)
{
    $fileArray = scandir($dir);
    $sonArray = [];
    $domainArray = [];
    foreach ($fileArray as $item) {
        if (!in_array($item, IGNORE_DIR)) {
            $dirName = $dir . "/" . $item;
            if (is_dir($dirName)) {
                $shortName = str_replace(WWWROOT, '', $dirName);
                $domain = 'localhost' === $item ? $item : $item . SUFFIX;
                $domainArray[$domain] = $shortName;
                if (in_array($item, RESOLVE_SON_DIR)) {
                    $sonArray = getDomainArray($dirName);
                }
            }
        }
    }
    return array_merge($domainArray, $sonArray);
}
