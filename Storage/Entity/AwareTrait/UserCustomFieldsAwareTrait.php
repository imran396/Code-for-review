<?php
/** @noinspection PhpUnused */

/**
 * SAM-4819: Entity aware traits
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/17/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\AwareTrait;

use Sam\CustomField\User\Load\UserCustomFieldLoader;
use UserCustField;

/**
 * Trait UserCustomFieldsAwareTrait
 * @package Sam\Storage\Entity\AwareTrait
 */
trait UserCustomFieldsAwareTrait
{
    private ?array $userCustomFields = null;

    /**
     * @return int[]
     */
    public function getUserCustomFieldIds(): array
    {
        $userCustomFieldIds = [];
        foreach ($this->getUserCustomFields() as $userCustomField) {
            $userCustomFieldIds[] = $userCustomField->Id;
        }
        return $userCustomFieldIds;
    }

    /**
     * Return array of user custom field. By default it loads active only
     * @return UserCustField[]
     */
    public function getUserCustomFields(): array
    {
        if ($this->userCustomFields === null) {
            $this->userCustomFields = UserCustomFieldLoader::new()->loadAll();
        }
        return $this->userCustomFields;
    }

    /**
     * @param UserCustField[] $userCustomFields
     * @return static
     */
    public function setUserCustomFields(array $userCustomFields): static
    {
        $this->userCustomFields = $userCustomFields;
        return $this;
    }

    /**
     * @param int|null $id
     * @param bool $isReadOnlyDb
     * @return UserCustField|null
     */
    public function getUserCustomFieldById(?int $id, bool $isReadOnlyDb = false): ?UserCustField
    {
        $userCustomField = null;
        if ($this->userCustomFields === null) {
            $userCustomField = UserCustomFieldLoader::new()->load($id, $isReadOnlyDb);
        } else {
            foreach ($this->getUserCustomFields() as $checkedCustomField) {
                if ($checkedCustomField->Id === $id) {
                    $userCustomField = $checkedCustomField;
                    break;
                }
            }
            if (!$userCustomField) {
                $userCustomField = UserCustomFieldLoader::new()->load($id, $isReadOnlyDb);
            }
        }
        if ($userCustomField) {
            return $userCustomField;
        }

        log_error("User custom field not found" . composeSuffix(['ucf' => $id]));
        return null;
    }
}
