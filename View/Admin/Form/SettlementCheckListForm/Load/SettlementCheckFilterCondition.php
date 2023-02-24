<?php
/**
 * SAM-9889: Check Printing for Settlements: Searching, Filtering, Listing Checks (Part 3)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementCheckListForm\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementCheckFilterCondition
 * @package Sam\View\Admin\Form\SettlementCheckListForm
 */
class SettlementCheckFilterCondition extends CustomizableClass
{
    /** @var int[] */
    public readonly array $accountIds;
    /** @var int[] */
    public readonly array $settlementIds;
    /** @var int[] */
    public readonly array $status;
    public readonly string $checkNo;
    public readonly string $payee;
    public readonly string $createdOnFrom;
    public readonly string $createdOnTo;
    public readonly string $printedOnFrom;
    public readonly string $printedOnTo;
    public readonly string $postedOnFrom;
    public readonly string $postedOnTo;
    public readonly string $clearedOnFrom;
    public readonly string $clearedOnTo;
    public readonly string $voidedOnFrom;
    public readonly string $voidedOnTo;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int[] $accountIds
     * @param int[] $settlementIds
     * @param int[] $status
     * @param string $checkNo
     * @param string $payee
     * @param string $createdOnFrom
     * @param string $createdOnTo
     * @param string $printedOnFrom
     * @param string $printedOnTo
     * @param string $postedOnFrom
     * @param string $postedOnTo
     * @param string $clearedOnFrom
     * @param string $clearedOnTo
     * @param string $voidedOnFrom
     * @param string $voidedOnTo
     * @return static
     */
    public function construct(
        array $accountIds = [],
        array $settlementIds = [],
        array $status = [],
        string $checkNo = '',
        string $payee = '',
        string $createdOnFrom = '',
        string $createdOnTo = '',
        string $printedOnFrom = '',
        string $printedOnTo = '',
        string $postedOnFrom = '',
        string $postedOnTo = '',
        string $clearedOnFrom = '',
        string $clearedOnTo = '',
        string $voidedOnFrom = '',
        string $voidedOnTo = '',
    ): static {
        $this->accountIds = $accountIds;
        $this->settlementIds = $settlementIds;
        $this->status = $status;
        $this->checkNo = $checkNo;
        $this->payee = $payee;
        $this->createdOnFrom = $createdOnFrom;
        $this->createdOnTo = $createdOnTo;
        $this->printedOnFrom = $printedOnFrom;
        $this->printedOnTo = $printedOnTo;
        $this->postedOnFrom = $postedOnFrom;
        $this->postedOnTo = $postedOnTo;
        $this->clearedOnFrom = $clearedOnFrom;
        $this->clearedOnTo = $clearedOnTo;
        $this->voidedOnFrom = $voidedOnFrom;
        $this->voidedOnTo = $voidedOnTo;
        return $this;
    }
}
