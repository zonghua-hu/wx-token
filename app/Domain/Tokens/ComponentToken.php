<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/13
 * Time: 15:15
 */

namespace App\Domain\Tokens;

use App\Foundation\Repository\TokenRepository;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use WecarSwoole\Client\API;

class ComponentToken extends CommonOperation
{
    public $cacheKey;
    /**
     * ComponentToken constructor.
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct(CacheInterface::class, LoggerInterface::class,TokenRepository::class);
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

