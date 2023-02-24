<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           23.06.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Php\Prettier;

use Sam\Core\Php\Prettier\Transform\ShortArraySyntax;

/**
 * Class PhpPrettier
 * @package Sam\Core\Php\Prettier
 */
class PhpPrettier
{
    /**
     * enable short array syntax. Using [] instead old array() syntax.
     */
    protected bool $isShortArraySyntax = true;

    /**
     * PhpPrettier constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        foreach ($options ?: [] as $optionName => $value) {
            if ($optionName === 'shortArraySyntax') {
                $this->enableShortArraySyntax((bool)$value);
            }
        }
    }

    /**
     * get short array syntax property.
     * @return bool
     */
    public function isShortArraySyntax(): bool
    {
        return $this->isShortArraySyntax;
    }

    /**
     * Enable\Disable short array syntax format.
     * @param bool $enable
     * @return static
     */
    public function enableShortArraySyntax(bool $enable): static
    {
        $this->isShortArraySyntax = $enable;
        return $this;
    }

    /**
     * Format code using separate formatter classes.
     * @param string $input
     * @return string
     */
    public function transform(string $input): string
    {
        $output = $input;
        if ($this->isShortArraySyntax()) {
            $output = (new ShortArraySyntax())->transform($output);
        }
        return $output;
    }
}
