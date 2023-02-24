<?php
/**
 * Wrapper over general Translator with caching
 *
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

use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;

/**
 * Class Translator
 * @package
 */
class CachedTranslator extends CustomizableClass
{
    use TranslatorAwareTrait;

    private array $translates = [];
    private int $languageId;
    private int $systemAccountId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(int $systemAccountId, int $languageId): static
    {
        $this->systemAccountId = $systemAccountId;
        $this->languageId = $languageId;
        return $this;
    }

    public function translate(string $fieldKey, string $section): string
    {
        $key = $fieldKey . $section;
        if (!isset($this->translates[$key])) {
            $this->translates[$key] = $this->getTranslator()->translate(
                $fieldKey,
                $section,
                $this->systemAccountId,
                $this->languageId
            );
        }
        return $this->translates[$key];
    }

    public function translateByAuctionReverse(string $fieldKey, string $section, bool $isReverse = false): string
    {
        $key = $fieldKey . $section . (int)$isReverse;
        if (!isset($this->translates[$key])) {
            $this->translates[$key] = $this->getTranslator()->translateByAuctionReverse(
                $fieldKey,
                $section,
                $isReverse,
                $this->systemAccountId,
                $this->languageId
            );
        }
        return $this->translates[$key];
    }
}
