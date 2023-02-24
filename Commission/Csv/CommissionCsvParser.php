<?php
/**
 * SAM-9134: Refactor \User_CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Commission\Csv;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * Class CommissionCsvParser
 * @package Sam\Commission\Csv
 */
class CommissionCsvParser extends CustomizableClass
{
    use OptionalsTrait;

    protected const RANGES_DELIMITER = '|';
    protected const AMOUNT_DELIMITER = ':';

    public const OP_CSV_CLEAR_VALUE = 'csvClearValue';
    public const OP_CSV_DEFAULT_VALUE = 'csvDefaultValue';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Parse data in commissions/increments columns
     *
     * @param string $commissions
     * @return array Array of pairs [Amount, Percent/Increment]
     */
    public function parse(string $commissions): array
    {
        if (!$commissions) {
            return [];
        }
        if (in_array($commissions, [$this->fetchOptional(self::OP_CSV_CLEAR_VALUE), $this->fetchOptional(self::OP_CSV_DEFAULT_VALUE)], true)) {
            return [$commissions];
        }
        $result = [];
        foreach (explode(self::RANGES_DELIMITER, $commissions) as $key => $pair) {
            $pair = explode(self::AMOUNT_DELIMITER, $pair);
            $result[] = ($key == 0 && count($pair) === 1) ? [0, $pair[0]] : $pair;
        }
        return $result;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_CSV_CLEAR_VALUE] = $optionals[self::OP_CSV_CLEAR_VALUE]
            ?? static function (): string {
                return ConfigRepository::getInstance()->get('core->csv->clearValue');
            };
        $optionals[self::OP_CSV_DEFAULT_VALUE] = $optionals[self::OP_CSV_DEFAULT_VALUE]
            ?? static function (): string {
                return ConfigRepository::getInstance()->get('core->csv->defaultValue');
            };
        $this->setOptionals($optionals);
    }
}
