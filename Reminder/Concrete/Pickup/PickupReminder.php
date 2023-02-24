<?php
/**
 * SAM-4465 : Refactor reminder classes
 * https://bidpath.atlassian.net/browse/SAM-4465
 *
 * @author        Imran Rahman
 * @version       SVN: $Id: $
 * @since         Sept 30, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 */

namespace Sam\Reminder\Concrete\Pickup;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Reminder\Common\Dto\ProcessingInput;
use Sam\Reminder\Concrete\Pickup\Internal\Expiry\ExpirationDateRepository;
use Sam\Reminder\Common\Dto\ProcessingResult;
use Sam\Reminder\Concrete\Pickup\Internal\Process\Processor;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class PickupReminder
 * @package Sam\Reminder\Concrete\Pickup
 */
class PickupReminder extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use SettingsManagerAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function run(): ProcessingResult
    {
        $emailFrequency = (int)$this->getSettingsManager()
            ->getForMain(Constants\Setting::PICKUP_REMINDER_EMAIL_FREQUENCY);
        $reminderLastRunUtc = ExpirationDateRepository::new()->readLastRunDateUtc($emailFrequency);
        $input = ProcessingInput::new()->construct(
            $this->getCurrentDateUtc(),
            (int)$this->cfg()->get('core->reminder->pickup->interval'),
            $reminderLastRunUtc,
            $emailFrequency,
        );

        $result = Processor::new()->process($input);
        if ($result->isProcessed) {
            ExpirationDateRepository::new()->writeLastRun($result->lastRunUtc, $reminderLastRunUtc);
        }
        return $result;
    }
}
