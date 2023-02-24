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
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\SyncNamespace\Edit\Internal\Load\DataProviderAwareTrait;
use Sam\SyncNamespace\Edit\Internal\Validate\SyncNamespaceEditorConstants;
use Sam\SyncNamespace\Edit\Internal\Validate\ValidationHelperCreateTrait;

/**
 * Class Validator
 * @package Sam\SyncNamespace\Edit
 */
class Validator extends CustomizableClass
{
    use DataProviderAwareTrait;
    use EditorUserAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use SystemAccountAwareTrait;
    use ValidationHelperCreateTrait;

    protected Dto $dto;

    /**
     * @param Dto $dto
     * @param int $systemAccountId
     * @param int $editorUserId
     * @return $this
     */
    public function construct(
        Dto $dto,
        int $systemAccountId,
        int $editorUserId
    ): static {
        $this->dto = $dto;
        $this->setSystemAccountId($systemAccountId);
        $this->setEditorUserId($editorUserId);
        return $this;
    }

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate
     * @return bool
     */
    public function validate(): bool
    {
        $this->initResultStatusCollector();
        $syncNamespaceId = (int)$this->dto->getSyncNamespaceId();
        $validationHelper = $this->createValidationHelper()->construct($this->getResultStatusCollector());
        $success = $validationHelper->validateEntity($syncNamespaceId)
            && $validationHelper->validateAccess($syncNamespaceId, $this->getEditorUserId());
        if (!$success) {
            $this->setResultStatusCollector($validationHelper->getResultStatusCollector()); // JIC
            return false;
        }
        $success = $this->validateName($this->dto->getName(), $syncNamespaceId);
        return $success;
    }

    /**
     * Validate Name
     * @param string $name
     * @param int $syncNamespaceId
     * @return bool
     */
    public function validateName(string $name, int $syncNamespaceId): bool
    {
        $collector = $this->getResultStatusCollector();
        if (!$name) {
            $collector->addError(SyncNamespaceEditorConstants::ERR_NAME_REQUIRED);
            return false;
        }
        $systemAccountId = $this->getSystemAccountId();
        $isFound = $this->getDataProvider()->existByNameAndIdAndAccountId($name, $syncNamespaceId, $systemAccountId);
        if ($isFound) {
            $collector->addError(SyncNamespaceEditorConstants::ERR_NAME_EXISTED);
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function hasNameError(): bool
    {
        $has = $this->getResultStatusCollector()->hasConcreteError(SyncNamespaceEditorConstants::NAME_ERRORS);
        return $has;
    }

    /**
     * Get Name Error
     * @return string
     */
    public function nameErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(SyncNamespaceEditorConstants::NAME_ERRORS);
    }

    /**
     * @return bool
     */
    public function hasAccessError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(SyncNamespaceEditorConstants::ACCESS_ERRORS);
    }

    /**
     * @return string
     */
    public function accessErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()->findFirstErrorMessageAmongCodes(SyncNamespaceEditorConstants::ACCESS_ERRORS);
    }

    /**
     * @return bool
     */
    public function hasEntityError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(SyncNamespaceEditorConstants::ENTITY_ERRORS);
    }

    /**
     * @return string
     */
    public function entityErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes(SyncNamespaceEditorConstants::ENTITY_ERRORS);
    }

    /**
     * @return string
     */
    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    /**
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    /**
     * @return int[]
     * @internal
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    protected function initResultStatusCollector(): void
    {
        $errorMessages = [
            SyncNamespaceEditorConstants::ERR_NAME_REQUIRED => 'Name required',
            SyncNamespaceEditorConstants::ERR_NAME_EXISTED => 'Name existed',
            SyncNamespaceEditorConstants::ERR_ID_NOT_EXISTED => 'Available SyncNamespace record cannot be found by id' . composeSuffix(['sn' => $this->dto->getSyncNamespaceId()]),
            SyncNamespaceEditorConstants::ERR_SYNC_NAMESPACE_DELETED => 'SyncNamespace already deleted',
            SyncNamespaceEditorConstants::ERR_USER_ABSENT => 'User absent',
            SyncNamespaceEditorConstants::ERR_NO_ACCESS_BY_PRIVILEGE => 'Not enough privileges to access',
            SyncNamespaceEditorConstants::ERR_NO_ACCESS_BY_ACCOUNT => 'Access rejected to syncNamespace of another account',
        ];
        $this->getResultStatusCollector()->construct($errorMessages);
    }
}
