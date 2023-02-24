<?php
/**
 * Helping methods for response processing and caching. We store cached responses inside.
 *
 * SAM-3924: RTBD scaling by providing a "repeater/ broadcasting" service for viewers
 * SAM-10639: RTBD Viewer-repeater - Avoid repeated authorization requests to rtbd origin
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 13, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\ViewerRepeater;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ResponseManager
 * @package Sam\Rtb\ViewerRepeater
 */
class ResponseManager extends CustomizableClass
{
    /**
     * Commands, that are requested by viewer, hence we want to cache their responses
     * Do not remove Auth command from cache to prevent excess connections of repeater to rtbd on every viewer connection.
     * [response command => request command]
     * @var array
     */
    protected array $cachedCommandMap = [
        Constants\Rtb::CMD_AUTH_S => Constants\Rtb::CMD_AUTH_Q,
        Constants\Rtb::CMD_SYNC_S => Constants\Rtb::CMD_SYNC_Q,
    ];

    protected const IGNORED_REQUEST_COMMANDS = [
        Constants\Rtb::CMD_PING_S => Constants\Rtb::CMD_PING_Q,
        Constants\Rtb::CMD_REVERSE_PING_S => Constants\Rtb::CMD_REVERSE_PING_Q,
        Constants\Rtb::CMD_REVERSE_PING_RESULT_S => Constants\Rtb::CMD_REVERSE_PING_Q,
        Constants\Rtb::CMD_NOOP => Constants\Rtb::CMD_NOOP,
    ];

    protected string $eol = "\n\n";
    /**
     * @var string[][]
     */
    protected array $cachedResponses = [];

    /**
     * Return instance of self
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * We suppose, that incoming data should be json "{...}"
     * @param string $data
     * @return string
     */
    public function sanitize($data): string
    {
        $data = trim($data);
        $firstPos = strpos($data, '{');
        $lastPos = strrpos($data, '}');
        $length = $lastPos - $firstPos + 1;
        $data = substr($data, $firstPos, $length);
        return $data;
    }

    /**
     * @param string $data
     * @return string
     */
    public function prepareResponseToRtbd($data): string
    {
        $data .= $this->eol;
        return $data;
    }

    /**
     * Check if request command must be ignored by processing of viewer-repeater.
     * @param string $requestCmd
     * @return bool
     */
    public function isIgnoredCommand(string $requestCmd): bool
    {
        return in_array($requestCmd, self::IGNORED_REQUEST_COMMANDS, true);
    }

    /**
     * Cache only responses for available Viewer commands
     * We should drop SyncS response from cache, when other response received, means running auction state is changed
     * @param string $responseJson
     * @param int $auctionId
     */
    public function cacheResponse($responseJson, $auctionId): void
    {
        $responseArray = json_decode($responseJson, true);
        if (!$responseArray) {
            log_error("Skip response caching, because unable to decode json response: " . $responseJson);
            return;
        }

        $responseCmd = $responseArray[Constants\Rtb::RES_COMMAND];
        $requestCmd = $this->getMappedRequestCommand($responseCmd);
        if ($requestCmd) {
            if (isset($this->cachedCommandMap[$responseCmd])) {
                if (empty($this->cachedResponses[$auctionId])) {
                    $this->cachedResponses[$auctionId] = [];
                }

                if ($responseCmd === Constants\Rtb::CMD_SYNC_S) {
                    // Drop viewer resource id from cached data, because cache is intended for different viewers
                    $responseArray = json_decode($responseJson, true);
                    unset($responseArray[Constants\Rtb::REQ_DATA][Constants\Rtb::RES_VIEWER_RESOURCE_ID]);
                    $responseJson = json_encode($responseArray);
                }

                $this->cachedResponses[$auctionId][$requestCmd] = $responseJson;
                log_trace('Response saved in cache' . composeSuffix(['a' => $auctionId, 'requestCmd' => $requestCmd]));
            } else {
                log_trace('SKip response caching' . composeSuffix(['a' => $auctionId, 'requestCmd' => $requestCmd]));
            }
            return;
        }

        $this->dropCachedResponse(Constants\Rtb::CMD_SYNC_Q, $auctionId);
    }

    /**
     * @param string $requestCmd
     * @param int $auctionId
     * @return string|null
     */
    public function getCachedResponse($requestCmd, $auctionId): ?string
    {
        $has = $this->hasCachedResponse($requestCmd, $auctionId);
        $responseJson = $has ? $this->cachedResponses[$auctionId][$requestCmd] : null;
        return $responseJson;
    }

    /**
     * @param string $requestCmd
     * @param int $auctionId
     */
    protected function dropCachedResponse($requestCmd, $auctionId): void
    {
        if ($this->hasCachedResponse($requestCmd, $auctionId)) {
            log_trace('Drop cached response' . composeSuffix(['a' => $auctionId, 'requestCmd' => $requestCmd]));
            unset($this->cachedResponses[$auctionId][$requestCmd]);
        }
    }

    /**
     * @param string $requestCmd
     * @param int $auctionId
     * @return bool
     */
    protected function hasCachedResponse($requestCmd, $auctionId): bool
    {
        $has = !empty($this->cachedResponses[$auctionId])
            && !empty($this->cachedResponses[$auctionId][$requestCmd]);
        return $has;
    }

    /**
     * @param string $responseCmd
     * @return string|null
     */
    protected function getMappedRequestCommand($responseCmd): ?string
    {
        $map = array_merge($this->cachedCommandMap, self::IGNORED_REQUEST_COMMANDS);
        $requestCmd = $map[$responseCmd] ?? null;
        return $requestCmd;
    }
}
