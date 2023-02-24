<?php
/**
 * SAM-4749: View language editor
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-02-08
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lang\ViewLanguage\Save;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\ViewLanguage\Validate\ViewLanguageExistenceCheckerAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Storage\WriteRepository\Entity\ViewLanguage\ViewLanguageWriteRepositoryAwareTrait;

/**
 * Class ViewLanguageEditor
 * @package Sam\Lang\ViewLanguage\Save
 */
class ViewLanguageEditor extends CustomizableClass
{
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use FilterAccountAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use ViewLanguageExistenceCheckerAwareTrait;
    use ViewLanguageWriteRepositoryAwareTrait;

    public const ERR_NAME_EXIST = 1;
    public const ERR_NAME_REQUIRED = 2;

    /** @var int[] */
    protected array $nameErrors = [self::ERR_NAME_EXIST, self::ERR_NAME_REQUIRED];
    protected ?string $name = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $errorMessages = [
            self::ERR_NAME_EXIST => 'Name exists',
            self::ERR_NAME_REQUIRED => 'Name required',
        ];
        $this->getResultStatusCollector()->construct($errorMessages);
        return $this;
    }

    /**
     * Update changes and save
     * @param int $editorUserId
     */
    public function update(int $editorUserId): void
    {
        $viewLanguage = $this->createEntityFactory()->viewLanguage();
        $viewLanguage->AccountId = $this->getFilterAccountId();
        $viewLanguage->Active = true;
        $viewLanguage->Name = $this->getName();
        $this->getViewLanguageWriteRepository()->saveWithModifier($viewLanguage, $editorUserId);
    }

    /**
     * Validate
     * @return bool
     */
    public function validate(): bool
    {
        $this->getResultStatusCollector()->clear();
        $this->validateName();
        $success = !$this->getResultStatusCollector()->hasError();
        return $success;
    }

    /**
     * Get Name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set Name
     * @param string $name
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = trim($name);
        return $this;
    }

    /**
     * @return bool
     */
    public function hasNameError(): bool
    {
        $has = $this->getResultStatusCollector()->hasConcreteError($this->nameErrors);
        return $has;
    }

    /**
     * Get Name Error
     * @return string
     */
    public function nameErrorMessage(): string
    {
        $searchErrors = $this->nameErrors;
        $errorMessage = (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes($searchErrors);
        return $errorMessage;
    }

    /**
     * Validate Name
     */
    protected function validateName(): void
    {
        $name = $this->getName();
        if ($name === '') {
            $this->getResultStatusCollector()->addError(self::ERR_NAME_REQUIRED);
            return;
        }

        $isFound = $this->getViewLanguageExistenceChecker()
            ->existByNameAndAccountId($name, $this->getFilterAccountId());
        if ($isFound) {
            $this->getResultStatusCollector()->addError(self::ERR_NAME_EXIST);
            return;
        }
    }
}
