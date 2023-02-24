<?php
/**
 * SAM-5826: Decouple SyncNamespace Editor to classes and add unit tests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Mar 07, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\SyncNamespace\Edit\Delete;

use LogicException;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\SyncNamespace\SyncNamespaceWriteRepositoryAwareTrait;
use Sam\SyncNamespace\Edit\Internal\Load\DataProviderAwareTrait;
use SyncNamespace;

/**
 * Class Deleter
 * @package Sam\SyncNamespace\Edit\Delete
 */
class Deleter extends CustomizableClass
{
    use DataProviderAwareTrait;
    use EditorUserAwareTrait;
    use SyncNamespaceWriteRepositoryAwareTrait;

    protected int $syncNamespaceId;
    protected ?SyncNamespace $deletedSyncNamespace = null;
    protected ?string $successMessage = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * @param int $editorUserId
     * @param int $syncNamespaceId
     * @return $this
     */
    public function construct(int $editorUserId, int $syncNamespaceId): static
    {
        $this->syncNamespaceId = $syncNamespaceId;
        $this->setEditorUserId($editorUserId);
        return $this;
    }

    /**
     * Delete SyncNamespace record loaded by id
     * @return void
     */
    public function delete(): void
    {
        $this->deletedSyncNamespace = $this->getDataProvider()->loadSyncNamespaceById($this->syncNamespaceId);
        $this->deletedSyncNamespace->Active = false;
        $this->getSyncNamespaceWriteRepository()->saveWithModifier($this->deletedSyncNamespace, $this->getEditorUserId());
        $this->setSuccessMessage("SyncNamespace [" . $this->deletedSyncNamespace->Id . "] has been deleted");
    }

    /**
     * Return deleted syncNamespace
     * @return SyncNamespace
     */
    public function getResultSyncNamespace(): SyncNamespace
    {
        if (!$this->deletedSyncNamespace) {
            throw new LogicException('You should call delete() first to load syncNamespace');
        }
        return $this->deletedSyncNamespace;
    }

    /**
     * @return string|null
     */
    public function getSuccessMessage(): ?string
    {
        return $this->successMessage;
    }

    /**
     * @param string $successMessage
     * @return $this
     */
    public function setSuccessMessage(string $successMessage): static
    {
        $this->successMessage = $successMessage;
        return $this;
    }
}
