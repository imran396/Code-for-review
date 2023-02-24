<?php
/**
 * SAM-6853: Settings > System Parameters > User options - "Auto assign Preferred bidder privileges upon credit card update" condition is not working properly
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Save\Internal\AutoPreferredCreditCard\Internal\Check;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\Mode;

/**
 * Class EffectCheckingInput
 * @package Sam\EntityMaker\User\Save\Internal\AutoPreferredCreditCard\Internal\Check
 */
class AutoPreferredCreditCardEffectCheckingInput extends CustomizableClass
{
    public readonly Mode $mode;
    public readonly bool $willTargetUserHaveBidderRole;
    public readonly string $actualCcNumberHash;
    public readonly string $actualCcExpDate;
    /**
     * It is CreditCard->Id
     */
    public readonly ?int $actualCcType;
    public readonly ?string $inputCcNumber;
    public readonly ?string $inputCcExpDate;
    /**
     * It is CreditCard->Name
     */
    public readonly ?string $inputCcType;
    public readonly bool $isSetCcNumber;
    public readonly bool $isSetCcExpDate;
    public readonly bool $isSetCcType;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Mode $mode
     * @param bool $willTargetUserHaveBidderRole
     * @param string $actualCcNumberHash
     * @param string $actualCcExpDate
     * @param int|null $actualCcType
     * @param string|null $inputCcNumber
     * @param string|null $inputCcExpDate
     * @param string|null $inputCcType
     * @param bool $isSetCcNumber
     * @param bool $isSetCcExpDate
     * @param bool $isSetCcType
     * @return $this
     */
    public function construct(
        Mode $mode,
        bool $willTargetUserHaveBidderRole,
        string $actualCcNumberHash,
        string $actualCcExpDate,
        ?int $actualCcType,
        ?string $inputCcNumber,
        ?string $inputCcExpDate,
        ?string $inputCcType,
        bool $isSetCcNumber,
        bool $isSetCcExpDate,
        bool $isSetCcType
    ): static {
        $this->mode = $mode;
        $this->willTargetUserHaveBidderRole = $willTargetUserHaveBidderRole;
        $this->actualCcNumberHash = $actualCcNumberHash;
        $this->actualCcExpDate = $actualCcExpDate;
        $this->actualCcType = $actualCcType;
        $this->inputCcNumber = $inputCcNumber;
        $this->inputCcExpDate = $inputCcExpDate;
        $this->inputCcType = $inputCcType;
        $this->isSetCcNumber = $isSetCcNumber;
        $this->isSetCcExpDate = $isSetCcExpDate;
        $this->isSetCcType = $isSetCcType;
        return $this;
    }
}
