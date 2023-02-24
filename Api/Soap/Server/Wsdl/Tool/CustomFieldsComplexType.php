<?php
/**
 * CustomFieldsComplexType overrides ArrayOfTypeComplex and DefaultComplexType to add CustomFields to wsdl dynamically.
 * All methods are copied from Zend ArrayOfTypeComplex, DefaultComplexType classes, except addCustomFieldsTags() and getTypeName()
 */

namespace Sam\Api\Soap\Server\Wsdl\Tool;

use DOMDocument;
use DOMElement;
use Laminas\Soap\Exception;
use Laminas\Soap\Wsdl;
use Laminas\Soap\Wsdl\ComplexTypeStrategy\AbstractComplexTypeStrategy;
use LotItemCustField;
use ReflectionClass;
use ReflectionProperty;
use Sam\Core\Constants;
use Sam\CustomField\Auction\Help\AuctionCustomFieldHelperAwareTrait;
use Sam\CustomField\Auction\Load\AuctionCustomFieldLoaderAwareTrait;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\CustomField\User\Help\UserCustomFieldHelperAwareTrait;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\EntityMaker\Account\Dto\AccountMakerInputMeta;
use Sam\EntityMaker\Auction\Dto\AuctionMakerInputMeta;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputMeta;
use Sam\EntityMaker\Location\Dto\LocationMakerInputMeta;
use Sam\EntityMaker\LotCategory\Dto\LotCategoryMakerInputMeta;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputMeta;
use Sam\EntityMaker\User\Dto\UserMakerInputMeta;
use Sam\Lot\LotFieldConfig\Provider\LotFieldConfigProviderAwareTrait;
use Sam\Lot\LotFieldConfig\Provider\Map\EntityMakerFieldMap;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class CustomFieldsComplexType
 * @package Sam\Soap
 */
class CustomFieldsComplexType extends AbstractComplexTypeStrategy
{
    use AuctionCustomFieldHelperAwareTrait;
    use AuctionCustomFieldLoaderAwareTrait;
    use BaseCustomFieldHelperAwareTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotFieldConfigProviderAwareTrait;
    use SystemAccountAwareTrait;
    use UserCustomFieldHelperAwareTrait;
    use UserCustomFieldLoaderAwareTrait;

    /**
     * @var string[]
     */
    protected array $customFieldAutocompleteHints = [
        Constants\CustomField::TYPE_DECIMAL => 'Autocomplete from first assigned category',
        Constants\CustomField::TYPE_FULLTEXT => 'Autocomplete from first assigned category or from Parameter field',
        Constants\CustomField::TYPE_INTEGER => 'Autocomplete from first assigned category or from Parameter field',
        Constants\CustomField::TYPE_POSTALCODE => 'Autocomplete from auction default lot postal code or from Parameter field',
        Constants\CustomField::TYPE_SELECT => 'Autocomplete from first assigned category',
        Constants\CustomField::TYPE_TEXT => 'Autocomplete from first assigned category or from Parameter field',
    ];

    protected const AUTO_POPULATE_HINT = ' or Barcode is automatically generated if \'Auto populate when empty\' is checked';

    /**
     * @var string[]
     */
    private array $arrayOfStringsTypes = [
        'Category',
        'Currency',
        'Image',
    ];

    /**
     * @var string[]
     */
    private array $dtoToComplexDataType = [
        AccountMakerInputMeta::class => 'AccountData',
        AuctionMakerInputMeta::class => 'AuctionData',
        AuctionLotMakerInputMeta::class => 'AuctionLotData',
        LocationMakerInputMeta::class => 'LocationData',
        LotItemMakerInputMeta::class => 'ItemData',
        LotCategoryMakerInputMeta::class => 'LotCategoryData',
        UserMakerInputMeta::class => 'UserData',
    ];

    /**
     * @var string[]
     */
    private array $dtoWithFieldsRequiredInConfig = [
        AuctionLotMakerInputMeta::class,
        LotItemMakerInputMeta::class,
    ];

    /**
     * @var string[]
     */
    private array $types = [];

    /**
     * @var string[]
     */
    private array $typeHints = [
        'bool' => 'Y|N',
        'float' => 'decimal',
        'int' => 'integer',
    ];

