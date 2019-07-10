@echo off
SETLOCAL ENABLEDELAYEDEXPANSION
::遍历文件夹下的.conf文件 并去掉后缀，建议右键以管理员身份运行
for %%c in (*.conf) do (
	set host=%%c
	set "host=!host:~0,-5!"
	echo,
	::写入windows的hosts文件
	echo 127.0.0.1	!host! >>"%windir%\system32\drivers\etc\hosts"
	echo !host! 
)
pause