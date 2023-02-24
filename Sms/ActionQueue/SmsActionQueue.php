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

namespace Sam\Sms\ActionQueue;

use Sam\ActionQueue\ActionQueueManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SmsActionQueue
 * @package Sam\Sms\ActionQueue
 */
class SmsActionQueue extends CustomizableClass
{
    use ActionQueueManagerAwareTrait;

    protected const PRIORITY = Constants\ActionQueue::HIGH;
    protected const MAX_ATTEMPTS = 1;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function add(SmsActionQueueData $data, int $editorUserId, ?int $groupId = null): void
    {
        $this->getActionQueueManager()->addToQueue(
            SmsActionHandler::class,
            $data->serialize(),
            $editorUserId,
            null,
            $groupId,
            self::PRIORITY,
            self::MAX_ATTEMPTS
        );
    }
}
