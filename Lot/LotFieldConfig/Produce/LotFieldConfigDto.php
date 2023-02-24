<?php
/**
 * SAM-9688: Ability to make Auction and Lot/Item fields required to have values: Implementation (Developer)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Produce;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotFieldConfigDto
 * @package Sam\Lot\LotFieldConfig\Produce
 */
class LotFieldConfigDto extends CustomizableClass
{
    public bool $required;
    public bool $visible;
    public int $order;
    public string $key;

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
