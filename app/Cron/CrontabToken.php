<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/12
 * Time: 12:15
 */

namespace App\Cron;

use EasySwoole\EasySwoole\Crontab\AbstractCronTask;


class CrontabToken extends AbstractCronTask
{
    /**
     * 每10分钟执行一次
     * @return string
     */
    public static function getRule(): string
    {
        return '*/10 * * * *';
    }

    public static function getTaskName(): string
    {
        return '自动刷新token';
    }

    /**
     * @param int $taskId
     * @param int $fromWorkerId
     * @param null $flags
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    static function run(\swoole_server $server, int $taskId, int $fromWorkerId, $flags = null)
    {
        new TokenAutoFresh();
    }
}
