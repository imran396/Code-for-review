<?php
/**
 * Render SOAP tags for SOAP docs
 *
 * SAM-3874: Refactor SOAP service and apply unit tests
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 16, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Render;

use ReflectionClass;
use ReflectionProperty;
use Sam\Auction\FieldConfig\Provider\AuctionFieldConfigProviderAwareTrait;
use Sam\Auction\FieldConfig\Provider\Map\EntityMakerFieldMap as AuctionFieldConfigEntityMakerMap;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Dto\AuctionMakerInputMeta;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputMeta;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputMeta;
use Sam\Lot\LotFieldConfig\Provider\LotFieldConfigProviderAwareTrait;
use Sam\Lot\LotFieldConfig\Provider\Map\EntityMakerFieldMap as LotFieldConfigEntityMakerMap;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class CssOverride
 * @package Sam\View\Responsive\Render
 */
class SoapTags extends CustomizableClass
{
    use AuctionFieldConfigProviderAwareTrait;
    use LotFieldConfigProviderAwareTrait;
    use SystemAccountAwareTrait;
    use UserLoaderAwareTrait;

    // Special characters to replace angle brackets < >
    protected string $g = '&gt;';
    protected string $l = '&lt;';

    protected array $typesInternal = ['bool', 'float'];
    protected array $typesDocs = ['Y|N', 'decimal'];
    protected array $skip = ['Id', 'SyncNamespaceId'];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Render dto fields to soap tags doc using group/var doc attributes
     * @param $dto
     * @param string[] $skip
     * @param string[] $skipGroups
     * @param $depth
     */
    public function render($dto, array $skip = [], array $skipGroups = [], int $depth = 3): void
    {
        $complexTypes = [];
        $groups = [];
        $fieldConfigProvider = null;
        if (
            $dto instanceof AuctionLotMakerInputMeta
            || $dto instanceof LotItemMakerInputMeta
        ) {
            $fieldConfigProvider = $this->getLotFieldConfigProvider()->setFieldMap(LotFieldConfigEntityMakerMap::new());
        } elseif ($dto instanceof AuctionMakerInputMeta) {
            $fieldConfigProvider = $this->getAuctionFieldConfigProvider()->setFieldMap(AuctionFieldConfigEntityMakerMap::new());
        }

        foreach ((new ReflectionClass($dto))->getProperties() as $property) {
            if ($property->isPublic()) {
                if (
                    $fieldConfigProvider
                    && !$fieldConfigProvider->isVisible(
                        $property->name,
                        $this->getSystemAccountId()
                    )
                ) {
                    continue;
                }
                $description = $this->getDocDescription($property);
                $group = $this->getDocAttribute('group', $property);
                $tag = ucfirst($property->name);
                $type = $this->changeType($this->getDocAttribute('var', $property));
                $soapTypeHint = $this->getDocAttribute('soap-type-hint', $property);
                if ($soapTypeHint) {
                    $type = str_replace('string', $this->changeType($soapTypeHint), $type);
                }
                $required = $fieldConfigProvider
                    && $fieldConfigProvider->isRequired(
                        $property->name,
                        $this->getSystemAccountId()
                    );
                $deprecated = $this->isDocAttributeExist('deprecated', $property);
                if ($deprecated) {
                    $description = 'DEPRECATED ' . $description;
                }
                if (in_array($tag, array_merge($skip, $this->skip), true)) {
                    continue;
                }
                if ($group) {
                    if (in_array($group, $skipGroups, true)) {
                        continue;
                    }
                    $groups[$group][] = ['name' => $tag, 'type' => $type, 'description' => $description];
                    continue;
                }
                if ($this->isComplexType($type)) {
                    if (
                        $required
                        && stripos($type, 'required') === false
                    ) {
                        $description .= ' required';
                    }
                    $complexTypes[] = ['tag' => $tag, 'description' => $description, 'type' => $type];
                    continue;
                }
                if (
                    $required
                    && stripos($type, 'required') === false
                ) {
                    $type .= ' required';
                }
                $this->renderSimpleTag($tag, $type, $description, $depth);
            }
        }
        $this->renderGroups($groups);
        $this->renderComplexTypes($complexTypes);
    }

