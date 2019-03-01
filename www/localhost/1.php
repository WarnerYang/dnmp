<?php 

//每隔2000ms触发一次
swoole_timer_tick(1000, function ($timer_id) {
    // echo $timer_id;
    $txt = file_get_contents('2.txt')?:'iushfisfhi';
    file_put_contents('2.txt', $txt, FILE_APPEND);
    // file_put_contents('1.txt', "$num\n",FILE_APPEND);
});