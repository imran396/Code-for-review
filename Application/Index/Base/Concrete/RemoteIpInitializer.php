<?php
/**
 * SAM-5171: Application layer
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/3/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Index\Base\Concrete;

use Sam\Application\HttpRequest\ProxiedFeature\ProxiedFeatureAvailabilityCheckerCreateTrait;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Process\ApplicationProcessGuidManagerCreateTrait;

/**
 * Class RemoteIpInitializer
 * @package
 */
class RemoteIpInitializer extends CustomizableClass
{
    use ApplicationProcessGuidManagerCreateTrait;
    use MemoryCacheManagerAwareTrait;
    use ProxiedFeatureAvailabilityCheckerCreateTrait;
    use ServerRequestReaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Hack to get user remote IP address in $_SERVER['REMOTE_ADDR'] through CloudFlare network
     * Note: This allows remote IP addresses to be spoofed, which is why we log the transition
     */
    public function initialize(): void
    {
        if (!$this->createProxiedFeatureAvailabilityChecker()->isEnabled()) {
            return;
        }

        $ip = null;
        $serverRequestReader = $this->getServerRequestReader();
        $headerNames = [
            'HTTP_CF_PSEUDO_IPV4',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_CF_CONNECTING_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'HTTP_FORWARDED',
        ];
        $processGuid = $this->createApplicationProcessGuidManager()->getProcessGuid();

        foreach ($headerNames as $key) {
            if (!empty($serverRequestReader->readServerValueByKey($key))) {
                $data = [
                    'remote ip' => $serverRequestReader->remoteAddr(),
                    'guid' => $processGuid,
                    'file' => basename(__FILE__),
                ];
                $message = 'Found _SERVER[' . $key . ']: ' . $serverRequestReader->readServerValueByKey($key);
                log_debug($message . composeSuffix($data));

                foreach (explode(',', $serverRequestReader->readServerValueByKey($key)) as $ip) {
                    $ip = trim($ip); // just to be safe

                    if (filter_var(
                            $ip,
                            FILTER_VALIDATE_IP,
                            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                        ) !== false
                    ) {
                        // we found a valid IP in the header, exit the two nested foreach
                        break 2;
                    }

                    // reset if it's not a valid IP
                    $ip = null;
                }
            }
        }

        if ($ip) {
            $data = [
                'remote ip' => $serverRequestReader->remoteAddr(),
                'guid' => $processGuid,
                'file' => basename(__FILE__),
            ];
            $message = 'Replacing _SERVER[REMOTE_ADDR] ' . $serverRequestReader->remoteAddr()
                . ' with ' . $ip . ' from ' . $key;
            log_debug($message . composeSuffix($data));
            $this->setRemoteAddr($ip);
        }
    }

    /**
     * @param string $remoteAddr
     * @return static
     */
    protected function setRemoteAddr(string $remoteAddr): static
    {
        $_SERVER['REMOTE_ADDR'] = trim($remoteAddr);
        // Reset server request cache
        $this->getMemoryCacheManager()->delete(Constants\MemoryCache::SERVER_REQUEST);
        return $this;
    }
}
