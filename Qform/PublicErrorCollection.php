<?php
/**
 * Base helper for rendering (error) messages at public pages
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           13 Feb, 2013
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform;

use Sam\Core\Service\CustomizableClass;

/**
 * Class PublicErrorCollection
 * @package Sam\Qform
 */
class PublicErrorCollection extends CustomizableClass
{
    /**
     * @var string[]
     */
    protected array $errors = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Add error
     * @param string $idSelector
     * @param string $message
     * @param string $label optional
     */
    public function addError(string $idSelector, string $message, string $label = ''): void
    {
        $this->errors[] = [$idSelector, $message, $label];
    }

    /**
     * Add array of errors
     * @param array $errors = array(<control id>, <message>, <field name>)
     */
    public function addErrors(array $errors): void
    {
        $this->errors = array_merge($this->errors, $errors);
    }

    /**
     * Return array of error blocks ready for public error messenger
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Remove registered errors
     */
    public function clearErrors(): void
    {
        $this->errors = [];
    }

    /**
     * Check if errors exist
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
