<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/15
 * Time: 16:53
 */

namespace App\Domain\Tokens;

use WecarSwoole\Container;
use Psr\SimpleCache\InvalidArgumentException;

class TokenPattern extends CommonOperation
{
    public $appId;
    public $appSecret;
    public $tokenType;
    /**
     * @param $appId
     * @param $appSecret
     * @param $tokenType
     * @throws InvalidArgumentException
     * @throws \Throwable
     */
    public function init($appId, $appSecret, $tokenType)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->tokenType = $tokenType;

        if ($this->tokenType == $this->developerToken) {
            $developerToken = Container::get(DeveloperToken::class);
            $developerToken->freshToken($this->appId, $this->appSecret);
        } elseif ($this->tokenType == $this->openPlatform) {
            $openPlatformToken = Container::get(OpenPlatformToken::class);
            $openPlatformToken->getToken($this->appId);
        }
    }

}
