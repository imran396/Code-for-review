<?php
/**
 * SAM-6499: Refactor Settlement Calculator module (2020 year)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Calculate\Internal\Load\Dto;


use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementTotalsDto
 * @package Sam\Settlement\Calculate\Internal\Load\GetSettlementTotal
 * @internal
 */
class SettlementTotalsDto extends CustomizableClass
{
    public readonly float $hpTotal;
    public readonly float $commTotal;
    public readonly float $extraCharges;
    public readonly float $taxInclusive;
    public readonly float $taxExclusive;
    public readonly float $taxServices;
    public readonly float $costTotal;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string[] $data
     * @return static
     */
    public function fromDbRow(array $data): static
    {
        if (!isset($data['total_hp'], $data['total_comm'], $data['total_charge'], $data['tax_inclusive'], $data['tax_exclusive'], $data['tax_service'], $data['total_cost'])) {
            throw new \InvalidArgumentException(sprintf('Not all keys are set or null %s', var_export($data, true)));
        }

        $dto = static::new();

        $dto->hpTotal = (float)$data['total_hp'];
        $dto->commTotal = (float)$data['total_comm'];
        $dto->extraCharges = (float)$data['total_charge'];
        $dto->taxInclusive = (float)$data['tax_inclusive'];
        $dto->taxExclusive = (float)$data['tax_exclusive'];
        $dto->taxServices = (float)$data['tax_service'];
        $dto->costTotal = (float)$data['total_cost'];
        return $dto;
    }
}
