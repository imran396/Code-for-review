<?php
/**
 * SAM-5826: Decouple SyncNamespace Editor to classes and add unit tests
 */

namespace Sam\SyncNamespace\Edit\Internal\Exception;

use RuntimeException;

/**
 * Class CouldNotFindSyncNamespace
 * @package
 **/
final class CouldNotFindSyncNamespace extends RuntimeException
{
    /**
     * @param int|null $syncNamespaceId
     * @return self
     */
    public static function withId(?int $syncNamespaceId): self
    {
        $message = "Could not find SyncNamespace by id \"{$syncNamespaceId}\"";
        log_errorBackTrace($message);
        return new self($message);
    }
}