    public function __construct()
    {
        $this->getLotFieldConfigProvider()->setFieldMap(EntityMakerFieldMap::new());
    }

    /**
     * Add an ArrayOfType based on the xsd:complexType syntax if type[] is
     * detected in return value doc comment.
     *
     * @param string $type
     * @return string tns:xsd-type
     * @throws Exception\InvalidArgumentException
     */
    public function addComplexType($type): string
    {
        if (($soapType = $this->scanRegisteredTypes($type)) !== null) {
            return $soapType;
        }

        $singularType = $this->getSingularPhpType($type);
        $nestingLevel = $this->getNestedCount($type);

        if ($nestingLevel === 0) {
            return $this->addComplexTypeDefault($singularType);
        }

        if ($nestingLevel !== 1) {
            throw new Exception\InvalidArgumentException(
                'ArrayOfTypeComplex cannot return nested ArrayOfObject deeper than one level. '
                . 'Use array object properties to return deep nested data.'
            );
        }

        // The following blocks define the Array of Object structure
        return $this->addArrayOfComplexType($singularType, $type);
    }

    /**
     * Add an ArrayOfType based on the xsd:complexType syntax if type[] is
     * detected in return value doc comment.
     *
     * @param string $singularType e.g. '\MyNamespace\MyClassname'
     * @param string $type e.g. '\MyNamespace\MyClassname[]'
     * @return string tns:xsd-type   e.g. 'tns:ArrayOfMyNamespace.MyClassname'
     */
    protected function addArrayOfComplexType(string $singularType, string $type): string
    {
        if (($soapType = $this->scanRegisteredTypes($type)) !== null) {
            return $soapType;
        }

        $type = $this->getContext()->translateType($singularType);
        $xsdComplexTypeName = $type . 's';
        $xsdComplexType = Wsdl::TYPES_NS . ':' . $xsdComplexTypeName;

        // Prevent duplicates
        if (in_array($singularType, $this->types, true)) {
            return $xsdComplexType;
        }

        // Register type here to avoid recursion
        $this->getContext()->addType($type, $xsdComplexType);

        // Process singular type using DefaultComplexType strategy
        $this->addComplexTypeDefault($singularType);

        // Add array type structure to WSDL document
        $dom = $this->getContext()->toDomDocument();

        $complexType = $dom->createElementNS(Wsdl::XSD_NS_URI, 'complexType');
        $complexType->appendChild($dom->createComment('ArrayOf' . $xsdComplexTypeName));
        /** @noinspection NullPointerExceptionInspection */
        $this->getContext()->getSchema()->appendChild($complexType);

        $complexType->setAttribute('name', $xsdComplexTypeName);

        $xsdSequence = $dom->createElementNS(Wsdl::XSD_NS_URI, 'sequence');
        $complexType->appendChild($xsdSequence);

        $xsdElement = $dom->createElementNS(Wsdl::XSD_NS_URI, 'element');
        $xsdSequence->appendChild($xsdElement);

        $xsdElement->setAttribute('name', $type);
        $xsdElement->setAttribute('type', Wsdl::TYPES_NS . ':' . $type);
        $xsdElement->setAttribute('maxOccurs', 'unbounded');

        $this->types[] = $singularType;

        return $xsdComplexType;
    }

    /**
     * From a nested definition with type[], get the singular PHP Type
     *
     * @param string $type
     * @return string
     */
    protected function getSingularPhpType(string $type): string
    {
        return str_replace('[]', '', $type);
    }

    /**
     * Return the array nesting level based on the type name
     *
     * @param string $type
     * @return int
     */
    protected function getNestedCount(string $type): int
    {
        return substr_count($type, '[]');
    }

