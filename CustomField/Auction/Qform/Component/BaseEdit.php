<?php
/**
 * Base class for auction custom field editing component
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

namespace Sam\CustomField\Auction\Qform\Component;

use AuctionCustData;
use AuctionCustField;
use QCallerException;
use QControl;
use QForm;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\Save\AuctionCustomDataUpdaterAwareTrait;
use Sam\CustomField\Auction\Translate\AuctionCustomFieldTranslationManagerAwareTrait;
use Sam\CustomField\Auction\Validate\Web\AuctionCustomFieldWebDataValidatorAwareTrait;
use Sam\CustomField\Base\Qform\Component\BaseCustomFieldComponentBuilderAwareTrait;

/**
 * Class BaseEdit
 * @package Sam\CustomField\Auction\Qform\Component
 * Define methods inherited from BaseComponent, see __call():
 * // * @method $this enableEditMode(bool $enable) - add when will be used
 * // * @method $this enableMobileUi(bool $enable) - add when will be used
 * @method $this enablePublic(bool $enable)
 * @method $this enableTranslating(bool $enable)
 * @method $this setControlId(string $controlId)
 * @method $this setCustomData(AuctionCustData $customData)
 * @method $this setCustomField(AuctionCustField $customField)
 * @method $this setParentObject(QControl|QForm $parent)
 * @method $this setRelatedEntityId(int $userId);
 */
abstract class BaseEdit extends CustomizableClass
{
    use AuctionCustomDataUpdaterAwareTrait;
    use AuctionCustomFieldWebDataValidatorAwareTrait;
    use AuctionCustomFieldTranslationManagerAwareTrait;
    use BaseCustomFieldComponentBuilderAwareTrait;

    /**
     * Set in child class to define custom field type
     */
    protected int $type;
    /**
     * Store base component (which is not related to any entity: auction, user, lot item)
     */
    protected \Sam\CustomField\Base\Qform\Component\BaseEdit $baseComponent;

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $baseComponent = $this->getBaseCustomFieldComponentBuilder()->createComponent($this->type);
        $baseComponent->setDataValidator($this->getAuctionCustomFieldWebDataValidator());
        $baseComponent->setDataUpdater($this->getAuctionCustomDataUpdater());
        $baseComponent->setTranslator($this->getAuctionCustomFieldTranslationManager());
        $this->setBaseComponent($baseComponent);
        parent::initInstance();
        return $this;
    }

    /**
     * We try to call method of current class, then same method of $this->mixBaseComponent
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
     * @return \Sam\CustomField\Base\Qform\Component\BaseEdit
     */
    public function getBaseComponent(): \Sam\CustomField\Base\Qform\Component\BaseEdit
    {
        return $this->baseComponent;
    }

    /**
     * @param \Sam\CustomField\Base\Qform\Component\BaseEdit $baseComponent
     * @return static
     */
    public function setBaseComponent(\Sam\CustomField\Base\Qform\Component\BaseEdit $baseComponent): BaseEdit
    {
        $this->baseComponent = $baseComponent;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getType(): ?int
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
}
