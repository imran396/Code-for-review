<?php
/**
 * SAM-5883: Develop the architecture for the admin side translation
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Translation;

/**
 * Trait TranslationDirectoryProviderAwareTrait
 * @package Sam\Translation
 */
trait TranslationDirectoryProviderAwareTrait
{
    protected ?TranslationDirectoryProvider $translationDirectoryProvider = null;

    /**
     * @return TranslationDirectoryProvider
     */
    protected function getTranslationDirectoryProvider(): TranslationDirectoryProvider
    {
        if ($this->translationDirectoryProvider === null) {
            $this->translationDirectoryProvider = TranslationDirectoryProvider::new();
        }
        return $this->translationDirectoryProvider;
    }

    /**
     * @param TranslationDirectoryProvider $translationDirectoryProvider
     * @return static
     * @internal
     */
    public function setTranslationDirectoryProvider(TranslationDirectoryProvider $translationDirectoryProvider): static
    {
        $this->translationDirectoryProvider = $translationDirectoryProvider;
        return $this;
    }
}
