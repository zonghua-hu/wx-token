<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/15
 * Time: 16:53
 */

namespace App\Domain\Tokens;

use App\Foundation\Repository\TokenRepository;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class TokenPattern extends CommonOperation
{
    public $appId;
    public $appSecret;
    public $tokenType;

    /**
     * TokenPattern constructor.
     * @param $appId
     * @param $appSecret
     * @param $tokenType
     * @throws \Exception
     * @throws InvalidArgumentException
     */
    public function __construct($appId, $appSecret, $tokenType)
    {
        parent::__construct(CacheInterface::class, LoggerInterface::class, TokenRepository::class);
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->tokenType = $tokenType;
        $this->init();
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function init()
    {
        if ($this->tokenType == $this->developerToken) {
            $this->appAccessToken = new DeveloperToken($this->appId, $this->appSecret);
        } elseif ($this->tokenType == $this->openPlatform) {
            $this->appAccessToken = new OpenPlatformToken($this->appId);
        }
    }

}
