<?php
/**
 * SAM-9561: Refactor support logger
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Log\Text;

use DateTimeInterface;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LogDataComposer
 * @package Sam\Log
 */
class LogDataComposer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Create ' (key1: value1, key2: value2, ...)' or ' ($parts)' string from $parts
     * @param mixed $parts
     * @param string $separator
     * @return string
     */
    public function composeSuffix(mixed $parts, string $separator = ', '): string
    {
        $output = $this->composeLogData($parts, $separator);
        if ($output) {
            $output = ' (' . $output . ')';
        }
        return $output;
    }

    /**
     * @param mixed $parts
     * @param string $separator
     * @return string
     */
    public function composeLogData(mixed $parts, string $separator = ', '): string
    {
        if (is_array($parts)) {
            $results = [];
            foreach ($parts as $key => $input) {
                if ($input instanceof DateTimeInterface) {
                    $output = $input->format(Constants\Date::ISO_TZ);
                } elseif (is_array($input)) {
                    $output = json_encode($input);
                } elseif (is_bool($input)) {
                    $output = (int)$input;
                } elseif (is_object($input)) {
                    $output = json_encode($input);
                } elseif (is_null($input)) {
                    $output = 'null';
                } else {
                    $output = $input;
                }
                $results[] = "{$key}: {$output}";
            }
            $result = implode($separator, $results);
        } else {
            $result = (string)$parts;
        }
        return $result;
    }
}
