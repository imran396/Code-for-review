<?php
/**
 * SAM-5883: Develop the architecture for the admin side translation
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Translation\Compiler\FileLoader;

/**
 * Trait TranslationFileLoaderAwareTrait
 * @package Sam\Translation\Compiler\FileLoader
 */
trait TranslationFileLoaderAwareTrait
{
    protected ?TranslationFileLoaderInterface $translationFileLoader = null;

    /**
     * @return TranslationFileLoaderInterface
     */
    protected function getTranslationFileLoader(): TranslationFileLoaderInterface
    {
        if ($this->translationFileLoader === null) {
            $this->translationFileLoader = CsvFileLoader::new();
        }
        return $this->translationFileLoader;
    }

    /**
     * @param TranslationFileLoaderInterface $translationFileLoader
     * @return static
     * @internal
     */
    public function setTranslationFileLoader(TranslationFileLoaderInterface $translationFileLoader): static
    {
        $this->translationFileLoader = $translationFileLoader;
        return $this;
    }
}
