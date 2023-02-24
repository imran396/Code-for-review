<?php
/**
 * SAM-5843: System Parameters management by CLI script
 * SAM-4720: Refactor logic of System Parameters management pages to editor modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Dev\Cli\Command;

use Symfony\Component\Console\Command\Command;

/**
 * Class CommandBase
 * @package Sam\Settings\Dev\Cli
 */
abstract class CommandBase extends Command
{
    public const NAME = null;

    public function __construct(?string $name = null)
    {
        if ($name === null) {
            $name = static::NAME;
        }
        parent::__construct($name);
    }
}
