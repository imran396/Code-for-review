<?php
/**
 * SAM-8107: Issues related to Validation and Values of Buyer's Premium
 * SAM-9727: Refactor \Qform_BuyersPremiumHelper
 * SAM-10477: Reject assigning both BP rules on the same level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyersPremiumForm\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * Class BuyersPremiumDto
 * @package Sam\View\Admin\Form\BuyersPremiumForm\Dto
 */
class BuyersPremiumDto extends CustomizableClass
{
    public readonly ?int $id;
    public readonly string $name;
    public readonly string $shortName;
    public readonly string $calculationMethod;
    public readonly string $additionalBpInternet;
    public readonly array $ranges;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        ?int $id,
        string $name,
        string $shortName,
        string $calculationMethod,
        string $additionalBpInternet,
        array $ranges
    ): static {
        $this->id = $id;
        $this->name = trim($name);
        $this->shortName = trim($shortName);
        $this->calculationMethod = trim($calculationMethod);
        $this->additionalBpInternet = trim($additionalBpInternet);
        $this->ranges = $ranges;
        return $this;
    }
}
