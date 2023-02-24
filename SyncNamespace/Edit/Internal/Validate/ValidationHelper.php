<?php
/**
 * SAM-5826: Decouple SyncNamespace Editor to classes and add unit tests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\SyncNamespace\Edit\Internal\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollector;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\SyncNamespace\Edit\Internal\Exception\CouldNotFindSyncNamespace;
use Sam\SyncNamespace\Edit\Internal\Load\DataProviderAwareTrait;

/**
 * Class ValidationHelper
 * @package Sam\SyncNamespace\Edit\Internal\Validator
 */
class ValidationHelper extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DataProviderAwareTrait;
    use EditorUserAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * Service configuration and additional data
     */
    protected array $options = [];

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
     * @param ResultStatusCollector $collector
     * @param array $options = [
     *     'isMultipleTenant' => bool, // by config defined
     * ]
     * @return $this
     */
    public function construct(ResultStatusCollector $collector, array $options = []): ValidationHelper
    {
        $this->setResultStatusCollector($collector);
        $this->options = $options;
        return $this;
    }

    /**
     * Validate namespaceId
     * @param int $syncNamespaceId
     * @return bool
     */
    public function validateEntity(int $syncNamespaceId): bool
    {
        if ($syncNamespaceId) {
            try {
                $syncNamespace = $this->getDataProvider()->loadSyncNamespaceById($syncNamespaceId);
            } catch (CouldNotFindSyncNamespace) {
                $this->getResultStatusCollector()->addError(SyncNamespaceEditorConstants::ERR_ID_NOT_EXISTED);
                return false;
            }
            if (!$syncNamespace->Active) {
                $this->getResultStatusCollector()->addError(SyncNamespaceEditorConstants::ERR_SYNC_NAMESPACE_DELETED);
                return false;
            }
        }
        return true;
    }

    /**
     * Check editor user access privilege to feed entity, including cross-account entity checking
     * @param int $syncNamespaceId
     * @param int|null $editorUserId
     * @return bool
     */
    public function validateAccess(int $syncNamespaceId, ?int $editorUserId): bool
    {
        $collector = $this->getResultStatusCollector();
        $this->setEditorUserId($editorUserId);
        if (!$this->getEditorUser()) {
            $collector->addError(SyncNamespaceEditorConstants::ERR_USER_ABSENT);
            return false;
        }

        $checker = $this->getEditorUserAdminPrivilegeChecker();
        if (
            !$this->hasEditorUserAdminRole()
            || !$checker->hasPrivilegeForManageSettings()
        ) {
            $collector->addError(SyncNamespaceEditorConstants::ERR_NO_ACCESS_BY_PRIVILEGE);
            return false;
        }

        if (
            $syncNamespaceId
            && $this->isMultipleTenant()
        ) {
            $syncNamespace = $this->getDataProvider()->loadSyncNamespaceById($syncNamespaceId);
            if (
                $syncNamespace->AccountId !== $this->getEditorUser()->AccountId
                && !$checker->hasPrivilegeForSuperadmin()
            ) {
                $collector->addError(SyncNamespaceEditorConstants::ERR_NO_ACCESS_BY_ACCOUNT);
                return false;
            }
        }
        return true;
    }

    /**
     * @return int[]
     * @internal
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @return bool
     */
    protected function isMultipleTenant(): bool
    {
        return (bool)($this->options['isMultipleTenant'] ?? $this->cfg()->get('core->portal->enabled'));
    }
}
