<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/13
 * Time: 15:15
 */

namespace App\Domain\Tokens;

use WecarSwoole\Client\API;

class ComponentToken extends CommonOperation
{
    public $cacheKey;
    /**
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function initComToken()
    {
        $this->cacheKey = $this->comAppId . $this->key;
        $this->refreshComToken();
    }
    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    private function refreshComToken()
    {
        $result = API::invoke(
            'wechat:component_token.post',
            [
                'component_appid' => $this->comAppId,
                'component_appsecret' => $this->comAppSecret,
                'component_verify_ticket' => $this->comTicket,
            ]
        );

        $comToken = $result->getBody();
        if ($comToken['status'] != 200) {
            $this->comAccessToken = false;
            $this->logger->info(">>>开放平台模式：获取最新componentToken失败" . $this->comAppId . "原因：" . json_encode($comToken));
        }
        $this->comAccessToken = $comToken['access_token'];

        if ($this->comAccessToken) {
            $this->cache->set($this->cacheKey, $this->comAccessToken);
        }
    }
}

