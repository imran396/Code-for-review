<?php
/**
 * Base class for user custom field editing component
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: EditControls.php 13797 2013-07-09 15:26:18Z SWB\igors $
 * @since           Jul 20, 2013
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Qform\Component;

use QCallerException;
use QControl;
use QForm;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Qform\Component\BaseCustomFieldComponentBuilderAwareTrait;
use Sam\CustomField\User\Qform\ViewControls;
use Sam\CustomField\User\Save\UserCustomDataUpdaterAwareTrait;
use Sam\CustomField\User\Translate\UserCustomFieldTranslationManagerAwareTrait;
use Sam\CustomField\User\Validate\Web\UserCustomFieldWebDataValidatorAwareTrait;
use UserCustData;
use UserCustField;

/**
 * Class BaseEdit
 * @package Sam\CustomField\User\Qform\Component
 * Define methods inherited from BaseComponent, see __call():
 * @method $this enableEditMode(bool $enable)
 * @method $this enableMobileUi(bool $enable)
 * @method $this enablePublic(bool $enable)
 * @method $this enableTranslating(bool $enable)
 * @method $this setControlId(string $controlId)
 * @method $this setCustomData(UserCustData $customData)
 * @method $this setCustomField(UserCustField $customField)
 * @method $this setEditorUserId(int $userId);
 * @method $this setParentObject(QControl | QForm $parent)
 * @method $this setRelatedEntityId(int $userId);
 */
abstract class BaseEdit extends CustomizableClass
{
    use BaseCustomFieldComponentBuilderAwareTrait;
    use UserCustomDataUpdaterAwareTrait;
    use UserCustomFieldTranslationManagerAwareTrait;
    use UserCustomFieldWebDataValidatorAwareTrait;

    /**
     * Set in child class to define custom field type
     */
    protected int $type;
    /**
     * To store base component (which is not related to any entity: auction, user, lot item)
     */
    protected ?\Sam\CustomField\Base\Qform\Component\BaseEdit $baseComponent = null;

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $baseComponent = $this->getBaseCustomFieldComponentBuilder()->createComponent($this->type);
        $baseComponent->setDataValidator($this->getUserCustomFieldWebDataValidator());
        $baseComponent->setDataUpdater($this->getUserCustomDataUpdater());
        $baseComponent->setTranslator($this->getUserCustomFieldTranslationManager());
        $this->setBaseComponent($baseComponent);
        parent::initInstance();
        return $this;
    }

    /**
     * We try to call method of current class, then same method of $this->baseComponent
     *
     * @param string $methodName
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $methodName, array $arguments)
    {
        if (method_exists($this, $methodName)) {
            return call_user_func_array([$this, $methodName], $arguments);
        }

        if (method_exists($this->getBaseComponent(), $methodName)) {
            $result = call_user_func_array([$this->getBaseComponent(), $methodName], $arguments);
            if ($result instanceof self) {
                return $this;
            }

            return $result;
        }

        throw new QCallerException('Method with name "' . $methodName . '" not found in component class');
    }

    /**
     * @return \Sam\CustomField\Base\Qform\Component\BaseEdit|null
     */
    public function getBaseComponent(): ?\Sam\CustomField\Base\Qform\Component\BaseEdit
    {
        return $this->baseComponent;
    }

    /**
     * @param \Sam\CustomField\Base\Qform\Component\BaseEdit $baseComponent
     * @return static
     */
    public function setBaseComponent(\Sam\CustomField\Base\Qform\Component\BaseEdit $baseComponent): static
    {
        $this->baseComponent = $baseComponent;
        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return static
     */
    public function setType(int $type): static
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        if ($this->getBaseComponent()->isEditMode()) {
            $output = $this->getBaseComponent()->render();
        } else {
            $isTranslating = $this->getBaseComponent()->isTranslating();
            $customField = $this->getBaseComponent()->getCustomField();
            $customData = $this->getBaseComponent()->getCustomData();
            $output = ViewControls::new()
                ->enableTranslating($isTranslating)
                ->renderByCustData($customField, $customData);
        }
        return $output;
    }
}
