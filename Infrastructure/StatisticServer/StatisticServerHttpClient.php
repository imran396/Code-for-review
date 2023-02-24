<?php
/**
 * SAM-9728: Move \Stats_Server to Sam namespace
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\StatisticServer;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class to report to SAM stats server
 *
 * Class StatisticServerHttpClient
 * @package Sam\Infrastructure\StatisticServer
 */
class StatisticServerHttpClient extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Function to report to stats server
     *
     * @param string $host
     * @param int $memory
     * @param int $maxMemory
     * @param int $execTime
     * @param string $clientIp
     * @param string $userAgent
     * @param string $sess
     * @param string $url
     * @param string $referrer
     */
    public function report(
        string $host,
        int $memory,
        int $maxMemory,
        int $execTime,
        string $clientIp,
        string $userAgent,
        string $sess,
        string $url,
        string $referrer
    ): void {
        $reportDataQueryString = $this->makeReportDataQueryString(
            $host,
            $memory,
            $maxMemory,
            $execTime,
            $clientIp,
            $userAgent,
            $sess,
            $url,
            $referrer
        );
        $requestUrl = $this->makeRequestUrl($reportDataQueryString);
        $this->sendRequest($requestUrl);
    }

    protected function sendRequest(string $requestUrl): void
    {
        log_debug($requestUrl);
        $result = @file_get_contents($requestUrl, false, $this->makeStreamContext());
        if ($result === false) {
            $error = error_get_last();
            $suffix = $error ? composeSuffix($error) : '';
            log_error("Can't send report to the stat server" . $suffix);
        }
    }

    protected function makeRequestUrl(string $reportDataQueryString): string
    {
        $requestUrl = sprintf(
            'http://%s:%d/logRequest?%s',
            $this->cfg()->get('core->statsServer->host'),
            (int)$this->cfg()->get('core->statsServer->port'),
            $reportDataQueryString

        );
        return $requestUrl;
    }

    protected function makeReportDataQueryString(
        string $host,
        int $memory,
        int $maxMemory,
        int $execTime,
        string $clientIp,
        string $userAgent,
        string $sess,
        string $url,
        string $referrer
    ): string {
        $params = [
            'd' => $host,
            'm' => $memory,
            'mm' => $maxMemory,
            't' => $execTime,
            'ip' => $clientIp,
            'ua' => $userAgent,
            Constants\UrlParam::BACK_URL => $url,
            's' => $sess,
            'r' => $referrer,
        ];
        $queryString = http_build_query($params);
        return $queryString;
    }

    /**
     * @return resource
     */
    protected function makeStreamContext()
    {
        $timeout = (float)$this->cfg()->get('core->statsServer->timeout') ?: 0.2;
        $options = ['http' => ['method' => 'GET', 'timeout' => $timeout]];
        $streamContext = stream_context_create($options);
        return $streamContext;
    }
}
