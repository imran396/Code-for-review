<?php
/**
 * SAM-8680: Fix text transformations with unicode characters
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Transform\Text;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Log\Support\SupportLogger;
use Sam\Log\Support\SupportLoggerAwareTrait;

/**
 * Class TextSanitizer
 * @package Sam\Core\Transform\Text\Sanitize
 */
class NewLineRemover extends CustomizableClass
{
    use SupportLoggerAwareTrait;

    public const OP_IS_BACKTRACE = 'isBacktrace'; // bool

    protected array $pregLastError = [];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Replace New Line characters with replacement string.
     * @param string $input
     * @param string $replacement
     * @param array $optionals = [
     *      self::OP_IS_BACKTRACE => bool, (def: true)
     * ]
     * @return string
     */
    public function replace(string $input, string $replacement, array $optionals = []): string
    {
        $this->dropPregLastError();
        // \R matches any Unicode newline sequence https://www.pcre.org/original/doc/html/pcrepattern.html#newlineseq
        // "u" modifier means pattern and subject strings should be treated as UTF-8.
        $pattern = '/\R+/u';
        $output = preg_replace($pattern, $replacement, $input);
        if ($output === null) {
            $this->registerPregLastError();
            $logData = [
                'pattern' => $pattern,
                'input' => $input,
                'preg error' => $this->pregLastErrorCode(),
                'error message' => $this->pregLastErrorMessage(),
            ];
            $isBacktrace = $optionals[self::OP_IS_BACKTRACE] ?? true;
            $this->getSupportLogger()->error(
                "Error on preg replace" . composeSuffix($logData),
                null,
                0,
                [SupportLogger::OP_IS_BACKTRACE => $isBacktrace]
            );
            return Constants\TextTransform::CHARACTER_ENCODING_ERROR_MARKER;
        }
        return $output;
    }

    /**
     * Replace New Line characters with single space character.
     * @param string $input
     * @param array $optionals
     * @return string
     */
    public function replaceWithSpace(string $input, array $optionals = []): string
    {
        return $this->replace($input, ' ', $optionals);
    }

    /**
     * Completely remove New Line characters.
     * @param string $input
     * @param array $optionals
     * @return string
     */
    public function remove(string $input, array $optionals = []): string
    {
        return $this->replace($input, '', $optionals);
    }

    /**
     * Return error code of the last preg_replace() operation.
     * @return int|null
     */
    public function pregLastErrorCode(): ?int
    {
        return Cast::toInt($this->pregLastError['code'] ?? null);
    }

    /**
     * Return error message of the last preg_replace() operation.
     * @return string
     */
    public function pregLastErrorMessage(): string
    {
        return $this->pregLastError['message'] ?? '';
    }

    protected function registerPregLastError(): void
    {
        $this->pregLastError = [
            'code' => preg_last_error(),
            'message' => preg_last_error_msg(),
        ];
    }

    protected function dropPregLastError(): void
    {
        $this->pregLastError = [];
    }
}
