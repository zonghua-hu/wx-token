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
    /**
     * @param $appId
     * @param $appSecret
     * @param $tokenType
     * @throws InvalidArgumentException
     * @throws \Throwable
     */
    public function freshDifferentToken($appId, $appSecret, $tokenType)
    {
        if ($tokenType == $this->developerToken) {
            $developerToken = Container::get(DeveloperToken::class);
            $developerToken->getToken($appId, $appSecret);
        } elseif ($tokenType == $this->openPlatform) {
            $openPlatformToken = Container::get(OpenPlatformToken::class);
            $openPlatformToken->getToken($appId);
        }
    }

}
