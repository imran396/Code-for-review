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
 * Class BuyersPremiumCalculationMethodTranslator
 * @package Sam\BuyersPremium\Translate
 */
class BuyersPremiumCalculationMethodTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    private const TRANSLATION_KEYS = [
        Constants\BuyersPremium::RANGE_CALC_SLIDING => 'calculation_method.sliding.label',
        Constants\BuyersPremium::RANGE_CALC_CUMULATIVE_TIERED => 'calculation_method.tiered.label'
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
     * @param string $method
     * @return string
     */
    public function trans(string $method, array $params = [], string $domain = null, string $language = null): string
    {
        $translation = '';
        $key = self::TRANSLATION_KEYS[$method] ?? null;
        if ($key) {
            $translation = $this->getAdminTranslator()->trans($key, $params, $domain, $language);
        }
        return $translation;
    }
}
