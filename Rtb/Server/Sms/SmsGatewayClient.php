<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Server\Sms;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Rtb\Server\Daemon\RtbDaemonEvent;
use Sam\Rtb\Server\Daemon\RtbDaemonLegacy;
use Sam\Rtb\Server\Sms\Internal\SmsEventClient;
use Sam\Rtb\Server\Sms\Internal\SmsLegacyClient;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class SmsGatewayClient
 * @package Sam\Rtb\Server\Sms
 */
class SmsGatewayClient extends CustomizableClass
{
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $responseJson
     * @param int $accountId
     * @param RtbDaemonLegacy|RtbDaemonEvent $daemon
     */
    public function sendMessage(string $responseJson, int $accountId, RtbDaemonLegacy|RtbDaemonEvent $daemon): void
    {
        $url = $this->getGatewayUrl($accountId);
        if (!$url) {
            log_error("Failed to send text message no api url defined!", 'action_queue.log');
            return;
        }

        $host = $this->extractHost($url);
        if (!$host) {
            log_error("Failed to send text message invalid api url defined no host found!", 'action_queue.log');
            return;
        }

        log_debug('Creating SMS sending client for ' . get_debug_type($daemon));
        if ($daemon instanceof RtbDaemonLegacy) {
            /** @var SmsLegacyClient $client */
            $client = $daemon->createClient(SmsLegacyClient::class, $host, 80);
        } elseif ($daemon instanceof RtbDaemonEvent) {
            /** @var SmsEventClient $client */
            $client = $daemon->createClient(SmsEventClient::new(), $host, 80);
        } else {
            return;
        }

        $response = json_decode($responseJson, true);
        $message = $response[Constants\Rtb::REQ_SMS_PAYLOAD];
        $request = $this->makeRequestData($url, $message, $accountId);
        log_debug('Writing to SMS gate' . composeLogData(['server' => $url]));
        $client->write($request);
    }

    protected function makeRequestData(string $url, string $message, int $accountId): string
    {
        $postVar = $this->getSettingsManager()->get(Constants\Setting::TEXT_MSG_API_POST_VAR, $accountId);
        $postData = "{$postVar}={$message}";
        $requestLength = strlen($postData);
        $host = $this->extractHost($url);
        $postRequestData = "POST {$url} HTTP/1.0\r\n" .
            "Host:{$host}\r\n" .
            "Content-Type: application/x-www-form-urlencoded\r\n" .
            "Content-Length: $requestLength\r\n\r\n" .
            "$postData\r\n";
        return $postRequestData;
    }

    protected function getGatewayUrl(int $accountId): string
    {
        $url = (string)$this->getSettingsManager()->get(Constants\Setting::TEXT_MSG_API_URL, $accountId);
        return $url;
    }

    protected function extractHost(string $url): string
    {
        $urlParts = parse_url($url);
        $host = $urlParts['host'] ?? '';
        return $host;
    }
}
