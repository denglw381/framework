#!/bin/bash
action=${1:-start}
php_exe=/usr/local/php/bin/php
workdir=$(pwd)
calldir=lib

if [ ! -n "${calldir/\/*/}" ]
then
    php_file=$calldir/DmStart.php
    pid_file=$calldir/DmDaemon.pid
else
    php_file=$workdir/$calldir/DmStart.php
    pid_file=$workdir/$calldir/DmDaemon.pid
fi

if [ ! -x $php_exe ]
then
    echo $php_exe is not executable.
    exit 1
fi

case $action in

    start )
        if [ ! -w `dirname $pid_file` ]
        then
            echo $pid_file can not writeable.
            exit 1
        elif [ -e $pid_file ]
        then
            echo $pid_file already exists.
            exit 1
        fi
        php_shell="$php_exe $php_file -p$pid_file"
        exec $php_shell
    ;;  

    restart )
        if [ ! -r $pid_file ]
        then
            echo $pid_file does not exists or can not read.
            exit 1
        fi  
        pid=`cat $pid_file`
        if [ $pid -gt 0 ]
        then
            `kill -s SIGINT $pid`
        fi
        php_shell="$php_exe $php_file -p$pid_file"
        exec $php_shell
    ;;  

    stop )
        if [ ! -r $pid_file ]
        then
            echo $pid_file does not exists or can not read.
            exit 1
        fi  
        pid=`cat $pid_file`
        `kill -s SIGINT $pid`
        if [ $? -eq 0 ]
        then
             rm $pid_file
        fi
    ;;

esac
