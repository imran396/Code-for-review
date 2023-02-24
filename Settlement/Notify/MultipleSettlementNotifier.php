<?php
/**
 * SAM-4855: Settlement consignor by email notifier module
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           15.02.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Settlement\Notify;

use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Load\SettlementLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Settlement\Validate\SettlementRelatedEntityValidatorAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class MultipleSettlementNotifier
 * @package Sam\Settlement\Notify
 */
class MultipleSettlementNotifier extends CustomizableClass
{
    use SettlementRelatedEntityValidatorAwareTrait;
    use SettlementLoaderAwareTrait;
    use SingleSettlementNotifierAwareTrait;
    use UserLoaderAwareTrait;
    use ResultStatusCollectorAwareTrait;

    public const ERR_WITH_LOCKED_SETTLEMENTS = 1;
    public const ERR_NO_SETTLEMENT_SPECIFIED = 2;

    public const OK_NOTIFIED = 11;

    /** @var int[] */
    protected array $settlementIds;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Initialize object
     * @return static
     */
    public function initInstance(): static
    {
        $this->settlementIds = [];
        $successMessages = [
            self::OK_NOTIFIED => 'Settlement email has been sent!',
        ];

        $errorMessages = [
            self::ERR_WITH_LOCKED_SETTLEMENTS => 'Locked settlements #: %s.',
            self::ERR_NO_SETTLEMENT_SPECIFIED => 'No settlement has been selected to send email.',
        ];

        $this->getResultStatusCollector()->construct($errorMessages, $successMessages);
        return $this;
    }

    /**
     * @param int $editorUserId
     * @return bool
     */
    public function notify(int $editorUserId): bool
    {
        $this->getResultStatusCollector()->clear();

        $lockedSettlementNos = [];
        $sentCount = 0;
        $settlementsSentIds = [];
        if (count($this->settlementIds)) {
            $settlements = $this->getSettlementLoader()->loadEntities($this->settlementIds, true);
            foreach ($settlements as $settlement) {
                $singleNotifier = $this->getSingleSettlementNotifier();
                $singleNotifier->setPriority(Constants\ActionQueue::LOW);
                $singleNotifier->setSettlement($settlement);
                if ($singleNotifier->notify($editorUserId)) {
                    $settlementsSentIds[] = $settlement->Id;
                    $sentCount++;
                } elseif ($singleNotifier->hasSettlementLockedError()) {
                    $lockedSettlementNos[] = $settlement->SettlementNo;
                }
            }
        }

        // if there are no settlements has been sent - show warning:
        if (count($settlementsSentIds) === 0) {
            if ($lockedSettlementNos) {
                $this->getResultStatusCollector()->addErrorWithInjectedInMessageArguments(
                    self::ERR_WITH_LOCKED_SETTLEMENTS,
                    [implode(', ', $lockedSettlementNos)]
                );
            } else {
                $this->getResultStatusCollector()->addError(self::ERR_NO_SETTLEMENT_SPECIFIED);
            }
            return false;
        }

        $this->getResultStatusCollector()->addSuccess(self::OK_NOTIFIED);
        log_info($sentCount . ' Settlement email sent.');
        return true;
    }

    /**
     * @return string
     */
    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    /**
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    /**
     * @return int[]
     */
    public function getSettlementIds(): array
    {
        return $this->settlementIds;
    }

    /**
     * @param int[] $settlementIds
     * @return MultipleSettlementNotifier
     */
    public function setSettlementIds(array $settlementIds): MultipleSettlementNotifier
    {
        $this->settlementIds = ArrayCast::makeIntArray($settlementIds);
        return $this;
    }
}
