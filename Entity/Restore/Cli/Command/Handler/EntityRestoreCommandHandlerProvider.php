<?php
/**
 * SAM-6856: Soft-deleted Auction restore
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Entity\Restore\Cli\Command\Handler;

use Generator;
use Sam\Core\Service\CustomizableClass;

/**
 * Class EntityRestoreCommandHandlerProvider
 * @package Sam\Entity\Restore\Cli\Command\Handler
 */
class EntityRestoreCommandHandlerProvider extends CustomizableClass
{
    protected array $handlers = [
        AuctionRestoreCommandHandler::class
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        return $this;
    }

    /**
     * Detect applicable handler for entity
     *
     * @param string $entityName
     * @return EntityRestoreCommandHandlerInterface|null
     */
    public function getHandler(string $entityName): ?EntityRestoreCommandHandlerInterface
    {
        $entityName = $this->normalizeEntityName($entityName);
        foreach ($this->getHandlers() as $handler) {
            if ($this->isHandlerForEntity($handler, $entityName)) {
                return $handler;
            }
        }
        return null;
    }

    /**
     * Returns all handlers registered with the system
     *
     * @return EntityRestoreCommandHandlerInterface[]|Generator
     */
    public function getHandlers(): Generator
    {
        foreach ($this->handlers as $handlerClass) {
            /** @var EntityRestoreCommandHandlerInterface $handler */
            $handler = call_user_func("{$handlerClass}::new");
            yield $handler->construct();
        }
    }

    /**
     * @param EntityRestoreCommandHandlerInterface $handler
     * @param string $entityName
     * @return bool
     */
    protected function isHandlerForEntity(EntityRestoreCommandHandlerInterface $handler, string $entityName): bool
    {
        return $handler->getEntityName() === $entityName;
    }

    /**
     * Normalize to pascal-case key
     * @param string $entityName
     * @return string
     */
    protected function normalizeEntityName(string $entityName): string
    {
        if (str_contains($entityName, '_')) {
            // underscore key to pascal-case key
            $entityName = str_replace('_', '', ucwords(strtolower($entityName), '_'));
        } else {
            // camel-case to pascal-case (JIC)
            $entityName = ucfirst($entityName);
        }
        return $entityName;
    }
}
