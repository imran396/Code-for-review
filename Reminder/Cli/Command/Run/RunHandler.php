<?php
/**
 * SAM-9734: Fix email reminder behavior for the case when last run timestamps are missed
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Reminder\Cli\Command\Run;

use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\LocalFileManager;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Reminder\Concrete\Payment\PaymentReminder;
use Sam\Reminder\Concrete\Pickup\PickupReminder;
use Sam\Reminder\Concrete\Registration\RegistrationReminder;

/**
 * Class ReminderFactory
 * @package Sam\Reminder
 */
class RunHandler extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * @var LocalFileManager
     */
    protected LocalFileManager $fileManager;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->cfg()->set('core->filesystem->managerClass', LocalFileManager::class);
        $this->fileManager = call_user_func([$this->cfg()->get('core->filesystem->managerClass'), 'new']);
        $this->fileManager->createDirPath(path()->logRun() . '/');
        return $this;
    }

    public function run(): void
    {
        $generalTsStart = $regRemStartTs = microtime(true);
        $regRemResult = RegistrationReminder::new()->run();
        $regRemEndTs = microtime(true);
        $regRemExecutionTimeMs = round(($regRemEndTs - $regRemStartTs) * 1000, 2);

        $paymentStartTs = microtime(true);
        $paymentReminderResult = PaymentReminder::new()->run();
        $paymentEndTs = microtime(true);
        $paymentExecutionTimeMs = round(($paymentEndTs - $paymentStartTs) * 1000, 2);

        $pickupStartTs = microtime(true);
        $pickupReminderResult = PickupReminder::new()->run();
        $paymentEndTs = $generalTsEnd = microtime(true);
        $pickUpExecutionTimeMs = round(($paymentEndTs - $pickupStartTs) * 1000, 2);

        $horizontalDelimiter = str_repeat('-   ', 30);

        if (!$regRemResult->isProcessed) {
            log_warning('Problems reminding users');
        }
        log_info(sprintf('Reminded %s users in %s auctions for registration', $regRemResult->countRemindedUsers, $regRemResult->countAuctions));
        log_info(composeLogData(['Execution time' => $regRemExecutionTimeMs . 'ms']));
        log_info($horizontalDelimiter);

        if (!$paymentReminderResult->isProcessed) {
            log_warning('Problems payment reminding users');
        }
        log_info(sprintf('Reminded %s users for invoice payment', $paymentReminderResult->countRemindedUsers));
        log_info(composeLogData(['Execution time' => $paymentExecutionTimeMs . 'ms']));
        log_info($horizontalDelimiter);

        if (!$pickupReminderResult->isProcessed) {
            log_warning('Problems pickup reminding users');
        }
        log_info(sprintf('Reminded %s users for invoice pickup', $pickupReminderResult->countRemindedUsers));
        log_info(composeLogData(['Execution time' => $pickUpExecutionTimeMs . 'ms']));
        log_info($horizontalDelimiter);

        $ms = round(($generalTsEnd - $generalTsStart) * 1000, 2);
        log_info(composeLogData(['General execution time' => $ms . 'ms']));
        log_info(str_repeat('-', 120));
    }
}
