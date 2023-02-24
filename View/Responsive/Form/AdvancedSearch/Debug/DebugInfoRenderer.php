<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\Debug;

use ReflectionClass;
use ReflectionProperty;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Load\AdvancedSearchLotDto;

/**
 * Class WebDebugger
 * @package Sam\View\Responsive\Form\AdvancedSearch\Debug
 */
class DebugInfoRenderer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    public const OP_DISPLAY_ON_PAGE = 'displayOnPage'; // bool

    /** @var string[] */
    protected const HIGHLIGHT = [
        'auctionId',
        'auctionLotItemId',
        'auctionStatusId',
        'auctionType',
        'lotItemId',
        'secondsBefore',
        'secondsLeft',
    ];

    /** @var string[] */
    protected const EXCLUDE = [
        'customFields',
        'lotDescription',
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @param array $optionals
     * @return string
     */
    public function render(AdvancedSearchLotDto $dto, array $optionals = []): string
    {
        if (!$this->fetchOptionalDisplayOnPage($optionals)) {
            return '';
        }

        $reflectionClass = new ReflectionClass($dto);
        $properties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);
        $elements = [];
        foreach ($properties as $property) {
            $key = $property->getName();
            if (in_array($key, self::EXCLUDE, true)) {
                continue;
            }
            $val = $dto->$key;
            $isHighlight = in_array($key, self::HIGHLIGHT, true);
            if ($isHighlight) {
                $val = "<span style='font-weight:bold'>{$val}</span>";
            }
            $elements[] = $key . ': ' . $val;
        }
        sort($elements);
        $output = implode('</br>', $elements);
        return $output;
    }

    protected function fetchOptionalDisplayOnPage(array $optionals): bool
    {
        return (bool)($optionals[self::OP_DISPLAY_ON_PAGE]
            ?? $this->cfg()->get('core->debug->web->displayOnPage'));
    }
}
