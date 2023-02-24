<?php
/**
 * SAM-5739: RTB ping
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\Base;


use Sam\Core\Constants;

/**
 * Class ReversePing
 * @package Sam\Rtb\Command\Concrete\Base
 */
abstract class ReversePing extends CommandBase
{
    protected ?float $timestamp = null;

    public function execute(): void
    {
        $roundTripDuration = $this->getRoundTripDurationIsMilliseconds();
        $logData = ['u' => $this->getEditorUserId()];
        log_info("Round trip duration: {$roundTripDuration}ms." . composeSuffix($logData));
        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_REVERSE_PING_RESULT_S,
            Constants\Rtb::RES_DATA => [
                Constants\Rtb::RES_REVERSE_PING_DURATION => $roundTripDuration,
            ],
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_SINGLE] = $responseJson;

        $this->setResponses($responses);
    }

    /**
     * @return int
     */
    private function getRoundTripDurationIsMilliseconds(): int
    {
        $duration = microtime(true) - $this->timestamp;
        return (int)floor($duration * 1000);
    }

    /**
     * @param float $timestamp
     * @return static
     */
    public function setTimestamp(float $timestamp): static
    {
        $this->timestamp = $timestamp;
        return $this;
    }
}
