<?php
/**
 * SAM-9688: Ability to make Auction and Lot/Item fields required to have values: Implementation (Developer)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\FieldConfig\Produce;

use Sam\Core\Service\CustomizableClass;

/**
 * Contains field config input data intended for updating config
 *
 * Class AuctionFieldConfigDto
 * @package Sam\Auction\FieldConfig\Produce
 */
class AuctionFieldConfigDto extends CustomizableClass
{
    /**
     * @var string
     */
    public string $key;
    /**
     * @var int
     */
    public int $order;
    /**
     * @var bool
     */
    public bool $visible;
    /**
     * @var bool
     */
    public bool $required;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(string $key, int $order, bool $visible, bool $required): static
    {
        $this->key = $key;
        $this->order = $order;
        $this->visible = $visible;
        $this->required = $required;
        return $this;
    }
}
