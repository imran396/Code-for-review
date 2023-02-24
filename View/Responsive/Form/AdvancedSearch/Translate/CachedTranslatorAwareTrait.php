<?php
/**
 * SAM-9416: Decouple logic of AdvancedSearch class for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 09, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\Translate;

/**
 * Trait TranslatorAwareTrait
 * @package
 */
trait CachedTranslatorAwareTrait
{
    protected ?CachedTranslator $cachedTranslator = null;

    /**
     * @return CachedTranslator
     */
    protected function getCachedTranslator(): CachedTranslator
    {
        if ($this->cachedTranslator === null) {
            $this->cachedTranslator = CachedTranslator::new();
        }
        return $this->cachedTranslator;
    }

    /**
     * @param CachedTranslator $translator
     * @return $this
     * @internal
     */
    public function setCachedTranslator(CachedTranslator $translator): static
    {
        $this->cachedTranslator = $translator;
        return $this;
    }
}
