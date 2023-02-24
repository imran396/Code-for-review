<?php
/**
 * SAM-5739: RTB ping
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 08, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\ReversePing;


use Sam\Core\Constants;
use Sam\Rtb\Command\Concrete\Base\CommandBase;
use Sam\Rtb\Live\HelpersAwareTrait;

/**
 * Class ReversePingHandler
 * @package Sam\Rtb\Command\Concrete\ReversePing
 */
class ReversePingHandler extends CommandBase
{
    use HelpersAwareTrait;

    protected ReversePingCommand $command;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param ReversePingCommand $command
     * @return static
     */
    public function construct(ReversePingCommand $command): static
    {
        $this->command = $command;
        return $this;
    }

    public function execute(): void
    {
    }

    protected function createResponses(): void
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
    protected function getRoundTripDurationIsMilliseconds(): int
    {
        $duration = microtime(true) - $this->command->getTimestamp();
        return (int)floor($duration * 1000);
    }
}
