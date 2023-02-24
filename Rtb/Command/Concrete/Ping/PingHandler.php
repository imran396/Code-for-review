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

namespace Sam\Rtb\Command\Concrete\Ping;


use Sam\Core\Constants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Rtb\Command\Concrete\Base\CommandBase;
use Sam\Rtb\Live\HelpersAwareTrait;

class PingHandler extends CommandBase
{
    use HelpersAwareTrait;
    use OptionalsTrait;

    public const OP_PING_DROP_PERCENT = 'dp';

    private PingCommand $command;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param PingCommand $command
     * @param array $optionals = [
     *     self::OP_PING_DROP_PERCENT => int
     * ]
     * @return static
     */
    public function construct(PingCommand $command, array $optionals = []): static
    {
        $this->command = $command;
        $this->initOptionals($optionals);
        return $this;
    }

    public function execute(): void
    {
    }

    protected function createResponses(): void
    {
        if ($this->shouldDrop()) {
            log_warning('Ping was dropped.' . composeSuffix(['dp' => $this->fetchPingDropPercent()]));
            $this->setResponses([]);
            return;
        }

        $response = [
            Constants\Rtb::RES_COMMAND => Constants\Rtb::CMD_PING_S,
            Constants\Rtb::RES_DATA => [
                Constants\Rtb::RES_PING_TS => $this->command->getTimestamp(),
            ],
        ];
        $responseJson = json_encode($response);
        $responses[Constants\Rtb::RT_SINGLE] = $responseJson;

        $this->setResponses($responses);
    }

    /**
     * @return bool
     */
    protected function shouldDrop(): bool
    {
        $dropPercent = $this->fetchPingDropPercent();
        if ($dropPercent === 0) {
            return false;
        }

        return random_int(0, 100) <= $dropPercent;
    }

    /**
     * @return int
     */
    protected function fetchPingDropPercent(): int
    {
        return $this->fetchOptional(self::OP_PING_DROP_PERCENT);
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_PING_DROP_PERCENT] = $optionals[self::OP_PING_DROP_PERCENT]
            ?? static function () {
                return ConfigRepository::getInstance()->get('core->rtb->ping->drop');
            };
        $this->setOptionals($optionals);
    }
}
