<?php
/**
 * Helper functions for the Soap 1.2 API documentation
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2.api
 * @subpackage      soap12
 * @version         SVN: $Id$
 * @since           Apr 30, 2013
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Doc;

use Sam\Auction\AuctionHelperAwareTrait;
use Sam\Auction\FieldConfig\Provider\AuctionFieldConfigProviderAwareTrait;
use Sam\Core\Auction\Render\AuctionPureRenderer;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\Help\AuctionCustomFieldHelperAwareTrait;
use Sam\CustomField\Auction\Load\AuctionCustomFieldLoaderAwareTrait;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\CustomField\User\Help\UserCustomFieldHelperAwareTrait;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\Lot\LotFieldConfig\Provider\LotFieldConfigProviderAwareTrait;
use Sam\Lot\LotFieldConfig\Provider\Map\EntityMakerFieldMap;
use Sam\Rtb\Pool\Feature\RtbdPoolFeatureAvailabilityValidatorAwareTrait;
use Sam\Timezone\ApplicationTimezoneProviderAwareTrait;

/**
 * Class Sam\Soap
 */
class SoapDocumentor extends CustomizableClass
{
    use ApplicationTimezoneProviderAwareTrait;
    use AuctionCustomFieldHelperAwareTrait;
    use AuctionCustomFieldLoaderAwareTrait;
    use AuctionFieldConfigProviderAwareTrait;
    use AuctionHelperAwareTrait;
    use BaseCustomFieldHelperAwareTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotFieldConfigProviderAwareTrait;
    use RtbdPoolFeatureAvailabilityValidatorAwareTrait;
    use UserCustomFieldHelperAwareTrait;
    use UserCustomFieldLoaderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * Return values of default lot order types (separated by pipe character "|")
     * @param bool $isPrimary
     * @return string
     */
    public function getLotOrderTypes(bool $isPrimary = false): string
    {
        $lotOrderTypes = $isPrimary ? Constants\Auction::$lotOrderPrimaryTypes : Constants\Auction::$lotOrderTypes;
        return implode('|', $lotOrderTypes);
    }

    /**
     * Return description of default lot order types
     * @return string
     */
    public function getLotOrderTypesDescription(): string
    {
        $lotOrderTypeDescription = Constants\Auction::LOT_ORDER_BY_NONE . ': not defined';
        $lotOrderTypes = Constants\Auction::$lotOrderPrimaryTypes;
        foreach ($lotOrderTypes as $lotOrderType) {
            $lotOrderTypeDescription .= ', ' . $lotOrderType . ': by ' . AuctionPureRenderer::new()->makeOrderOptionName($lotOrderType);
        }
        return $lotOrderTypeDescription;
    }

    /**
     * Return array with description of lot item custom field tags
     * @param int $accountId
     * @return array
     */
    public function getLotCustomFieldDescriptions(int $accountId): array
    {
        $descriptions = [];
        $baseCustomFieldHelper = $this->getBaseCustomFieldHelper();
        $fieldConfigProvider = $this->getLotFieldConfigProvider()->setFieldMap(EntityMakerFieldMap::new());
        foreach ($this->createLotCustomFieldLoader()->loadAll() as $lotCustomField) {
            if (!$fieldConfigProvider->isVisibleCustomField(
                $lotCustomField->Id,
                $accountId
            )) {
                continue;
            }
            switch ($lotCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                    $dataType = 'integer';
                    break;
                case Constants\CustomField::TYPE_DECIMAL:
                    $dataType = 'decimal';
                    break;
                case Constants\CustomField::TYPE_DATE:
                    $dataType = 'yyyy-mm-dd hh:mm';
                    break;
                case Constants\CustomField::TYPE_SELECT:
                    $parameters = $lotCustomField->Parameters;
                    $dataType = $this->getDataTypeFromParameters($parameters);
                    break;
                default:
                    $dataType = 'string';
            }
            if ($fieldConfigProvider->isRequiredCustomField(
                $lotCustomField->Id,
                $accountId
            )) {
                $dataType .= ' required';
            }
            $descriptions[] = [
                'tag' => $baseCustomFieldHelper->makeSoapTagByName($lotCustomField->Name),
                'name' => $lotCustomField->Name,
                'type' => $dataType,
                'id' => $lotCustomField->Id,
            ];
        }
        return $descriptions;
    }

