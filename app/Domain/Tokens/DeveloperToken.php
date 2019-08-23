<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/12
 * Time: 18:02
 */

namespace App\Domain\Tokens;

use WecarSwoole\Client\API;
/**
 * 开发者模式相关业务
 * Class DeveloperToken
 * @package App\Domain\Tokens
 */
class DeveloperToken extends CommonOperation
{
    public $cacheKey;
    /**
     * @param $appId
     * @param $appSecret
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function freshToken($appId, $appSecret)
    {
        $this->cacheKey = $appId . $this->key;

        $result = API::invoke(
            'wechat:developer_token.get',
            [
                'grant_type'  => 'client_credential',
                'appid'  => $appId,
                'secret'  => $appSecret,
            ]
        );

        $accessToken = $result->getBody();
        if ($accessToken['status'] != 200) {
            $this->appAccessToken = false;
            $this->logger->info(">>>开发者模式：获取最新token失败" . $appId . "原因：" . json_encode($accessToken));
        }
        $this->appAccessToken = $accessToken['access_token'];
        if ($this->appAccessToken) {
            $this->cache->set($this->cacheKey, $this->appAccessToken);
        }
    }
}
