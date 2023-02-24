<?php
/**
 * Validates soap tags according to operation's dto or array of fields
 *
 * SAM-5794: SOAP call processing shouldn't ignore incorrect fields
 * SAM-6445: Soap should ignore tags with xsi:nil="true" attribute
 *
 * Project        SOAP Server
 * @author        Victor Pautoff
 * @version       SVN: $Id: $
 * @since         May 04, 2021
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Server\Request\Validate;

use Sam\Api\Soap\Server\Request\Parse\SoapRequestParser;
use Sam\Api\Soap\Server\Request\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Help\BaseCustomFieldHelper;
use Sam\EntityMaker\Account\Dto\AccountMakerInputMeta;
use Sam\EntityMaker\Auction\Dto\AuctionMakerInputMeta;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputMeta;
use Sam\EntityMaker\Location\Dto\LocationMakerInputMeta;
use Sam\EntityMaker\LotCategory\Dto\LotCategoryMakerInputMeta;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputMeta;
use Sam\EntityMaker\User\Dto\UserMakerInputMeta;

/**
 * Class SoapRequestHelper
 * @package Sam\Soap
 */
class SoapRequestValidator extends CustomizableClass
{
    use DataProviderCreateTrait;

    public const ERROR_WRONG_TAGS = 'Not allowed tags detected';

    protected ?SoapRequestParser $parser = null;

    /**
     * @var array<string, string[]|string> Allowed dto or array for operation
     */
    public array $allowedFields = [
        'CacheImages' => ['items'],
        'CreateAccount' => AccountMakerInputMeta::class,
        'CreateAuction' => AuctionMakerInputMeta::class,
        'CreateAuctionLot' => AuctionLotMakerInputMeta::class,
        'CreateItem' => LotItemMakerInputMeta::class,
        'CreateLocation' => LocationMakerInputMeta::class,
        'CreateLotCategory' => LotCategoryMakerInputMeta::class,
        'CreateUser' => UserMakerInputMeta::class,
        'DeleteAccount' => ['id'],
        'DeleteAuction' => ['id'],
        'DeleteAuctionLot' => ['id'],
        'DeleteItem' => ['id'],
        'DeleteLocation' => ['id'],
        'DeleteLotCategory' => ['id'],
        'DeleteUser' => ['id'],
        'EchoTest' => ['message'],
        'PlaceBid' => ['amount', 'auction', 'shouldNotifyUsers', 'lot', 'user'],
        'RefreshAuctionLotDates' => ['id'],
        'RegisterBidder' => ['auction', 'bidderNumber', 'forceUpdateBidderNumber', 'user'],
        'ReorderAuctionLots' => ['id'],
        'ReorderLotCategories' => ['orderBy'],
        'UpdateAccount' => AccountMakerInputMeta::class,
        'UpdateAuction' => AuctionMakerInputMeta::class,
        'UpdateAuctionLot' => AuctionLotMakerInputMeta::class,
        'UpdateItem' => LotItemMakerInputMeta::class,
        'UpdateLocation' => LocationMakerInputMeta::class,
        'UpdateLotCategory' => LotCategoryMakerInputMeta::class,
        'UpdateUser' => UserMakerInputMeta::class,
    ];
    /** @var string[] */
    protected array $locationTypeFields = ['specificEventLocation', 'specificInvoiceLocation', 'specificLocation'];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Returns an error if invalid tags are found in the request
     * @param string $request
     * @return string
     */
    public function checkAllowedTags(string $request): string
    {
        // If invalid xml provided do nothing, soap server itself return an error 'Invalid XML'
        $this->parser = SoapRequestParser::new();
        if (
            !$this->parser->parseData($request)
            || !isset($this->allowedFields[$this->parser->operation])
        ) {
            return '';
        }

        $notAllowedTags = $this->detectNotAllowedTags() ?: $this->detectNotAllowedComplexTags();
        return $notAllowedTags
            ? self::ERROR_WRONG_TAGS . ': ' . implode(', ', $notAllowedTags)
            : '';
    }

