<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
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

namespace Sam\Settlement\Check\Action\Edit\Single\Common\Input;

use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementCheckEditingInput
 * @package Sam\Settlement\Check
 */
class SettlementCheckEditingInput extends CustomizableClass
{
    public readonly ?int $settlementCheckId;
    public readonly ?int $settlementId;
    public readonly int $editorUserId;
    public readonly int $systemAccountId;
    public readonly ?string $checkNo;
    public readonly ?string $payee;
    public readonly ?string $amount;
    public readonly ?string $amountSpelling;
    public readonly ?string $memo;
    public readonly ?string $note;
    public readonly ?string $address;
    public readonly ?string $postedOnSysIso;
    public readonly ?string $clearedOnSysIso;
    public readonly bool $isDropPrintedOn;
    public readonly bool $isDropVoidedOn;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $systemAccountId
     * @param int $editorUserId
     * @param int|null $settlementCheckId
     * @param int|null $settlementId
     * @param string $checkNo
     * @param string $payee
     * @param string $amount
     * @param string $amountSpelling
     * @param string $memo
     * @param string $note
     * @param string $address
     * @param string|null $postedOnSysIso
     * @param string|null $clearedOnSysIso
     * @param bool $isDropPrintedOn
     * @param bool $isDropVoidedOn
     * @return $this
     */
    public function construct(
        int $systemAccountId,
        int $editorUserId,
        ?int $settlementCheckId,
        ?int $settlementId,
        ?string $checkNo,
        ?string $payee,
        ?string $amount,
        ?string $amountSpelling,
        ?string $memo,
        ?string $note,
        ?string $address,
        ?string $postedOnSysIso,
        ?string $clearedOnSysIso,
        bool $isDropPrintedOn,
        bool $isDropVoidedOn
    ): static {
        $this->systemAccountId = $systemAccountId;
        $this->editorUserId = $editorUserId;
        $this->settlementCheckId = $settlementCheckId;
        $this->settlementId = $settlementId;
        $this->checkNo = $checkNo;
        $this->payee = $payee;
        $this->amount = $amount;
        $this->amountSpelling = $amountSpelling;
        $this->memo = $memo;
        $this->note = $note;
        $this->address = $address;
        $this->postedOnSysIso = $postedOnSysIso;
        $this->clearedOnSysIso = $clearedOnSysIso;
        $this->isDropPrintedOn = $isDropPrintedOn;
        $this->isDropVoidedOn = $isDropVoidedOn;
        return $this;
    }
}
