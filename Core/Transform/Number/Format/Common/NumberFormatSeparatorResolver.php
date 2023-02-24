<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Transform\Number\Format\Common;

use Sam\Core\Service\CustomizableClass;

/**
 * Class NumberFormatConfigurator
 * @package Sam\Core\Transform\Number\Format\Common
 */
class NumberFormatSeparatorResolver extends CustomizableClass
{
    /** @var string[] */
    protected const DECIMAL_SEPARATORS = [
        false => ',',
        true => '.',
    ];

    /** @var string[] */
    protected const THOUSAND_SEPARATORS = [
        false => '.',
        true => ',',
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return decimal separator for US Number Formatting setting.
     * @param bool $isUsNumberFormatting
     * @return non-empty-string
     */
    public function decimalSeparator(bool $isUsNumberFormatting): string
    {
        return self::DECIMAL_SEPARATORS[$isUsNumberFormatting];
    }

    /**
     * Return thousand separator for US Number Formatting setting.
     * @param bool $isUsNumberFormatting
     * @return non-empty-string
     */
    public function thousandSeparator(bool $isUsNumberFormatting): string
    {
        return self::THOUSAND_SEPARATORS[$isUsNumberFormatting];
    }

    /**
     * Return array with thousand and decimal separators for US Number Formatting setting.
     * @param bool $isUsNumberFormatting
     * @return array{0: non-empty-string, 1: non-empty-string}
     */
    public function thousandAndDecimalSeparators(bool $isUsNumberFormatting): array
    {
        return [
            $this->thousandSeparator($isUsNumberFormatting),
            $this->decimalSeparator($isUsNumberFormatting)
        ];
    }
}
