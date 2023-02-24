<?php
/**
 * SAM-10477: Reject assigning both BP rules on the same level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Translate;

use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\Core\Constants;

/**
 * Class BuyersPremiumRangeModeTranslator
 * @package Sam\BuyersPremium\Translate
 */
class BuyersPremiumRangeModeTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    private const TRANSLATION_KEYS = [
        Constants\BuyersPremium::MODE_NAME_SUM => 'list.mode.sum_of_fixed_and_percentage',
        Constants\BuyersPremium::MODE_NAME_GREATER => 'list.mode.greater_of_fixed_and_percentage'
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $mode
     * @return string
     */
    public function trans(string $mode, array $params = [], string $domain = null, string $language = null): string
    {
        $translation = '';
        $key = self::TRANSLATION_KEYS[$mode] ?? null;
        if ($key) {
            $translation = $this->getAdminTranslator()->trans($key, $params, $domain, $language);
        }
        return $translation;
    }
}
