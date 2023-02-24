<?php
/**
 *
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 28, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Rtb\Server\Daemon;

use EventUtil;
use Sam\Core\Service\CustomizableClass;
use Socket;

class SocketHelper extends CustomizableClass
{
    /**
     * Get instance of Application
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isSocketOrFd($socket): bool
    {
        if ($socket instanceof Socket) {
            return true;
        }

        if (is_resource($socket)) {
            return true;
        }

        if (is_int($socket)) {
            return true;
        }

        return false;
    }

    public function getSocketFd($socket): ?int
    {
        if ($socket === null) {
            return null;
        }

        if ($socket instanceof Socket) {
            return EventUtil::getSocketFd($socket);
        }

        return (int)$socket;
    }
}
