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
    public $appId;
    public $appSecret;
    public $cacheKey;
    /**
     * @param $appId
     * @param $appSecret
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function freshToken($appId, $appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->cacheKey = $this->appId . $this->key;

        $result = API::invoke(
            'wechat:developer_token.get',
            [
                'grant_type'  => 'client_credential',
                'appid'  => $this->appId,
                'secret'  => $this->appSecret,
            ]
        );

        $accessToken = $result->getBody();
        if ($accessToken['status'] != 200) {
            $this->appAccessToken = false;
            $this->logger->info(">>>开发者模式：获取最新token失败" . $this->appId . "原因：" . json_encode($accessToken));
        }
        $this->appAccessToken = $accessToken['access_token'];
        if ($this->appAccessToken) {
            $this->cache->set($this->cacheKey, $this->appAccessToken);
        }
    }
}
