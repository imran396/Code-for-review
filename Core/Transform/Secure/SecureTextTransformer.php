<?php
/**
 * SAM-4445: Apply TextFormatter
 * SAM-10555: Improve sensitive text masking service for v3-7
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko, Igors Kotlevskis
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           05-23, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Transform\Secure;

use Sam\Core\Service\CustomizableClass;

/**
 * Class SecureTextTransformer
 * @package Sam\Core\Transform\Secure
 */
class SecureTextTransformer extends CustomizableClass
{
    public const OP_CC_NUMBER_STANDARD_LENGTH = 'ccNumberStandardLength'; // int
    public const OP_CC_NUMBER_VISIBLE_TAIL_LENGTH = 'ccNumberVisibleTailLength'; // int
    public const OP_CREDENTIAL_VISIBLE_TAIL_LENGTH = 'credentialsVisibleTailLength'; // int
    public const OP_MASK_CHARACTER = 'maskCharacter'; // string

    protected const CC_NUMBER_STANDARD_LENGTH_DEF = 16;
    protected const CC_NUMBER_VISIBLE_TAIL_LENGTH_DEF = 4;
    protected const CREDENTIAL_VISIBLE_TAIL_LENGTH_DEF = 3;
    protected const MASK_CHARACTER_DEF = 'x';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Mask by "x" characters input text from the start.
     *
     * @param string $input input string for masking it.
     * @param int $visibleTailLength number of characters in the tail that must be kept visible and non-masked.
     * @param array $optionals = [
     *      self::OP_MASK_CHARACTER => string,
     * ]
     * @return string
     * #[Pure]
     */
    public function mask(string $input, int $visibleTailLength = 0, array $optionals = []): string
    {
        if ($input === '') {
            return ''; // Don't mask empty input
        }

        $maskCharacter = $this->fetchOptionalMaskCharacter($optionals);
        $maskedLength = max(mb_strlen($input) - $visibleTailLength, 0);
        $output = str_repeat($maskCharacter, $maskedLength);
        if ($visibleTailLength > 0) {
            $output .= mb_substr($input, -1 * $visibleTailLength);
        }
        return $output;
    }

    /**
     * Mask CC Number.
     * When it is full CC Number, then we display the last 4 digits,
     * when it is already a part of CC Number (eway), then we prepend it with mask character to complete standard length of CC Number (16 characters).
     *
     * @param string $ccNumber input CC number for masking it.
     * @param array $optionals = [
     *      self::OP_CC_NUMBER_STANDARD_LENGTH => int,
     *      self::OP_CC_NUMBER_VISIBLE_TAIL_LENGTH => int,
     *      self::OP_MASK_CHARACTER => string,
     * ]
     * @return string
     * #[Pure]
     */
    public function maskCcNumber(string $ccNumber, array $optionals = []): string
    {
        if ($ccNumber === '') {
            return ''; // Don't mask empty CC number
        }

        $ccNumberVisibleTailLength = $this->fetchOptionalCcNumberVisibleTailLength($optionals);
        $len = strlen($ccNumber);
        if ($len > $ccNumberVisibleTailLength) {
            return $this->mask($ccNumber, $ccNumberVisibleTailLength);
        }

        $ccNumberStandardLength = $this->fetchOptionalCcNumberStandardLength($optionals);
        $maskCharacter = $this->fetchOptionalMaskCharacter($optionals);
        $output = str_repeat($maskCharacter, $ccNumberStandardLength - $len) . $ccNumber;
        return $output;
    }

    /**
     * Default masking function for sensitive credential data like usernames, passwords, API keys.
     *
     * @param string $input
     * @param array $optionals = [
     *      self::OP_CREDENTIAL_VISIBLE_TAIL_LENGTH => int,
     *      self::OP_MASK_CHARACTER => string,
     * ]
     * @return string
     * #[Pure]
     */
    public function maskCredential(string $input, array $optionals = []): string
    {
        $visibleTailLength = $this->fetchOptionalCredentialVisibleTailLength($optionals);
        return $this->mask($input, $visibleTailLength, $optionals);
    }

    protected function fetchOptionalCcNumberStandardLength(array $optionals): int
    {
        return (int)($optionals[self::OP_CC_NUMBER_STANDARD_LENGTH] ?? self::CC_NUMBER_STANDARD_LENGTH_DEF);
    }

    protected function fetchOptionalCcNumberVisibleTailLength(array $optionals): int
    {
        return (int)($optionals[self::OP_CC_NUMBER_VISIBLE_TAIL_LENGTH] ?? self::CC_NUMBER_VISIBLE_TAIL_LENGTH_DEF);
    }

    protected function fetchOptionalCredentialVisibleTailLength(array $optionals): int
    {
        return (int)($optionals[self::OP_CREDENTIAL_VISIBLE_TAIL_LENGTH] ?? self::CREDENTIAL_VISIBLE_TAIL_LENGTH_DEF);
    }

    protected function fetchOptionalMaskCharacter(array $optionals): string
    {
        return (string)($optionals[self::OP_MASK_CHARACTER] ?? self::MASK_CHARACTER_DEF);
    }
}
