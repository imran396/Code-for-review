<?php
/**
 * Password strength checker
 *
 * SAM-1238: Increased password security
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan, Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           4 May, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Password\Strength;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class FormHelper
 */
class FormHelper extends CustomizableClass
{
    use SettingsManagerAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return array of values, that should be imported for page's js logic
     * @return array
     */
    public function getImportValues(): array
    {
        $sm = $this->getSettingsManager();
        $values = [
            "minLength" => $sm->getForMain(Constants\Setting::PW_MIN_LEN),
            "minLetter" => $sm->getForMain(Constants\Setting::PW_MIN_LETTER),
            "mixedCase" => $sm->getForMain(Constants\Setting::PW_REQ_MIXED_CASE),
            "minNumber" => $sm->getForMain(Constants\Setting::PW_MIN_NUM),
            "minSpecialCharacter" => $sm->getForMain(Constants\Setting::PW_MIN_SPECIAL),
            "maxSequentialLetter" => $sm->getForMain(Constants\Setting::PW_MAX_SEQ_LETTER),
            "maxConsecutiveLetter" => $sm->getForMain(Constants\Setting::PW_MAX_CONSEQ_LETTER),
            "maxSequentialNumber" => $sm->getForMain(Constants\Setting::PW_MAX_SEQ_NUM),
            "maxConsecutiveNumber" => $sm->getForMain(Constants\Setting::PW_MAX_CONSEQ_NUM),
        ];
        return $values;
    }
}
