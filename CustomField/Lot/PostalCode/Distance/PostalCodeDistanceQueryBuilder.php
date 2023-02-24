<?php
/**
 * SAM-7715: Refactor \Lot_DistanceQuery
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\PostalCode\Distance;

use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Contains methods for building expressions for selecting and filtering lot items
 * in some area by lot item geo coordinates and distance
 *
 * Class PostalCodeDistanceQueryBuilder
 * @package Sam\CustomField\Lot\PostalCode\Distance
 */
class PostalCodeDistanceQueryBuilder extends CustomizableClass
{
    use DbConnectionTrait;

    public const DISTANCE_AREA_CIRCLE = 1;
    public const DISTANCE_AREA_RECTANGLE = 2;

    private const GEOLOCATION_TABLE = 'lot_item_geolocation';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Build where clause to filter by distance.
     *
     * @param float $latitude
     * @param float $longitude
     * @param int $radius In miles
     * @param string $joinTableAlias Alias for joining to lot_item_geolocation
     * @param int $areaType Distance area type. Use rectangle for performance optimization
     * @return string
     */
    public function buildWhereClause(
        float $latitude,
        float $longitude,
        int $radius,
        string $joinTableAlias = 'lig',
        int $areaType = self::DISTANCE_AREA_RECTANGLE
    ): string {
        $radiusEscaped = $this->escape($radius);
        $distanceExpression = ($areaType === self::DISTANCE_AREA_RECTANGLE)
            ? $this->buildDistanceExpressionForRectangleArea($latitude, $longitude, $joinTableAlias)
            : $this->buildDistanceExpressionForCircleArea($latitude, $longitude, $joinTableAlias);
        $whereClause = '(' . $distanceExpression . ' < ' . $radiusEscaped . ')';
        return $whereClause;
    }

    /**
     * Build clause for joining with geolocation table
     *
     * @param string $joinColumn
     * @param string $joinTableAlias Alias for joining to lot_item_geolocation
     * @param int|null $lotCustomFieldId lot_item_cust_field.id, if NULL don't filter by custom field
     * @return string
     */
    public function buildJoinClause(string $joinColumn, string $joinTableAlias = 'lig', ?int $lotCustomFieldId = null): string
    {
        $customFieldTableAlias = 'licf';
        $customDataTableAlias = 'licd';
        $condition = '';
        if ($lotCustomFieldId !== null) {
            $customFieldTableAlias .= $lotCustomFieldId;
            $customDataTableAlias .= $lotCustomFieldId;
            $condition = ' AND `' . $customFieldTableAlias . '`.id = ' . $this->escape($lotCustomFieldId);
        }

        $joinClause = "`" . self::GEOLOCATION_TABLE . "` AS `{$joinTableAlias}` ON `{$joinTableAlias}`.lot_item_id = {$joinColumn} " .
            "INNER JOIN lot_item_cust_data AS `{$customDataTableAlias}` ON " .
            "`{$customDataTableAlias}`.id = `{$joinTableAlias}`.lot_item_cust_data_id AND `{$customDataTableAlias}`.active " .
            "INNER JOIN lot_item_cust_field AS `{$customFieldTableAlias}` ON " .
            "`{$customFieldTableAlias}`.id = `{$customDataTableAlias}`.lot_item_cust_field_id AND `{$customFieldTableAlias}`.active" . $condition;
        return $joinClause;
    }

    /**
     * Return expression for selecting distance
     *
     * @param float $latitude
     * @param float $longitude
     * @param string $joinTableAlias Alias for joining to lot_item_geolocation
     * @param string $columnAlias
     * @param int $areaType
     * @return string
     */
    public function buildSelectExpression(
        float $latitude,
        float $longitude,
        string $joinTableAlias = 'lig',
        string $columnAlias = 'distance',
        int $areaType = self::DISTANCE_AREA_RECTANGLE
    ): string {
        $distanceExpression = ($areaType === self::DISTANCE_AREA_RECTANGLE)
            ? $this->buildDistanceExpressionForRectangleArea($latitude, $longitude, $joinTableAlias)
            : $this->buildDistanceExpressionForCircleArea($latitude, $longitude, $joinTableAlias);
        $selectExpression = $distanceExpression . ' AS ' . $columnAlias;
        return $selectExpression;
    }

    /**
     * Build expression for filtering distance by radius in having clause
     *
     * @param int $radius
     * @param string $columnAlias
     * @return string
     */
    public function buildHavingClause(int $radius, string $columnAlias = 'distance'): string
    {
        $radiusEscaped = $this->escape($radius);
        $havingClause = $columnAlias . ' < ' . $radiusEscaped;
        return $havingClause;
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @param string $joinTableAlias
     * @return string
     */
    protected function buildDistanceExpressionForCircleArea(float $latitude, float $longitude, string $joinTableAlias = 'lig'): string
    {
        $latitudeEsc = $this->escape($latitude);
        $longitudeEsc = $this->escape($longitude);
        return "
            ATAN2(
                SQRT(
                  POW(COS(RADIANS({$latitudeEsc})) *
                   SIN(RADIANS(`{$joinTableAlias}`.`longitude` - {$longitudeEsc})), 2) +
                  POW(COS(RADIANS(`{$joinTableAlias}`.`latitude`)) * SIN(RADIANS({$latitudeEsc})) -
                   SIN(RADIANS(`{$joinTableAlias}`.`latitude`)) * COS(RADIANS({$latitudeEsc})) *
                   COS(RADIANS(`{$joinTableAlias}`.`longitude` - {$longitudeEsc})), 2)),
                (SIN(RADIANS(`{$joinTableAlias}`.`latitude`)) * SIN(RADIANS({$latitudeEsc})) +
                 COS(RADIANS(`{$joinTableAlias}`.`latitude`)) * COS(RADIANS({$latitudeEsc})) *
                 COS(RADIANS(`{$joinTableAlias}`.`longitude` - {$longitudeEsc})))
            ) * 3956"; // miles
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @param string $joinTableAlias
     * @return string
     */
    protected function buildDistanceExpressionForRectangleArea(float $latitude, float $longitude, string $joinTableAlias = 'lig'): string
    {
        $latitudeEsc = $this->escape($latitude);
        $longitudeEsc = $this->escape($longitude);
        return "
            3956 * 2 * ASIN(
                SQRT(
                    POWER(SIN((`{$joinTableAlias}`.`latitude` - abs({$latitudeEsc})) * pi()/180 / 2), 2) +
                    COS(`{$joinTableAlias}`.`latitude` * pi()/180 ) *
                    COS(abs({$latitudeEsc}) * pi()/180) *
                    POWER(SIN((`{$joinTableAlias}`.`longitude` - {$longitudeEsc}) * pi()/180 / 2), 2)
                ))";
    }
}
