<?php
/**
 * SAM-4727 : Additional Signup Confirmation Editor and Deleter
 * https://bidpath.atlassian.net/browse/SAM-4727
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/8/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Signup\AdditionalConfirmation\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\AdditionalSignupConfirmation\AdditionalSignupConfirmationWriteRepositoryAwareTrait;
use Sam\User\Signup\AdditionalConfirmation\Load\AdditionalSignupConfirmationLoaderAwareTrait;

/**
 * Class AdditionalSignupConfirmationDeleter
 * @package Sam\User\Signup\AdditionalConfirmation\Delete
 */
class AdditionalSignupConfirmationDeleter extends CustomizableClass
{
    use AdditionalSignupConfirmationLoaderAwareTrait;
    use AdditionalSignupConfirmationWriteRepositoryAwareTrait;

    protected ?int $additionalSignupConfirmationId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int|null
     */
    public function getAdditionalSignupConfirmationId(): ?int
    {
        return $this->additionalSignupConfirmationId;
    }

    /**
     * @param int $id
     * @return static
     */
    public function setAdditionalSignupConfirmationId(int $id): static
    {
        $this->additionalSignupConfirmationId = $id;
        return $this;
    }

    // Delete confirmation
    public function delete(int $editorUserId): void
    {
        $additionalConfirmation = $this->getAdditionalSignupConfirmationLoader()
            ->loadById($this->additionalSignupConfirmationId, true);
        if (!$additionalConfirmation) {
            log_error("Available AdditionalConfirmation not found" . composeSuffix(['id' => $this->additionalSignupConfirmationId]));
            return;
        }
        $this->getAdditionalSignupConfirmationWriteRepository()->deleteWithModifier($additionalConfirmation, $editorUserId);
    }
}
