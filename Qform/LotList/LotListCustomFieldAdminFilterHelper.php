<?php
/**
 * "Custom Fields" block rendering helper
 *
 * SAM-3412: Admin Inventory page merge "Custom Field Search" into "Search/Filters" section
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 7, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\LotList;

use LogicException;
use LotItemCustField;
use QEnterKeyEvent;
use QForm;
use QPanel;
use QServerAction;
use QServerControlAction;
use QTextBox;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\CustomField\Lot\Qform\LotCustomFieldFilterControlsManager;
use Sam\CustomField\Lot\Qform\LotCustomFieldFilterControlsManagerCreateTrait;
use Sam\Qform\Longevity\FormStateLongevityAwareTrait;
use Sam\Qform\LotList\Barcode\Url\LotUrlBuilderByBarcode;
use Sam\Qform\QformHelperAwareTrait;

/**
 * Class CustomFieldAdminFilterHelper
 * @package Sam\Qform\LotList
 */
class LotListCustomFieldAdminFilterHelper extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use BackUrlParserAwareTrait;
    use FormStateLongevityAwareTrait;
    use LotCustomFieldFilterControlsManagerCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use ParamFetcherForGetAwareTrait;
    use QformHelperAwareTrait;
    use ServerRequestReaderAwareTrait;

    public static bool $isFocus = false;
    public ?LotCustomFieldFilterControlsManager $customFieldFilterControlsManager = null;
    protected QForm|QPanel|null $parent = null;
    protected string $groupId = '';
    /**
     * @var LotItemCustField[]|null
     */
    protected ?array $customFields = null;
    protected ?array $getParams = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param QForm|QPanel $parent
     * @return static
     */
    public function setParent(QForm|QPanel $parent): static
    {
        $this->parent = $parent;
        $this->customFieldFilterControlsManager = null;
        return $this;
    }

    /**
     * @return QForm|QPanel
     */
    public function getParent(): QForm|QPanel
    {
        if ($this->parent === null) {
            throw new LogicException('Parent object not defined');
        }
        return $this->parent;
    }

    /**
     * @param string $functionName
     * @return static
     */
    public function bindActionToBarcodeCustomField(string $functionName): static
    {
        $customFields = $this->getManager()->getCustomFields();
        if (count($customFields)) {
            $controls = $this->getManager()->getControls();
            foreach ($customFields as $lotCustomField) {
                if (
                    $lotCustomField->Barcode
                    && $functionName
                ) { // Only add enter key event on barcode type custom field
                    $paramKey = $this->getManager()->makeParamKey($lotCustomField->Id);
                    /** @var QTextBox $txtBarcode */
                    $txtBarcode = $controls[$paramKey];
                    $actionObject = $this->parent instanceof QPanel
                        ? new QServerControlAction($this->parent, $functionName)
                        : new QServerAction($functionName);
                    $txtBarcode->AddAction(
                        new QEnterKeyEvent(),
                        $actionObject
                    );
                    if (!self::$isFocus) { // The first barcode field should be automatically auto-focused
                        $txtBarcode->SetFocus();
                        self::$isFocus = true;
                    }
                }
            }
        }
        return $this;
    }

    /**
     * @param string $formId
     * @param string $controlId
     * @param string $parameter
     * @noinspection PhpUnusedParameterInspection
     */
    public function barcodeKeypress(string $formId, string $controlId, string $parameter): void
    {
        $pattern = sprintf('/^%s(\d+)/', Constants\UrlParam::CUSTOM_FIELD_PREFIX_GENERAL);
        if (!preg_match($pattern, $controlId, $matches)) {
            return;
        }

        $lotCustomFieldId = (int)$matches[1];
        $controls = $this->getManager()->getControls();
        $paramKey = $this->getManager()->makeParamKey($lotCustomFieldId);
        $barcode = $controls[$paramKey]->Text;
        if (!$barcode) {
            return;
        }

        $url = LotUrlBuilderByBarcode::new()->buildUrl($barcode, $lotCustomFieldId);
        $backUrl = $this->getServerRequestReader()->requestUri();
        $url = $this->getBackUrlParser()->replace($url, $backUrl);
        $this->createApplicationRedirector()->redirect($url);
    }

    /**
     * @return array
     */
    public function buildGetArray(): array
    {
        $get = $this->getManager()
            ->setGetParams($this->getGetParams())
            ->buildGetArray();
        $get[Constants\UrlParam::GROUP_ID] = $this->getGroupId();
        return $get;
    }

    /**
     * @return array
     */
    public function getFilterParams(): array
    {
        return $this->getManager()->getFilterParams();
    }

    /**
     * @return string
     */
    public function getJs(): string
    {
        return $this->getManager()->getJs();
    }

    /**
     * Renders Custom Fields HTML
     * @return string
     */
    public function render(): string
    {
        return $this->getManager()->getListFormatted();
    }

    /**
     * @return LotCustomFieldFilterControlsManager
     */
    public function getManager(): LotCustomFieldFilterControlsManager
    {
        if ($this->customFieldFilterControlsManager === null) {
            $this->customFieldFilterControlsManager = $this->createLotCustomFieldFilterControlsManager()
                ->setParent($this->getParent())
                ->setGroupId($this->getGroupId())
                ->setCustomFields($this->getCustomFields())
                ->setGetParams($this->getGetParams())
                ->enablePublic(false)
                ->create();
        }
        return $this->customFieldFilterControlsManager;
    }

    /**
     * @param LotCustomFieldFilterControlsManager $manager
     * @return static
     * @noinspection PhpUnused
     */
    public function setManager(LotCustomFieldFilterControlsManager $manager): static
    {
        $this->customFieldFilterControlsManager = $manager;
        return $this;
    }

    /**
     * @return array
     */
    public function getCustomFields(): array
    {
        if ($this->customFields === null) {
            $this->customFields = $this->createLotCustomFieldLoader()->loadInAdminSearch();
        }
        return $this->customFields;
    }

    /**
     * @param array $customFields
     * @return static
     */
    public function setCustomFields(array $customFields): static
    {
        $this->customFields = $customFields;
        return $this;
    }

    /**
     * @return array
     */
    public function getGetParams(): array
    {
        if ($this->getParams === null) {
            $this->getParams = $this->getParamFetcherForGet()->getParameters();
        }
        return $this->getParams;
    }

    /**
     * @param array $params
     * @return static
     */
    public function setGetParams(array $params): static
    {
        $this->getParams = $params;
        return $this;
    }

    /**
     * @return string
     */
    public function getGroupId(): string
    {
        return $this->groupId;
    }

    /**
     * @param string $groupId
     * @return static
     */
    public function setGroupId(string $groupId): static
    {
        $this->groupId = trim($groupId);
        return $this;
    }
}
