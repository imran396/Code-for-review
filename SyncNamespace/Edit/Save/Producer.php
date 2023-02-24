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

use LogicException;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\WriteRepository\Entity\SyncNamespace\SyncNamespaceWriteRepositoryAwareTrait;
use Sam\SyncNamespace\Edit\Internal\Load\DataProviderAwareTrait;
use SyncNamespace;

/**
 * Class Producer
 * @package Sam\SyncNamespace\Edit
 */
class Producer extends CustomizableClass
{
    use CurrentDateTrait;
    use DataProviderAwareTrait;
    use EditorUserAwareTrait;
    use EntityFactoryCreateTrait;
    use ResultStatusCollectorAwareTrait;
    use SyncNamespaceWriteRepositoryAwareTrait;
    use SystemAccountAwareTrait;

    public const OK_CREATED = 1;
    public const OK_UPDATED = 2;

    protected Dto $dto;
    protected ?SyncNamespace $resultSyncNamespace = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Dto $dto
     * @param int $systemAccountId
     * @param int $editorUserId
     * @return static
     */
    public function construct(Dto $dto, int $systemAccountId, int $editorUserId): static
    {
        $this->dto = $dto;
        $this->setSystemAccountId($systemAccountId);
        $this->setEditorUserId($editorUserId);
        $successMessages = [
            self::OK_CREATED => 'SyncNamespace created',
            self::OK_UPDATED => 'SyncNamespace updated',
        ];
        $this->getResultStatusCollector()->initAllSuccesses($successMessages);
        return $this;
    }

    /**
     * @return void
     */
    public function update(): void
    {
        $syncNamespace = $this->build();
        $this->getSyncNamespaceWriteRepository()->saveWithModifier($syncNamespace, $this->getEditorUserId());
        $this->resultSyncNamespace = $syncNamespace;
        $successCode = $this->dto->isNew() ? self::OK_CREATED : self::OK_UPDATED;
        $this->getResultStatusCollector()->addSuccess($successCode);
    }

    /**
     * @return SyncNamespace
     */
    protected function build(): SyncNamespace
    {
        $name = (string)$this->dto->getName();
        $isFound = $this->getDataProvider()->existByNameAndAccountId($name, $this->getSystemAccountId());
        $syncNamespaceId = (int)$this->dto->getSyncNamespaceId();
        if (
            $isFound
            || $syncNamespaceId
        ) {
            $syncNamespace = $this->getDataProvider()->loadSyncNamespaceById($syncNamespaceId);
        } else {
            $syncNamespace = $this->createEntityFactory()->syncNamespace();
        }
        $syncNamespace->AccountId = $this->getSystemAccountId();
        $syncNamespace->Name = $name;
        $syncNamespace->Active = true;
        return $syncNamespace;
    }

    /**
     * @return SyncNamespace
     */
    public function getResultSyncNamespace(): SyncNamespace
    {
        if (!$this->resultSyncNamespace) {
            $message = "Result SyncNamespace not found. Run self::update() first";
            log_error($message);
            throw new LogicException($message);
        }
        return $this->resultSyncNamespace;
    }

    /**
     * @return string
     */
    public function successMessage(): ?string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

}
