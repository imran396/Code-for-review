<?php
/**
 * Helper methods that inform you about available placeholders.
 * $keys array contains list of available placeholder keys, that are grouped by type, and related info (eg. language label)
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Mar 2, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Core;

use InvalidArgumentException;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Core\Placeholder\Detect\Detector;
use Sam\Details\Core\Placeholder\Placeholder;

/**
 * Class PlaceholderManager
 * @package Sam\Details
 */
class PlaceholderManager extends CustomizableClass
{
    use ConfigManagerAwareTrait;
    use MemoryCacheManagerAwareTrait;

    /**
     * @var Placeholder[]|null
     */
    protected ?array $actualPlaceholders = null;
    protected ?string $template = null;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * We want to detect existing in template placeholders to avoid redundant processing
     * @return Placeholder[]
     */
    public function getActualPlaceholders(): array
    {
        if ($this->actualPlaceholders === null) {
            $keysByType = $this->getConfigManager()->getKeysGroupedByType();
            $template = $this->getTemplate();
            $templateKey = md5($template);
            $cacheKey = sprintf(Constants\MemoryCache::ACTUAL_PLACEHOLDERS_TEMPLATE, $templateKey);
            $memoryCacheManager = $this->getMemoryCacheManager();
            $actualPlaceholders = null;
            if ($memoryCacheManager->has($cacheKey)) {
                $actualPlaceholders = $memoryCacheManager->get($cacheKey);
            }
            if ($actualPlaceholders) {
                $this->actualPlaceholders = $actualPlaceholders;
            } else {
                $this->actualPlaceholders = Detector::new()->detectActualPlaceholders($template, $keysByType);
                $memoryCacheManager->set($cacheKey, $this->actualPlaceholders);
            }
        }
        return $this->actualPlaceholders;
    }

    public function setActualPlaceholders(array $placeholders): PlaceholderManager
    {
        $this->actualPlaceholders = $placeholders;
        return $this;
    }

    /**
     * Collect and return keys of all actual placeholders that are related to custom fields.
     * It includes sub-placeholders of composite placeholders.
     * @return string[]
     */
    public function getActualCustomFieldPlaceholderKeys(): array
    {
        $keys = [];
        foreach ($this->getActualPlaceholders() as $placeholder) {
            if (in_array($placeholder->getType(), Constants\Placeholder::$customFieldTypes, true)) {
                $keys[] = $placeholder->getKey();
            } elseif ($placeholder->getType() === Constants\Placeholder::COMPOSITE) {
                foreach ($placeholder->getSubPlaceholders() as $subPlaceholder) {
                    if (in_array($subPlaceholder->getType(), Constants\Placeholder::$customFieldTypes, true)) {
                        $keys[] = $subPlaceholder->getKey();
                    }
                }
            }
            elseif ($placeholder->getType() === Constants\Placeholder::BEGIN_END) {
                /**
                 * Check if BEGIN-END placeholder refers to custom field.
                 * There should be one sub-placeholder in BEGIN-END placeholder, however let it be a loop.
                 */
                foreach ($placeholder->getSubPlaceholders() as $subPlaceholder) {
                    if (in_array($subPlaceholder->getType(), Constants\Placeholder::$customFieldTypes, true)) {
                        $keys[] = $subPlaceholder->getKey();
                    }
                }
            }
        }
        return array_unique($keys);
    }

    /**
     * Return keys of actual placeholders (with sub-placeholders of composite)
     */
    public function getActualKeys(bool $includeComposite = false, bool $onlyAvailable = false): array
    {
        $keys = [];
        foreach ($this->getActualPlaceholders() as $placeholder) {
            if ($placeholder->getType() === Constants\Placeholder::COMPOSITE) {
                if ($includeComposite) {
                    foreach ($placeholder->getSubPlaceholders() as $subPlaceholder) {
                        if ($subPlaceholder->getType() !== Constants\Placeholder::INLINE_TEXT) {
                            $isAvailable = $this->getConfigManager()->getOption($subPlaceholder->getKey(), 'available');
                            if (
                                !$onlyAvailable
                                || $isAvailable
                            ) {
                                $keys[] = $subPlaceholder->getKey();
                            }
                        }
                    }
                }
            } else {
                $isAvailable = $this->getConfigManager()->getOption($placeholder->getKey(), 'available');
                if (
                    !$onlyAvailable
                    || $isAvailable
                ) {
                    $keys[] = $placeholder->getKey();
                }
            }
        }
        return array_unique($keys);
    }

    public function getTemplate(): string
    {
        if ($this->template === null) {
            throw new InvalidArgumentException("Template not defined");
        }
        return $this->template;
    }

    /**
     * We need to init by template only for actual placeholder detection - getActualPlaceholders()
     */
    public function setTemplate(string $template): static
    {
        $this->template = $template;
        return $this;
    }
}
