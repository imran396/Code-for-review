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

namespace Sam\Reminder\Concrete\Registration;

use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Reminder\Common\Dto\ProcessingInput;
use Sam\Reminder\Common\Dto\ProcessingResult;
use Sam\Reminder\Concrete\Registration\Internal\Expiry\ExpirationDateRepository;
use Sam\Reminder\Concrete\Registration\Internal\Process\Processor;

/**
 * Class RegistrationReminder
 * @package Sam\Reminder\Concrete\Registration
 */
class RegistrationReminder extends CustomizableClass
{
    use CurrentDateTrait;
    use ConfigRepositoryAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function run(): ProcessingResult
    {
        $reminderLastRunUtc = ExpirationDateRepository::new()->readLastRunDate();
        $input = ProcessingInput::new()->construct(
            $this->getCurrentDateUtc(),
            (int)$this->cfg()->get('core->reminder->registration->interval'),
            $reminderLastRunUtc,
            null
        );
        // generate auction registration reminders
        $result = Processor::new()->process($input);
        if ($result->isProcessed) {
            ExpirationDateRepository::new()->writeLastRunDate($result->lastRunUtc, $reminderLastRunUtc);
        }
        return $result;
    }
}
