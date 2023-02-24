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

namespace Sam\User\Signup\AdditionalConfirmation\Save;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\AdditionalSignupConfirmation\AdditionalSignupConfirmationWriteRepositoryAwareTrait;
use Sam\User\Signup\AdditionalConfirmation\Load\AdditionalSignupConfirmationLoaderAwareTrait;

/**
 * Class AdditionalSignupConfirmationEditor
 * @package Sam\User\Signup\AdditionalConfirmation\Save
 */
class AdditionalSignupConfirmationEditor extends CustomizableClass
{
    use AdditionalSignupConfirmationLoaderAwareTrait;
    use AdditionalSignupConfirmationWriteRepositoryAwareTrait;
    use EntityFactoryCreateTrait;

    protected ?int $additionalSignupConfirmationId = null;
    protected string $confirmationText = '';

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
     * @param int|null $id null - on create new additional confirmation.
     * @return static
     */
    public function setAdditionalSignupConfirmationId(?int $id): static
    {
        $this->additionalSignupConfirmationId = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmationText(): string
    {
        return $this->confirmationText;
    }

    /**
     * @param string $confirmationText
     * @return static
     */
    public function setConfirmationText(string $confirmationText): static
    {
        $this->confirmationText = trim($confirmationText);
        return $this;
    }

    /**
     * Input validation
     *
     * @return bool
     */
    public function validate(): bool
    {
        $isValid = $this->hasConfirmationTextError();
        return $isValid;
    }

    /**
     * Check empty confirmation text
     * @return bool
     */
    public function hasConfirmationTextError(): bool
    {
        return $this->confirmationText !== '';
    }

    /**
     * Save or update confirmation text
     * @param int $editorUserId
     */
    public function update(int $editorUserId): void
    {
        $additionalSignupConfirmationId = $this->getAdditionalSignupConfirmationId();
        $additionalConfirmation = $additionalSignupConfirmationId
            ? $this->getAdditionalSignupConfirmationLoader()->loadById($additionalSignupConfirmationId, true)
            : null;
        if (!$additionalConfirmation) {
            $additionalConfirmation = $this->createEntityFactory()->additionalSignupConfirmation();
        }
        $additionalConfirmation->ConfirmationText = $this->getConfirmationText();
        $this->getAdditionalSignupConfirmationWriteRepository()->saveWithModifier($additionalConfirmation, $editorUserId);
    }
}
