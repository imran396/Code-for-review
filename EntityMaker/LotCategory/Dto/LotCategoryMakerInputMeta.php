<?php
/**
 * Describe fields and their properties for soap documentation and wsdl for entity-maker of LotCategory.
 *
 * SAM-8856: Lot category entity-maker module structural adjustments for v3-5
 * SAM-4048: LotCategory entity maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Feb 5, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotCategory\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * @package Sam\EntityMaker\LotCategory
 */
class LotCategoryMakerInputMeta extends CustomizableClass
{
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $active;
    /**
     * @var string
     * @soap-type-hint int
     */
    public $afterId;
    /**
     * @var string
     */
    public $afterName;
    /**
     * @var string
     * @soap-type-hint int
     */
    public $beforeId;
    /**
     * @var string
     */
    public $beforeName;
    /**
     * @var string
     * @soap-type-hint int
     */
    public $buyNowAmount;
    /**
     * @var string
     * @soap-type-hint int
     */
    public $consignmentCommission;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $first;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $hideEmptyFields;
    /**
     * @var string
     * @soap-type-hint int
     */
    public $id;
    /**
     * @var string
     */
    public $imageLink;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $last;
    /**
     * @var string
     * @soap-required
     */
    public $name;
    /**
     * @var string
     * @soap-type-hint int
     */
    public $parentId;
    /**
     * @var string
     */
    public $parentName;
    /**
     * @var string
     * @soap-type-hint int
     */
    public $startingBid;
    /**
     * @var string
     * @soap-type-hint int
     */
    public $quantityDigits;

    /**
     * Lot custom fields from lot_item_cust_data.name
     * @var array
     */
    protected $customFields = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