    /**
     * Return array with description of user custom field tags
     * @param int[] $panels pass null for loading all panels
     * @return array
     */
    public function getUserCustomFieldDescriptions(array $panels = []): array
    {
        $descriptions = [];
        $userCustomFields = $this->getUserCustomFieldLoader()->loadAllEditable($panels, true);
        foreach ($userCustomFields as $userCustomField) {
            switch ($userCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                    $dataType = 'integer';
                    break;
                case Constants\CustomField::TYPE_DECIMAL:
                    $dataType = 'decimal';
                    break;
                case Constants\CustomField::TYPE_DATE:
                    $dataType = 'yyyy-mm-dd hh:mm';
                    break;
                case Constants\CustomField::TYPE_CHECKBOX:
                    $dataType = 'Y|N';
                    break;
                case Constants\CustomField::TYPE_SELECT:
                    $parameters = $userCustomField->Parameters;
                    $dataType = $this->getDataTypeFromParameters($parameters);
                    break;
                default:
                    $dataType = 'string';
            }
            $descriptions[] = [
                'tag' => $this->getUserCustomFieldHelper()->makeSoapTagByName($userCustomField->Name),
                'name' => $userCustomField->Name,
                'type' => $dataType,
                'id' => $userCustomField->Id,
                'required' => $userCustomField->Required,
            ];
        }
        return $descriptions;
    }

    /**
     * Return array with description of auction custom field tags
     * @param int $accountId
     * @return array
     */
    public function getAuctionCustomFieldDescriptions(int $accountId): array
    {
        $descriptions = [];
        $auctionCustomFields = $this->getAuctionCustomFieldLoader()->loadAll();
        foreach ($auctionCustomFields as $auctionCustomField) {
            switch ($auctionCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                    $dataType = 'integer';
                    break;
                case Constants\CustomField::TYPE_DECIMAL:
                    $dataType = 'decimal';
                    break;
                case Constants\CustomField::TYPE_DATE:
                    $dataType = 'yyyy-mm-dd hh:mm';
                    break;
                case Constants\CustomField::TYPE_CHECKBOX:
                    $dataType = '0|1';
                    break;
                case Constants\CustomField::TYPE_SELECT:
                    $parameters = $auctionCustomField->Parameters;
                    $dataType = $this->getDataTypeFromParameters($parameters);
                    break;
                default:
                    $dataType = 'string';
            }
            $descriptions[] = [
                'tag' => $this->getAuctionCustomFieldHelper()->makeSoapTagByName($auctionCustomField->Name),
                'name' => $auctionCustomField->Name,
                'type' => $dataType,
                'id' => $auctionCustomField->Id,
                'required' => $this->getAuctionFieldConfigProvider()->isRequiredCustomField(
                    $auctionCustomField->Id,
                    $accountId
                ),
            ];
        }
        return $descriptions;
    }

    /**
     * @return string
     */
    public function getLotStatusNames(): string
    {
        $names = implode('|', Constants\Lot::$lotStatusNames);
        return $names;
    }

    /**
     * @param int $accountId
     * @return string possible auction types
     */
    public function getAuctionTypes(int $accountId): string
    {
        $auctionTypes = $this->getAuctionHelper()->getAvailableTypes($accountId);
        $types = implode('|', $auctionTypes);
        return $types;
    }

    /**
     * @return string
     */
    public function getAbsenteeBidDisplayValues(): string
    {
        $values = implode('|', Constants\SettingAuction::ABSENTEE_BID_DISPLAY_SOAP_VALUES);
        return $values;
    }

    /**
     * Return <Premium> tag
     * @return string
     */
    public function getBuyersPremiumRange(): string
    {
        $modeList = implode('|', Constants\BuyersPremium::$rangeModeNames);
        $output = <<<XML
                &lt;Premium&gt;
                    &lt;Amount&gt;<span class="value">decimal</span>&lt;/Amount&gt;
                    &lt;Fixed&gt;<span class="value">decimal</span>&lt;/Fixed&gt;
                    &lt;Percent&gt;<span class="value">decimal</span>&lt;/Percent&gt;
                    &lt;Mode&gt;<span class="value">{$modeList}</span>&lt;/Mode&gt;
                &lt;/Premium&gt;

XML;
        return $output;
    }

    /**
     * @param string $parameters
     * @return string
     */
    protected function getDataTypeFromParameters(string $parameters): string
    {
        $values = $this->getBaseCustomFieldHelper()->extractDropdownOptionsFromString($parameters);
        $dataType = implode('|', $values);
        return $dataType;
    }

    /**
     * @return string
     */
    public function getBillingBankAccountTypeSamValues(): string
    {
        $types = array_keys(Constants\BillingBank::ACCOUNT_TYPE_SOAP_VALUES);
        $typeList = implode('|', $types);
        return $typeList;
    }

    /**
     * @return string
     */
    public function getBillingBankAccountTypeSoapValues(): string
    {
        $types = array_values(Constants\BillingBank::ACCOUNT_TYPE_SOAP_VALUES);
        $typeList = implode('|', $types);
        return $typeList;
    }

    /**
     * @return string
     */
    public function getRtbdNameValue(): string
    {
        if ($this->getRtbdPoolFeatureAvailabilityValidator()->isAvailable()) {
            return 'string';
        }
        return '';
    }
}
