<?php
/**
 * SAM-3578: Buyer select from quantity for timed buy now
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BuyNow\QuantityLotCloner\Internal;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * Class CloneNumberExtensionGenerator
 * @package Sam\Bidding\BuyNow\QuantityLotCloner\Internal
 */
class CloneNumberExtensionGenerator extends CustomizableClass
{
    use OptionalsTrait;

    public const OP_CLONE_EXTENSION_PREFIX = 'cloneExtensionPrefix';

    private const ALPHABET_LENGTH = 26;
    private const FIRST_LOWERCASE_LETTER_CODEPOINT = 97;
    private const EXTENSION_SUFFIX_LENGTH = 4;
    private const DEFAULT_DELIMITER = 'x';

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
     * Generate sequential alphabetic lot number extension for a cloned lot
     *
     * @param string $originalExtension
     * @param int $maxLength
     * @param int $index
     * @return string
     */
    public function generate(string $originalExtension, int $maxLength, int $index): string
    {
        $prefix = substr($originalExtension, 0, $maxLength - self::EXTENSION_SUFFIX_LENGTH);
        $delimiter = $this->getExtensionDelimiter();
        $suffix = $this->generateSuffix($index, self::EXTENSION_SUFFIX_LENGTH - 1);
        return $prefix . $delimiter . $suffix;
    }

    /**
     * @param int $index
     * @param int $length
     * @return string
     */
    protected function generateSuffix(int $index, int $length): string
    {
        $suffix = '';
        // Letter position from right to left
        for ($letterPosition = 0; $letterPosition < $length; $letterPosition++) {
            $letterIndex = $index / self::ALPHABET_LENGTH ** $letterPosition % self::ALPHABET_LENGTH;
            $suffix = $this->getLetterByIndex($letterIndex) . $suffix;
        }
        return $suffix;
    }

    /**
     * @param int $index
     * @return string
     */
    protected function getLetterByIndex(int $index): string
    {
        return chr(self::FIRST_LOWERCASE_LETTER_CODEPOINT + $index);
    }

    /**
     * @return string
     */
    protected function getExtensionDelimiter(): string
    {
        $delimiter = $this->fetchOptional(self::OP_CLONE_EXTENSION_PREFIX);
        return $delimiter[0] ?? self::DEFAULT_DELIMITER;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_CLONE_EXTENSION_PREFIX] = $optionals[self::OP_CLONE_EXTENSION_PREFIX]
            ?? static function (): string {
                return ConfigRepository::getInstance()->get('core->bidding->buyNow->timed->selectQuantity->cloneExtensionPrefix');
            };
        $this->setOptionals($optionals);
    }
}
