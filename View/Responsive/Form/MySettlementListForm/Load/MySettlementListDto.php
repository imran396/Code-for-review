<?php
/**
 * SAM-9322: Apply DTO for My Settlement List page at client side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\MySettlementListForm\Load;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class MySettlementListDto
 * @package Sam\View\Responsive\Form\MySettlementListForm\Load
 */
class MySettlementListDto extends CustomizableClass
{
    public readonly int $accountId;
    public readonly string $createdOn;
    public readonly int $id;
    public readonly int $saleId;
    public readonly string $settlementDate;
    public readonly ?int $settlementNo;
    public readonly ?int $settlementStatusId;
    public readonly int $totalAmt;
    public readonly string $username;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $accountId
     * @param string $createdOn
     * @param int $id
     * @param int $saleId
     * @param string $settlementDate
     * @param int|null $settlementNo
     * @param int|null $settlementStatusId
     * @param int $totalAmt
     * @param string $username
     * @return $this
     */
    public function construct(
        ?int $accountId,
        string $createdOn,
        int $id,
        int $saleId,
        string $settlementDate,
        ?int $settlementNo,
        ?int $settlementStatusId,
        int $totalAmt,
        string $username
    ): static {
        $this->accountId = $accountId;
        $this->createdOn = $createdOn;
        $this->id = $id;
        $this->saleId = $saleId;
        $this->settlementDate = $settlementDate;
        $this->settlementNo = $settlementNo;
        $this->settlementStatusId = $settlementStatusId;
        $this->totalAmt = $totalAmt;
        $this->username = $username;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (int)$row['account_id'],
            (string)$row['created_on'],
            (int)$row['id'],
            (int)$row['sale_id'],
            (string)$row['settlement_date'],
            Cast::toInt($row['settlement_no'], Constants\Type::F_INT_POSITIVE),
            Cast::toInt($row['settlement_status_id'], Constants\Type::F_INT_POSITIVE),
            (int)$row['total_amt'],
            (string)$row['username']
        );
    }
}
