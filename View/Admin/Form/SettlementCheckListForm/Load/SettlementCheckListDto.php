<?php
/**
 * SAM-9795: Check Printing for Settlements: Implementation of html layout and view layer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           10-27, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementCheckListForm\Load;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementCheckListDto
 * @package Sam\View\Admin\Form\SettlementCheckListForm
 */
class SettlementCheckListDto extends CustomizableClass
{
    /** @var string */
    public string $address = '';
    /** @var float */
    public float $amount = 0.;
    /** @var string */
    public string $amountSpelling = '';
    /** @var int|null */
    public ?int $checkNo = null;
    /** @var string */
    public string $clearedOn = '';
    /** @var string */
    public string $createdOn = '';
    /** @var string */
    public string $postedOn = '';
    /** @var string */
    public string $printedOn = '';
    /** @var string */
    public string $voidedOn = '';
    /** @var string */
    public string $modifiedOn = '';
    /** @var int|null */
    public ?int $id = null;
    /** @var string */
    public string $memo = '';
    /** @var string */
    public string $note = '';
    /** @var string */
    public string $payee = '';
    /** @var int */
    public int $settlementId;
    /** @var int|null */
    public ?int $settlementNo;
    /** @var int|null */
    public ?int $paymentId;
    /** @var float|null */
    public ?float $paymentAmount;
    /** @var int */
    public int $modifiedBy;
    /** @var int */
    public int $createdBy;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        int $id,
        int $settlementId,
        ?int $paymentId,
        ?int $checkNo,
        ?int $settlementNo,
        string $payee,
        float $amount,
        string $amountSpelling,
        string $memo,
        string $note,
        string $address,
        string $printedOn,
        string $postedOn,
        string $clearedOn,
        string $voidedOn,
        string $modifiedOn,
        string $createdOn,
        int $modifiedBy,
        int $createdBy,
        ?float $paymentAmount
    ): static {
        $this->id = $id;
        $this->settlementId = $settlementId;
        $this->settlementNo = $settlementNo;
        $this->paymentId = $paymentId;
        $this->checkNo = $checkNo;
        $this->payee = $payee;
        $this->amount = $amount;
        $this->amountSpelling = $amountSpelling;
        $this->memo = $memo;
        $this->note = $note;
        $this->address = $address;
        $this->printedOn = $printedOn;
        $this->postedOn = $postedOn;
        $this->clearedOn = $clearedOn;
        $this->voidedOn = $voidedOn;
        $this->modifiedOn = $modifiedOn;
        $this->createdOn = $createdOn;
        $this->modifiedBy = $modifiedBy;
        $this->createdBy = $createdBy;
        $this->paymentAmount = $paymentAmount;
        return $this;
    }

    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (int)$row['id'],
            (int)$row['settlement_id'],
            Cast::toInt($row['payment_id']),
            Cast::toInt($row['check_no']),
            Cast::toInt($row['settlement_no']),
            (string)$row['payee'],
            (float)$row['amount'],
            (string)$row['amount_spelling'],
            (string)$row['memo'],
            (string)$row['note'],
            (string)$row['address'],
            (string)$row['printed_on'],
            (string)$row['posted_on'],
            (string)$row['cleared_on'],
            (string)$row['voided_on'],
            (string)$row['modified_on'],
            (string)$row['created_on'],
            (int)$row['modified_by'],
            (int)$row['created_by'],
            Cast::toFloat($row['payment_amount'])
        );
    }
}
