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

namespace Sam\Auction\FieldConfig\Meta;

use Sam\Core\Service\CustomizableClass;

/**
 * DTO that contains metadata of auction field config.
 * The source is the _configuration/fieldConfig.php config file.
 *
 * Class AuctionFieldConfigMetadata
 * @package Sam\Auction\FieldConfig\Meta
 */
class AuctionFieldConfigMetadata extends CustomizableClass
{
    public string $relIndex;
    public bool $requirable;
    public bool $alwaysRequired;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(string $relIndex, bool $requirable, bool $alwaysRequired): static
    {
        $this->relIndex = $relIndex;
        $this->requirable = $requirable;
        $this->alwaysRequired = $alwaysRequired;
        return $this;
    }
}