    /**
     * Change php types by doc types
     * @param string $type
     * @return string
     */
    protected function changeType(string $type): string
    {
        return str_replace($this->typesInternal, $this->typesDocs, $type);
    }

    /**
     * @param string $type
     * @return object
     */
    protected function getClassFromType(string $type): object
    {
        $class = str_replace('[]', '', $type);
        return new $class();
    }

    /**
     * @param string $name
     * @param ReflectionProperty $property
     * @return string
     */
    protected function getDocAttribute(string $name, ReflectionProperty $property): string
    {
        preg_match("/$name (.*)/", $property->getDocComment(), $attribute);
        return isset($attribute[1]) ? trim($attribute[1]) : '';
    }

    /**
     * @param string $name
     * @param ReflectionProperty $property
     * @return bool
     */
    protected function isDocAttributeExist(string $name, ReflectionProperty $property): bool
    {
        preg_match("/$name/", $property->getDocComment(), $attribute);
        return count($attribute) > 0;
    }

    /**
     * @param ReflectionProperty $property
     * @return string
     */
    protected function getDocDescription(ReflectionProperty $property): string
    {
        preg_match("/\* ([^@].+)/", $property->getDocComment(), $attribute);
        return isset($attribute[1]) ? trim($attribute[1]) : '';
    }

    /**
     * @param string $type
     * @return bool
     */
    protected function isComplexType(string $type): bool
    {
        return !preg_match('/^(decimal|integer|int|string|Y\|N)/', $type);
    }

    /**
     * @param string[][] $complexTypes
     */
    protected function renderComplexTypes(array $complexTypes): void
    {
        foreach ($complexTypes as $data) {
            $this->renderComplexTag($data['tag'], $data['type'], $data['description']);
        }
    }

    /**
     * Render complex tag, like Increments->Increment[]
     * @param string $tag
     * @param string $type
     * @param string $description
     */
    protected function renderComplexTag(string $tag, string $type, string $description): void
    {
        $class = new ReflectionClass($this->getClassFromType($type));
        $isArray = strpos($type, '[]');
        echo "\n            {$this->l}!-- $description --{$this->g}\n";
        echo "            {$this->l}$tag{$this->g}\n";
        if ($isArray) {
            echo "                {$this->l}{$class->getShortName()}{$this->g}\n";
        }
        foreach ($class->getProperties() as $property) {
            $childTag = ucfirst($property->name);
            $type = $this->changeType($this->getDocAttribute('var', $property));
            $this->renderSimpleTag($childTag, $type, '', $isArray ? 5 : 4);
        }
        if ($isArray) {
            echo "                {$this->l}/{$class->getShortName()}{$this->g}\n";
        }
        echo "            {$this->l}/$tag{$this->g}\n";
    }

    /**
     * Render tags by one group together with group title
     * @param string[][][] $groups
     */
    protected function renderGroups(array $groups): void
    {
        foreach ($groups as $group => $tags) {
            echo "\n            $this->l!-- $group: --$this->g\n";
            foreach ($tags as $tag) {
                $this->renderSimpleTag($tag['name'], $tag['type'], $tag['description']);
            }
        }
    }

    /**
     * @param string $tag
     * @param string $type
     * @param string $description
     * @param int $depth
     */
    protected function renderSimpleTag(string $tag, string $type, string $description = '', int $depth = 3): void
    {
        $tabs = '';
        for ($level = 0; $level < $depth; $level++) {
            $tabs .= '    ';
        }
        if ($description) {
            echo $tabs . "{$this->l}!-- $description --{$this->g}\n";
        }
        echo $tabs . "{$this->l}$tag{$this->g}<span class=\"value\">$type</span>{$this->l}/$tag{$this->g}\n";
    }
}
