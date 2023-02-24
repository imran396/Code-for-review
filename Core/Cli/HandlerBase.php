<?php
/**
 * Base class for command handlers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/7/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Cli;

use Sam\Core\Service\CustomizableClass;

/**
 * Class HandlerBase
 * @package Sam\Rtb\Pool\Cli\Command\Update
 */
abstract class HandlerBase extends CustomizableClass
{
    use OutputAwareTrait;

    /**
     * @return void
     */
    abstract public function handle(): void;

    /**
     * @param string|iterable $text
     */
    public function output(string|iterable $text): void
    {
        $this->getOutput()->writeln($text);
    }
}
