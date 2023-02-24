<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\SettlementCheck\Status;

use DateTime;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementCheckStatusPureChecker
 * @package Sam\Core\Entity\Model\SettlementCheck\Status
 */
class SettlementCheckStatusPureChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isCheckNo(?int $checkNo): bool
    {
        return $checkNo > 0;
    }

    public function isPaymentId(?int $paymentId): bool
    {
        return $paymentId > 0;
    }

    public function isCreated(?string $createdOnIso): bool
    {
        return (bool)$createdOnIso;
    }

    public function isPrintedByDate(?DateTime $printedOn): bool
    {
        return (bool)$printedOn;
    }

    public function isPrintedByIso(?string $printedOnIso): bool
    {
        return (string)$printedOnIso !== '';
    }

    public function isPostedByDate(?DateTime $postedOn): bool
    {
        return (bool)$postedOn;
    }

    public function isPostedByIso(?string $postedOnIso): bool
    {
        return (string)$postedOnIso !== '';
    }

    public function isClearedByDate(?DateTime $clearedOn): bool
    {
        return (bool)$clearedOn;
    }

    public function isClearedByIso(?string $clearedOnIso): bool
    {
        return (string)$clearedOnIso !== '';
    }

    public function isVoidedByDate(?DateTime $voidedOn): bool
    {
        return (bool)$voidedOn;
    }

    public function isVoidedByIso(?string $voidedOnIso): bool
    {
        return (string)$voidedOnIso !== '';
    }

    public function detectStatusByIso(
        ?string $createdOn,
        ?string $printedOn,
        ?string $postedOn,
        ?string $clearedOn,
        ?string $voidedOn
    ): int {
        if ($voidedOn) {
            return Constants\SettlementCheck::S_VOIDED;
        }

        if ($clearedOn) {
            return Constants\SettlementCheck::S_CLEARED;
        }

        if ($postedOn) {
            return Constants\SettlementCheck::S_POSTED;
        }

        if ($printedOn) {
            return Constants\SettlementCheck::S_PRINTED;
        }

        if ($createdOn) {
            return Constants\SettlementCheck::S_CREATED;
        }

        return Constants\SettlementCheck::S_NONE;
    }

    public function detectStatusByDate(
        ?DateTime $createdOn,
        ?DateTime $printedOn,
        ?DateTime $postedOn,
        ?DateTime $clearedOn,
        ?DateTime $voidedOn
    ): int {
        $createdOnIso = $createdOn?->format(Constants\Date::ISO_DATE);
        $printedOnIso = $printedOn?->format(Constants\Date::ISO_DATE);
        $postedOnIso = $postedOn?->format(Constants\Date::ISO_DATE);
        $clearedOnIso = $clearedOn?->format(Constants\Date::ISO_DATE);
        $voidedOnIso = $voidedOn?->format(Constants\Date::ISO_DATE);

        return $this->detectStatusByIso(
            $createdOnIso,
            $printedOnIso,
            $postedOnIso,
            $clearedOnIso,
            $voidedOnIso
        );
    }

}
