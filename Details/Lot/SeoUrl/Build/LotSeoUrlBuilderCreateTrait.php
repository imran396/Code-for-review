<?php
/**
 * SAM-6595: Templated-content building - simplify module structure for v3.5
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 03, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Lot\SeoUrl\Build;

/**
 * Trait LotSeoUrlBuilderCreateTrait
 * @package
 */
trait LotSeoUrlBuilderCreateTrait
{
    /**
     * @var LotSeoUrlBuilder|null
     */
    protected ?LotSeoUrlBuilder $lotSeoUrlBuilder = null;

    protected function createLotSeoUrlBuilder(): LotSeoUrlBuilder
    {
        return $this->lotSeoUrlBuilder ?: LotSeoUrlBuilder::new();
    }

    /**
     * @internal
     */
    public function setLotSeoUrlBuilder(LotSeoUrlBuilder $lotSeoUrlBuilder): static
    {
        $this->lotSeoUrlBuilder = $lotSeoUrlBuilder;
        return $this;
    }
}
