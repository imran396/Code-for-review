<?php
/**
 * SAM-5826: Decouple SyncNamespace Editor to classes and add unit tests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 17, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\SyncNamespace\Edit\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\SyncNamespace\Edit\Internal\Validate\SyncNamespaceEditorConstants;
use Sam\SyncNamespace\Edit\Internal\Validate\ValidationHelperCreateTrait;

/**
 * Class Validator
 * @package Sam\SyncNamespace\Edit\Delete
 */
class Validator extends CustomizableClass
{
    use EditorUserAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use ValidationHelperCreateTrait;

    protected int $syncNamespaceId;

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
     * @param int $syncNamespaceId
     * @param int $editorUserId
     * @return $this
     */
    public function construct(int $syncNamespaceId, int $editorUserId): static
    {
        $this->syncNamespaceId = $syncNamespaceId;
        $this->setEditorUserId($editorUserId);
        $errorMessages = [
            SyncNamespaceEditorConstants::ERR_ID_NOT_EXISTED => 'SyncNamespace not found',
            SyncNamespaceEditorConstants::ERR_SYNC_NAMESPACE_DELETED => 'SyncNamespace already deleted',
            SyncNamespaceEditorConstants::ERR_USER_ABSENT => 'User absent',
            SyncNamespaceEditorConstants::ERR_NO_ACCESS_BY_PRIVILEGE => 'Not enough privileges to access',
            SyncNamespaceEditorConstants::ERR_NO_ACCESS_BY_ACCOUNT => 'Access rejected to sync namespace of another account',
        ];
        $this->getResultStatusCollector()->construct($errorMessages);
        return $this;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $validationHelper = $this->createValidationHelper()->construct($this->getResultStatusCollector());
        $isValid = $validationHelper->validateEntity($this->syncNamespaceId)
            && $validationHelper->validateAccess($this->syncNamespaceId, $this->getEditorUserId());
        $this->setResultStatusCollector($validationHelper->getResultStatusCollector()); // JIC
        return $isValid;
    }

    /**
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage("\n");
    }
}
