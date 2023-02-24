<?php
/**
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 3, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Translate;

/**
 * Trait LotCustomFieldTranslationManagerAwareTrait
 * @package Sam\CustomField\Lot\Translate
 */
trait LotCustomFieldTranslationManagerAwareTrait
{
    /**
     * @var LotCustomFieldTranslationManager|null
     */
    protected ?LotCustomFieldTranslationManager $lotCustomFieldTranslationManager = null;

    /**
     * @return LotCustomFieldTranslationManager
     */
    protected function getLotCustomFieldTranslationManager(): LotCustomFieldTranslationManager
    {
        if ($this->lotCustomFieldTranslationManager === null) {
            $this->lotCustomFieldTranslationManager = LotCustomFieldTranslationManager::new();
        }
        return $this->lotCustomFieldTranslationManager;
    }

    /**
     * @param LotCustomFieldTranslationManager $lotCustomFieldTranslationManager
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotCustomFieldTranslationManager(LotCustomFieldTranslationManager $lotCustomFieldTranslationManager): static
    {
        $this->lotCustomFieldTranslationManager = $lotCustomFieldTranslationManager;
        return $this;
    }
}
