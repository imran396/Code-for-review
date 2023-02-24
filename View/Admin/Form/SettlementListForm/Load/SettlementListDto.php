<?php
/**
 * SAM-9176: Apply DTO's for Settlement List page at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementListForm\Load;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementListDto
 * @package Sam\View\Admin\Form\SettlementListForm\Load
 */
class SettlementListDto extends CustomizableClass
{
    /**
     * @var string
     */
    public string $bphone = '';
    /**
     * @var float
     */
    public float $costTotal = 0.;
    /**
     * @var float
     */
    public float $exportTotal = 0.;
    /**
     * @var float
     */
    public float $feesCommTotal = 0.;
    /**
     * @var string
     */
    public string $firstName = '';
    /**
     * @var int
     */
    public int $id = 0;
    /**
     * @var string
     */
    public string $iphone = '';
    /**
     * @var string
     */
    public string $lastName = '';
    /**
     * @var float
     */
    public float $nonTaxableTotal = 0.;
    /**
     * @var string
     */
    public string $settlementDate = '';
    /**
     * @var int|null
     */
    public ?int $settlementNo = null;
    /**
     * @var int
     */
    public int $settlementStatusId = 0;
    /**
     * @var string
     */
    public string $sphone = '';
    /**
     * @var float
     */
    public float $taxableTotal = 0.;
    /**
     * @var float
     */
    public float $taxExclusive = 0.;
    /**
     * @var float
     */
    public float $taxInclusive = 0.;
    /**
     * @var float
     */
    public float $taxServices = 0.;
    /**
     * @var string
     */
    public string $username = '';

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $bphone
     * @param float $costTotal
     * @param float $exportTotal
     * @param float $feesCommTotal
     * @param string $firstName
     * @param int $id
     * @param string $iphone
     * @param string $lastName
     * @param float $nonTaxableTotal
     * @param string $settlementDate
     * @param int|null $settlementNo
     * @param int $settlementStatusId
     * @param string $sphone
     * @param float $taxableTotal
     * @param float $taxExclusive
     * @param float $taxInclusive
     * @param float $taxServices
     * @param string $username
     * @return $this
     */
    public function construct(
        string $bphone,
        float $costTotal,
        float $exportTotal,
        float $feesCommTotal,
        string $firstName,
        int $id,
        string $iphone,
        string $lastName,
        float $nonTaxableTotal,
        string $settlementDate,
        ?int $settlementNo,
        int $settlementStatusId,
        string $sphone,
        float $taxableTotal,
        float $taxExclusive,
        float $taxInclusive,
        float $taxServices,
        string $username
    ): static {
        $this->bphone = $bphone;
        $this->costTotal = $costTotal;
        $this->exportTotal = $exportTotal;
        $this->feesCommTotal = $feesCommTotal;
        $this->firstName = $firstName;
        $this->id = $id;
        $this->iphone = $iphone;
        $this->lastName = $lastName;
        $this->nonTaxableTotal = $nonTaxableTotal;
        $this->settlementDate = $settlementDate;
        $this->settlementNo = $settlementNo;
        $this->settlementStatusId = $settlementStatusId;
        $this->sphone = $sphone;
        $this->taxableTotal = $taxableTotal;
        $this->taxExclusive = $taxExclusive;
        $this->taxInclusive = $taxInclusive;
        $this->taxServices = $taxServices;
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
            (string)$row['bphone'],
            (float)$row['cost_total'],
            (float)$row['export_total'],
            (float)$row['fees_comm_total'],
            (string)$row['first_name'],
            (int)$row['id'],
            (string)$row['iphone'],
            (string)$row['last_name'],
            (float)$row['non_taxable_total'],
            (string)$row['settlement_date'],
            Cast::toInt($row['settlement_no'], Constants\Type::F_INT_POSITIVE),
            (int)$row['settlement_status_id'],
            (string)$row['sphone'],
            (float)$row['taxable_total'],
            (float)$row['tax_exclusive'],
            (float)$row['tax_inclusive'],
            (float)$row['tax_services'],
            (string)$row['username']
        );
    }
}
