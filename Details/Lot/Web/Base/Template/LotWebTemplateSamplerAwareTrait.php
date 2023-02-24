<?php
/**
 * SAM-6595: Templated-content building - simplify module structure for v3.5
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 04, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Lot\Web\Base\Template;

/**
 * Trait LotWebTemplateSamplerAwareTrait
 * @package Sam\Details\Lot\Web\Base\Template
 */
trait LotWebTemplateSamplerAwareTrait
{
    /**
     * @var LotWebTemplateSampler|null
     */
    protected ?LotWebTemplateSampler $lotWebTemplateSampler = null;

    protected function getLotWebTemplateSampler(): LotWebTemplateSampler
    {
        if ($this->lotWebTemplateSampler === null) {
            $this->lotWebTemplateSampler = LotWebTemplateSampler::new();
        }
        return $this->lotWebTemplateSampler;
    }

    /**
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotWebTemplateSampler(LotWebTemplateSampler $lotWebTemplateSampler): static
    {
        $this->lotWebTemplateSampler = $lotWebTemplateSampler;
        return $this;
    }
}
