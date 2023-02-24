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

namespace Sam\Details\Lot\SeoUrl\Observe;

/**
 * Trait LotSeoUrlObservingHelperCreateTrait
 * @package Sam\Details\Lot\SeoUrl\Observe
 */
trait LotSeoUrlObservingHelperCreateTrait
{
    /**
     * @var LotSeoUrlObservingHelper|null
     */
    protected ?LotSeoUrlObservingHelper $lotSeoUrlObservingHelper = null;

    protected function createLotSeoUrlObservingHelper(): LotSeoUrlObservingHelper
    {
        return $this->lotSeoUrlObservingHelper ?: LotSeoUrlObservingHelper::new();
    }

    /**
     * @internal
     */
    public function setLotSeoUrlObservingHelper(LotSeoUrlObservingHelper $lotSeoUrlObservingHelper): static
    {
        $this->lotSeoUrlObservingHelper = $lotSeoUrlObservingHelper;
        return $this;
    }
}
