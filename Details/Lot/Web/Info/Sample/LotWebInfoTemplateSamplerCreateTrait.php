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

namespace Sam\Details\Lot\Web\Info\Sample;

/**
 * Trait LotWebInfoTemplateSamplerCreateTrait
 * @package Sam\Details
 */
trait LotWebInfoTemplateSamplerCreateTrait
{
    /**
     * @var LotWebInfoTemplateSampler|null
     */
    protected ?LotWebInfoTemplateSampler $lotWebInfoTemplateSampler = null;

    protected function createLotWebInfoTemplateSampler(): LotWebInfoTemplateSampler
    {
        return $this->lotWebInfoTemplateSampler ?: LotWebInfoTemplateSampler::new();
    }

    /**
     * @internal
     */
    public function setLotWebInfoTemplateSampler(LotWebInfoTemplateSampler $lotWebInfoTemplateSampler): static
    {
        $this->lotWebInfoTemplateSampler = $lotWebInfoTemplateSampler;
        return $this;
    }
}
