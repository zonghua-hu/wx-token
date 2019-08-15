<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/14
 * Time: 16:10
 */

namespace App\Domain\Tokens;

use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

abstract class CommonOperation
{
    public $logger;
    public $cache;
    public $key;

    /**
     * Common constructor.
     * @param CacheInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(CacheInterface $cache, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->cache = $cache;
        $this->key = 'wccRedisToken';
    }
}
