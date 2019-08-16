<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/12
 * Time: 18:01
 */

namespace App\Domain\Tokens;

use App\Foundation\Repository\TokenRepository;
use WecarSwoole\Client\API;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
/**
 * 开放平台模式token类
 * 1.获取第三方appId、secret、ticket
 * 2.获取最新的开放平台模式下的token
 * Class CommonToken
 * @package App\Cron
 */
class OpenPlatformToken extends CommonOperation
{

    public $cacheKey;
    public $comCacheKey;
    public $appId;

    /**
     * OpenPlatformToken constructor.
     * @param $appId
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function __construct($appId)
    {
        parent::__construct(CacheInterface::class, LoggerInterface::class, TokenRepository::class);

        $this->appId = $appId;
        $this->cacheKey = $this->appId . $this->key;
        $this->comCacheKey = $this->comAppId . $this->key;
        $this->getComponentToken();
        $this->refreshToken();
        $this->returnAccessToken();
    }

    /**
     * 获取componentAccessToken并保存
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function getComponentToken()
    {
        $this->comAccessToken = $this->cache->get($this->comCacheKey);
        if (!$this->comAccessToken) {
            $this->comAccessToken = new ComponentToken();
        }
    }
    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    private function refreshToken()
    {
        $result = API::invoke(
            'wechat:authorizer_component_token.post',
            [
                'query_params' =>
                    [
                        'componentToken' => $this->comAccessToken
                    ],
                'body' =>
                    [
                        'component_appid' => $this->comAppId,
                        'authorizer_appid' => $this->appId,
                        'authorizer_refresh_token' => $this->comAccessToken
                    ]
            ]
        );

        $comToken = $result->getBody();
        if ($comToken['status'] != 200) {
            $this->appAccessToken = false;
            $this->logger->info(">>>开放平台模式：获取最新componentToken失败" . $this->comAppId . "原因：" . json_encode($comToken));
        }
        $this->appAccessToken = $comToken['access_token'];

        if ($this->appAccessToken) {
            $this->cache->set($this->cacheKey, $this->appAccessToken);
        }
    }
}
