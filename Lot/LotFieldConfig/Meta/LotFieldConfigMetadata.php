<?php
/**
 * SAM-9741: Admin options Inventory page - Add "Required" property for all fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Meta;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotFieldConfigMetadata
 * @package Sam\Lot\LotFieldConfig\Meta
 */
class LotFieldConfigMetadata extends CustomizableClass
{
    public bool $alwaysRequired = false;
    public bool $alwaysVisible = false;
    public bool $requirable = false;
    public bool $required = false;
    public string $relDir = '';
    public string $relIndex = '';
    public string $title = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        string $relIndex,
        string $relDir,
        bool $required,
        bool $requirable,
        bool $alwaysRequired,
        bool $alwaysVisible
    ): static {
        $this->relIndex = $relIndex;
        $this->relDir = $relDir;
        $this->required = $required;
        $this->requirable = $requirable;
        $this->alwaysRequired = $alwaysRequired;
        $this->alwaysVisible = $alwaysVisible;
        return $this;
    }
}
