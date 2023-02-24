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

namespace Sam\Details\Core\Sample;

/**
 * Trait CoreTemplateSamplerCreateTrait
 * @package Sam\Details\Core\Sample
 */
trait CoreTemplateSamplerAwareTrait
{
    /**
     * @var CoreTemplateSampler|null
     */
    protected ?CoreTemplateSampler $coreTemplateSampler = null;

    protected function getCoreTemplateSampler(): CoreTemplateSampler
    {
        if ($this->coreTemplateSampler === null) {
            $this->coreTemplateSampler = CoreTemplateSampler::new();
        }
        return $this->coreTemplateSampler;
    }

    /**
     * @internal
     */
    public function setCoreTemplateSampler(CoreTemplateSampler $coreTemplateSampler): static
    {
        $this->coreTemplateSampler = $coreTemplateSampler;
        return $this;
    }
}
