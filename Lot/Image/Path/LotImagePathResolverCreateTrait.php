<?php
/**
 * SAM-7845: Refactor \Lot_Image class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\Path;

/**
 * Trait LotImagePathResolverCreateTrait
 * @package Sam\Lot\Image\Helper
 */
trait LotImagePathResolverCreateTrait
{
    /**
     * @var LotImagePathResolver|null
     */
    protected ?LotImagePathResolver $lotImagePathResolver = null;

    /**
     * @return LotImagePathResolver
     */
    protected function createLotImagePathResolver(): LotImagePathResolver
    {
        return $this->lotImagePathResolver ?: LotImagePathResolver::new();
    }

    /**
     * @param LotImagePathResolver|null $lotImagePathResolver
     * @return static
     * @internal
     */
    public function setLotImagePathResolver(?LotImagePathResolver $lotImagePathResolver): static
    {
        $this->lotImagePathResolver = $lotImagePathResolver;
        return $this;
    }
}
