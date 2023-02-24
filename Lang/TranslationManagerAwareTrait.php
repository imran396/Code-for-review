<?php
/**
 * Trait for Translation Manager
 *
 * SAM-4449 : Language label translation modules
 * https://bidpath.atlassian.net/browse/SAM-4449
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/2/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lang;

/**
 * Trait TranslationManagerAwareTrait
 * @package Sam\Lang
 */
trait TranslationManagerAwareTrait
{
    /**
     * @var TranslationManager|null
     */
    protected ?TranslationManager $translationManager = null;

    /**
     * Singleton case aware trait works similar way to <Service>CreateTrait pattern
     * @return TranslationManager
     */
    protected function getTranslationManager(): TranslationManager
    {
        return $this->translationManager ?: TranslationManager::getInstance();
    }

    /**
     * @param TranslationManager $translationManager
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setTranslationManager(TranslationManager $translationManager): static
    {
        $this->translationManager = $translationManager;
        return $this;
    }
}
