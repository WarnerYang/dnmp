<?php

function FunctionName($len=100)
{
    for ($i = 0; $i < $len; $i++)
    {
        $txt = file_get_contents('2.txt')? :'sagpuishgpiwuaghwjb';
        file_put_contents('2.txt', $txt, FILE_APPEND);
		sleep(1);
    }
}
$a = FunctionName();