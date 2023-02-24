<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Consignor;

use Consignor;

class ConsignorWriteRepository extends AbstractConsignorWriteRepository
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function saveWithSelfModifier(Consignor $consignor): void
    {
        $this->saveEntityWithModifier($consignor, $consignor->UserId);
    }
}
