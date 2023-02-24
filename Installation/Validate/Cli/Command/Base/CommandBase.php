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

namespace Sam\Installation\Validate\Cli\Command\Base;

use Sam\Installation\Config\Edit\Meta\ConfigNameAwareTrait;
use Symfony\Component\Console\Command\Command;

/**
 * Class AbstractCommand
 * @package Sam\Installation\Cli
 */
abstract class CommandBase extends Command
{
    use ConfigNameAwareTrait;

    public const NAME = null;

    /**
     * CommandBase constructor.
     * @param string|null $name
     */
    public function __construct(?string $name = null)
    {
        if ($name === null) {
            $name = static::NAME;
        }
        parent::__construct($name);
    }
}
