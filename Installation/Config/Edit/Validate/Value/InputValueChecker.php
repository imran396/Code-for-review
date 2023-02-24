<?php
/**
 * SAM-5306: Local installation correctness check
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 27, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Validate\Value;

use Sam\Core\Constants;
use Sam\Installation\Config\Edit\Transform\ParsingHelper;

/**
 * This class contains methods for checking the input string of a configuration parameter value.
 *
 * Class InputValueChecker
 * @package Sam\Installation\Config
 */
class InputValueChecker extends AbstractValueChecker
{
    use OptionValueCheckerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Checks that string is a representation of  items collection with a comma delimiter
     *
     * @param string $value
     * @return bool
     * @noinspection PhpUnused
     */
    public function substringDelimiterComa(string $value): bool
    {
        $is = false;
        if (!empty($value)) {
            $tokens = token_get_all('<?php ' . $value);
            $parsingHelper = ParsingHelper::new();
            $builtOutput = $parsingHelper->buildArrayFromTokens($tokens);
            $countComaCharInTokens = $parsingHelper->countComaCharInTokens($tokens);
            if (count($builtOutput) === ($countComaCharInTokens + 1)) {
                $is = true;
            }
        } elseif ($value === '') {
            $is = true;
        }
        return $is;
    }

    /**
     * Checks that all collection items at input string are quoted
     * @param string $value
     * @return bool
     * @noinspection PhpUnused
     */
    public function substringQuoted(string $value): bool
    {
        $is = false;
        if (!empty($value)) {
            $tokens = token_get_all('<?php ' . $value);
            $countUnquotedStringsInTokens = ParsingHelper::new()->countUnquotedStringsInTokens($tokens);
            if ($countUnquotedStringsInTokens === 0) {
                $is = true;
            }
        } elseif ($value === '') {
            $is = true;
        }
        return $is;
    }

    /**
     * Checks that the input string is the representation of the boolean type
     * @param mixed $value
     * @return bool
     * @noinspection PhpUnused
     */
    public function isBool(mixed $value): bool
    {
        return $value === '0' || $value === '1' || is_bool($value);
    }

    /**
     * Checks that the input string is the representation of the array type
     * @param mixed $value
     * @return bool
     * @noinspection PhpUnused
     */
    public function isArray(mixed $value): bool
    {
        if (is_string($value)) {
            $value = ParsingHelper::new()->buildArrayFromString($value);
        }

        return $this->createOptionValueChecker()->isArray($value);
    }

    /**
     * Checks that the input string is a valid subnet
     * @param mixed $value
     * @return bool
     * @noinspection PhpUnused
     */
    public function subnet(mixed $value): bool
    {
        if (is_string($value)) {
            $value = ParsingHelper::new()->buildArrayFromString($value);
        }

        return $this->createOptionValueChecker()->subnet($value);
    }

    /**
     * (@inheritDoc)
     */
    protected function getCheckerMap(): array
    {
        return [
            Constants\Installation::C_SUBSTRING_DELIMITER_COMA => [$this, 'substringDelimiterComa'],
            Constants\Installation::C_SUBSTRING_QUOTED => [$this, 'substringQuoted'],
            Constants\Installation::C_BOOL => [$this, 'isBool'],
            Constants\Installation::C_SUBNET => [$this, 'subnet'],
            Constants\Installation::C_IS_ARRAY => [$this, 'isArray'],
        ];
    }
}
