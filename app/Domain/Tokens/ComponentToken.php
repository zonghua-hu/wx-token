<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/13
 * Time: 15:15
 */

namespace App\Domain\Tokens;

use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use WecarSwoole\Client\API;

class ComponentToken extends CommonOperation
{
    public $cacheKey;

    public $comAppId = 'wx46990617c3d8bf81';
    public $comAppSecret = '615d70ee3463062891771cfb1b4f7ef3';
    public $comAccessToken = false;
    public $comTicket;

    /**
     * ComponentToken constructor.
     * @param $comTicket
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct($comTicket)
    {
        parent::__construct(CacheInterface::class, LoggerInterface::class);

        $this->comTicket = $comTicket;
        $this->cacheKey = $this->comAppId.$this->key;

        $this->refreshComToken();
        $this->returnComToken();
    }

    private function returnComToken()
    {
        return $this->comAccessToken;
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    private function refreshComToken()
    {
        $comTokenData = [
            'component_appid' => $this->comAppId,
            'component_appsecret' => $this->comAppSecret,
            'component_verify_ticket' => $this->comTicket,
        ];
        $result = API::invoke('wechat:component_token.post', $comTokenData);
        $comToken = $result->getBody();
        if ($comToken['status'] != 200) {
            $this->comAccessToken = false;
            $this->logger->info(">>>开放平台模式：获取最新componentToken失败".$this->comAppId."原因：".json_encode($comToken));
        }
        $this->comAccessToken = $comToken['access_token'];

        if ($this->comAccessToken) {
            $this->cache->set($this->cacheKey,$this->comAccessToken);
        }
    }
}

