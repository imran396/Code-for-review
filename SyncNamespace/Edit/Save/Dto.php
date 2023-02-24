<?php
/**
 * SAM-5826: Decouple SyncNamespace Editor to classes and add unit tests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Feb 19, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\SyncNamespace\Edit\Save;

use Sam\Core\Service\CustomizableClass;

/**
 * Class Dto
 * @package Sam\SyncNamespace\Edit
 */
class Dto extends CustomizableClass
{
    protected ?string $name = null;
    protected ?string $syncNamespaceId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return static
     */
    public function setName(?string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSyncNamespaceId(): ?string
    {
        return $this->syncNamespaceId;
    }

    /**
     * @param string|null $syncNamespaceId
     * @return static
     */
    public function setSyncNamespaceId(?string $syncNamespaceId): static
    {
        $this->syncNamespaceId = $syncNamespaceId;
        return $this;
    }

    /**
     * Detects if it is supposed to be new or existing record.
     * Logic method in DTO.
     * @return bool
     */
    public function isNew(): bool
    {
        return !(int)$this->syncNamespaceId;
    }
}