    /**
     * Compares parsed complex soap tags with operation's dto/array fields
     * @return array
     * @throws \ReflectionException
     */
    protected function detectNotAllowedComplexTags(): array
    {
        $dtoClass = $this->allowedFields[$this->parser->operation];
        if (is_array($dtoClass)) {
            return [];
        }

        $this->parser->complexTags = $this->addLocationTagForLocationFields($this->parser->complexTags);

        $notAllowedTags = [];
        // Ex. buyersPremiums => [Premium => ..., Premium2 => ...]
        foreach ($this->parser->complexTags as $tag => $content) {
            $dtoContent = $this->buildDtoComplexTagContent($dtoClass, $tag);

            // Ex. Premium => SimpleXMLElement(Amount, Fixed, ...)
            foreach ($content as $type => $fields) {
                if ($type !== $dtoContent['type']) {
                    $notAllowedTags[] = $tag . '->' . $type;
                    continue;
                }

                if ($fields->count() === 0) {
                    continue;
                }

                $notAllowedSubTags = array_diff(array_keys((array)$fields), array_keys($dtoContent['fields']));
                if ($notAllowedSubTags) {
                    $notAllowedTags[] = $tag . '->' . $type . '->' . implode(', ', $notAllowedSubTags);
                }
            }
        }
        return $notAllowedTags;
    }

    /**
     * Compares parsed soap tags with operation's dto/array fields
     * @return array
     */
    protected function detectNotAllowedTags(): array
    {
        return array_diff($this->parser->tags, $this->detectAllowedTagsForOperation());
    }

    /**
     * Returns complex types with their fields
     * @param string $dtoClass
     * @param string $complexTag
     * @return array
     * @throws \ReflectionException
     */
    protected function buildDtoComplexTagContent(string $dtoClass, string $complexTag): array
    {
        // getType() is available starting with 7.4 PHP version
        preg_match_all('/@var\s+(\S+)(.*)/m', (new \ReflectionClass($dtoClass))->getProperty($complexTag)->getDocComment(), $matches);
        $type = str_replace('[]', '', $matches[1][0]);
        $path = explode('\\', $type);

        // Sam\EntityMaker\Base\Data\Range class members start with a lowercase letter
        $fields = [];
        foreach ((new \ReflectionClass($type))->getDefaultProperties() as $key => $value) {
            $fields[ucfirst($key)] = $value;
        }

        return [
            'type' => array_pop($path),
            'fields' => $fields
        ];
    }

    /**
     * Returns dto/array fields and custom fields for operation
     * @return array
     */
    protected function detectAllowedTagsForOperation(): array
    {
        $dtoClass = $this->allowedFields[$this->parser->operation];

        if (is_array($dtoClass)) {
            return $dtoClass;
        }

        $customFieldTags = [];
        foreach ($this->loadCustomFields($dtoClass) as $name) {
            $customFieldTags[] = lcfirst(BaseCustomFieldHelper::new()->makeSoapTagByName($name));
        }
        $allowedTags = array_keys(get_class_vars($dtoClass));

        return array_merge($allowedTags, $customFieldTags);
    }

    /**
     * Loads custom fields from db by dto class name
     * @param string $dtoClass
     * @return array
     */
    protected function loadCustomFields(string $dtoClass): array
    {
        return match ($dtoClass) {
            AuctionMakerInputMeta::class => $this->createDataProvider()->loadAllAuctionCustomFieldNames(),
            LotCategoryMakerInputMeta::class,
            LotItemMakerInputMeta::class => $this->createDataProvider()->loadEditableLotCustomFieldNames(),
            UserMakerInputMeta::class => $this->createDataProvider()->loadAllUserCustomFieldNames(),
            default => [],
        };
    }

    private function addLocationTagForLocationFields(array $complexTags): array
    {
        foreach ($complexTags as $tag => $content) {
            if (in_array($tag, $this->locationTypeFields, true)) {
                $complexTags[$tag] = ['Location' => $content];
            }
        }
        return $complexTags;
    }
}
