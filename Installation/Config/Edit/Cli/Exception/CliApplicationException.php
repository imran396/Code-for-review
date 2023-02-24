<?php
/**
 * SAM-5708: Local configuration management by CLI script
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Cli\Exception;

use Exception;
use Throwable;

/**
 * Class CliApplicationException
 * @package Sam\Installation\Config
 */
class CliApplicationException extends Exception
{
    /**
     * @param array $messages
     * @return self
     */
    public static function createFromMessages(array $messages): self
    {
        $message = implode("\n", $messages);
        return new self($message);
    }

    /**
     * CliApplicationException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        $message = "An error has occurred!\n" . $message;
        $message = strip_tags($message);
        parent::__construct($message, $code, $previous);
    }
}