    /**
     * Add a complex type by recursively using all the class properties fetched via Reflection.
     *
     * @param string $type Name of the class to be specified
     * @return string XSD Type for the given PHP type
     */
    public function addComplexTypeDefault(string $type): string
    {
        if (!class_exists($type)) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'Cannot add a complex type %s that is not an object or where '
                    . 'class could not be found in "DefaultComplexType" strategy.',
                    $type
                )
            );
        }

        $class = new ReflectionClass($type);
        $phpType = $class->getName();

        if (($soapType = $this->scanRegisteredTypes($phpType)) !== null) {
            return $soapType;
        }

        $dom = $this->getContext()->toDomDocument();
        // Rename Dto class name to complex data type
        $soapTypeName = array_key_exists($phpType, $this->dtoToComplexDataType)
            ? $this->dtoToComplexDataType[$phpType]
            : $this->getContext()->translateType($phpType);
        $soapType = Wsdl::TYPES_NS . ':' . $soapTypeName;

        // Register type here to avoid recursion
        $this->getContext()->addType($phpType, $soapType);

        $defaultProperties = $class->getDefaultProperties();

        $complexType = $dom->createElementNS(Wsdl::XSD_NS_URI, 'complexType');
        $complexType->setAttribute('name', $soapTypeName);

        $all = $dom->createElementNS(Wsdl::XSD_NS_URI, 'all');

        foreach ($class->getProperties() as $property) {
            if ($property->isPublic() && preg_match_all('/@var\s+(\S+)(.*)/m', $property->getDocComment(), $matches)) {
                if (
                    strpos($property->getDocComment(), '@soapExcluded')
                    || $property->isStatic()
                ) {
                    continue;
                }
                /**
                 * @todo check if 'xsd:element' must be used here (it may not be
                 * compatible with using 'complexType' node for describing other
                 * classes used as attribute types for current class
                 */
                $element = $dom->createElementNS(Wsdl::XSD_NS_URI, 'element');
                $propertyName = $property->getName();
                // Make Dto fields uppercase
                $element->setAttribute('name', ucfirst($propertyName));
                // Always return string instead of others simple types to avoid "SOAP-ERROR: Encoding: Violation of encoding rules"
                $type = in_array(trim($matches[1][0]), ['bool', 'float', 'integer', 'int']) ? 'string' : trim($matches[1][0]);
                $element->setAttribute('type', $this->getContext()->getType($type));

                // If the default value is null, then this property is nillable.
                if ($defaultProperties[$propertyName] === null) {
                    $element->setAttribute('nillable', 'true');
                }
                $element->setAttribute('minOccurs', '0');

                if (
                    in_array($propertyName, $this->arrayOfStringsTypes, true)
                    && strpos($class->getName(), $propertyName)
                ) {
                    $element->setAttribute('maxOccurs', 'unbounded');
                    $all = $dom->createElementNS(Wsdl::XSD_NS_URI, 'sequence');
                }

                // Required field
                $isRequired = $this->getAnnotation('@soap-required', $property);
                $isRequiredConditionally = $this->getAnnotation('@soap-required-conditionally', $property);

                if (
                    in_array($phpType, $this->dtoWithFieldsRequiredInConfig)
                    && $this->getLotFieldConfigProvider()->isRequired(
                        $propertyName,
                        $this->getSystemAccountId()
                    )
                ) {
                    $isRequired = true;
                }

                if ($isRequired) {
                    $element->setAttribute('use', 'required');
                }

                // Field with documentation
                $documentationText = trim($matches[2][0]);
                $typeHint = $this->typeHints[$this->getAnnotation('@soap-type-hint', $property)] ?? '';
                $deprecated = $this->getAnnotation('@deprecated', $property);
                if (
                    $documentationText
                    || $typeHint
                    || $deprecated
                ) {
                    $annotation = $dom->createElementNS(Wsdl::XSD_NS_URI, 'annotation');

                    $documentation = $dom->createElementNS(Wsdl::XSD_NS_URI, 'documentation');
                    $documentation->textContent = trim(implode(' ', [$typeHint, $documentationText]));
                    $annotation->appendChild($documentation);

                    $defaultValue = $this->getAnnotation('@soap-default-value', $property);
                    // For all bool fields 'N' is a default value
                    if (
                        $typeHint === 'Y|N'
                        && $defaultValue === ''
                    ) {
                        $defaultValue = 'N';
                    }
                    if (
                        $defaultValue !== ''
                        || $deprecated !== ''
                        || $isRequired
                        || $isRequiredConditionally
                    ) {
                        $appinfo = $dom->createElementNS(Wsdl::XSD_NS_URI, 'appinfo');

                        if ($defaultValue) {
                            $default = $dom->createElementNS(Wsdl::XSD_NS_URI, 'Default');
                            $default->textContent = $defaultValue;
                            $appinfo->appendChild($default);
                        }

                        if (
                            $isRequired
                            || $isRequiredConditionally
                        ) {
                            $required = $dom->createElementNS(Wsdl::XSD_NS_URI, 'Required');
                            $required->textContent = $isRequired ? 'Yes' : 'Conditionally';
                            $appinfo->appendChild($required);
                        }

                        if ($deprecated !== '') {
                            $deprecated = $dom->createElementNS(Wsdl::XSD_NS_URI, 'Deprecated');
                            $deprecated->textContent = 'Y';
                            $appinfo->appendChild($deprecated);
                        }

                        $annotation->appendChild($appinfo);
                    }

                    $element->appendChild($annotation);
                }

                $all->appendChild($element);
            }
        }

        $this->addCustomFieldsTags($phpType, $dom, $all);

        $complexType->appendChild($all);
        /** @noinspection NullPointerExceptionInspection */
        $this->getContext()->getSchema()->appendChild($complexType);

        return $soapType;
    }

    /**
     * Add custom fields tags
     * @param string $phpType
     * @param DOMDocument $dom
     * @param DOMElement $all
     */
    private function addCustomFieldsTags(string $phpType, DOMDocument $dom, DOMElement $all): void
    {
        switch ($phpType) {
            case LotItemMakerInputMeta::class:
            case LotCategoryMakerInputMeta::class:
                $customFields = $this->createLotCustomFieldLoader()->loadAllEditable(true);
                break;
            case AuctionMakerInputMeta::class:
                $customFields = $this->getAuctionCustomFieldLoader()->loadAll(true);
                break;
            case UserMakerInputMeta::class:
                $customFields = $this->getUserCustomFieldLoader()->loadAll(true);
                break;
            default:
                return;
        }
        foreach ($customFields as $customField) {
            $element = $dom->createElementNS(Wsdl::XSD_NS_URI, 'element');
            if ($phpType === UserMakerInputMeta::class) {
                $element->setAttribute('name', $this->getUserCustomFieldHelper()->makeSoapTagByName($customField->Name));
            } elseif ($phpType === AuctionMakerInputMeta::class) {
                $element->setAttribute('name', $this->getAuctionCustomFieldHelper()->makeSoapTagByName($customField->Name));
            } else {
                $element->setAttribute('name', $this->getBaseCustomFieldHelper()->makeSoapTagByName($customField->Name));
            }
            /**
             * Always return 'string' type because soap returns "SOAP-ERROR: Encoding: Violation of encoding rules"
             * when types do not match and we want to handle this error by ourselves
             */
            $element->setAttribute('type', 'xsd:string');
            $element->setAttribute('nillable', 'true');
            $element->setAttribute('minOccurs', '0');

            if ($phpType === LotItemMakerInputMeta::class) {
                $hint = $this->getCustomFieldHint($customField);
                if ($hint) {
                    $annotation = $dom->createElementNS(Wsdl::XSD_NS_URI, 'annotation');
                    $documentation = $dom->createElementNS(Wsdl::XSD_NS_URI, 'documentation');
                    $documentation->textContent = $hint;
                    $annotation->appendChild($documentation);
                    $element->appendChild($annotation);
                }
            }

            if (
                $phpType === AuctionMakerInputMeta::class
                || $phpType === UserMakerInputMeta::class
            ) {
                if ($customField->Required) {
                    $element->setAttribute('use', 'required');
                }
            }

            $all->appendChild($element);
        }
    }

    /**
     * @param string $name
     * @param ReflectionProperty $property
     * @return string
     */
    private function getAnnotation(string $name, ReflectionProperty $property): string
    {
        preg_match_all("/$name\s+(\S+)(.*)/m", $property->getDocComment(), $typeMatches);
        return $typeMatches[1][0] ?? '';
    }

    /**
     * @param LotItemCustField $customField
     * @return string
     */
    private function getCustomFieldHint(LotItemCustField $customField): string
    {
        $hint = $this->customFieldAutocompleteHints[$customField->Type] ?? '';
        if ($customField->Barcode) {
            $hint .= self::AUTO_POPULATE_HINT;
        }
        return $hint;
    }
}
