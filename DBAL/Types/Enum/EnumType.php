<?php

namespace Sam\DBAL\Types\Enum;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class EnumType extends Type
{
    protected string $name = 'enumType';

    public function getDefinition($columnName): array
    {
        // default values stay only for reference, they are not used.
        $foundEnumDefinition = match ($columnName) {
            'access',
            'auction_catalog_access',
            'auction_info_access',
            'auction_visibility_access',
            'live_view_access',
            'lot_bidding_history_access',
            'lot_bidding_info_access',
            'lot_details_access',
            'lot_starting_bid_access',
            'lot_winning_bid_access' => [
                'values' => [
                    'ADMIN',
                    'CONSIGNOR',
                    'BIDDER',
                    'USER',
                    'VISITOR'
                ],
                'default' => 'VISITOR'
            ],
            'bp_range_calculation', 'bp_range_calculation_live', 'bp_range_calculation_timed', 'bp_range_calculation_hybrid', 'range_calculation' => [
                'values' => [
                    'sliding',
                    'tiered'
                ],
                'default' => 'sliding'
            ],
            'mode' => [
                'values' => ['+', '>'],
                'default' => null
            ],
            /* v.3.6 compatability */
            'bid_type' => [
                'values' => ['REGULAR', 'PHONE'],
                'default' => 'REGULAR'
            ],
            'entity_type_old' => [
                'values' => ['lot_item', 'user', 'invoice', 'settlement', 'draft', 'auction'],
                'default' => 'lot_item'
            ],
            /* end v.3.6 */
            default => [
                'values' => null,
                'default' => null
            ]
        };
        return [$foundEnumDefinition['values'], $foundEnumDefinition['default']];
    }


    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        if (!$this->getDefinition($column['name'])) {
            throw new \InvalidArgumentException("Invalid '" . $column['name'] . "' for enum.");
        }

        [$enumValues] = $this->getDefinition($column['name']);
        $values = array_map(function ($val) {
            return "'" . $val . "'";
        }, $enumValues);

        return "ENUM(" . implode(", ", $values) . ")";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): string
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
