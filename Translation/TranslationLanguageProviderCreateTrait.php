<?php
/**
 * SAM-5883: Develop the architecture for the admin side translation
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Translation;

/**
 * Trait TranslationLanguageProviderCreateTrait
 * @package Sam\Translation
 */
trait TranslationLanguageProviderCreateTrait
{
    protected ?TranslationLanguageProvider $translationLanguageProvider = null;

    /**
     * @return TranslationLanguageProvider
     */
    protected function createTranslationLanguageProvider(): TranslationLanguageProvider
    {
        return $this->translationLanguageProvider ?: TranslationLanguageProvider::new();
    }

    /**
     * @param TranslationLanguageProvider $translationLanguageProvider
     * @return static
     * @internal
     */
    public function setTranslationLanguageProvider(TranslationLanguageProvider $translationLanguageProvider): static
    {
        $this->translationLanguageProvider = $translationLanguageProvider;
        return $this;
    }
}
