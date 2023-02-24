<?php
/**
 * SAM-9688: Ability to make Auction and Lot/Item fields required to have values: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\FieldConfig\Provider\Map;

/**
 * Interface for class that map fields to field config index
 *
 * Interface AuctionFieldMapInterface
 * @package Sam\Auction\FieldConfig\Provider\Map
 */
interface AuctionFieldMapInterface
{
    /**
     * Detect field config index
     *
     * @param string $field
     * @return string|null NULL if mapping not found
     */
    public function getFieldConfigIndex(string $field): ?string;

    /**
     * Check if the field is not affected by the "required" configuration option
     *
     * @param string $field
     * @return bool
     */
    public function isAlwaysOptionalField(string $field): bool;
}
