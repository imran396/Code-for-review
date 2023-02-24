<?php
/**
 * Created by PhpStorm.
 * User: namax
 * Date: 3/22/18
 * Time: 11:38 AM
 */

namespace Sam\Auction\AuctionList\Autocomplete\Exception;

/**
 * Class RequiredOption
 * @package Sam\Auction\AuctionList\Autocomplete\Exception
 */
class RequiredOption extends \Exception
{
    // Redefine the exception so message isn't optional
    /**
     * RequiredOption constructor.
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        $message = "The '{$message}' option is required\n";
        parent::__construct($message, $code, $previous);
    }
}