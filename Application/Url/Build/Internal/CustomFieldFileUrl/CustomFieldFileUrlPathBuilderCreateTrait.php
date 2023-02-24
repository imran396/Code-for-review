<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\CustomFieldFileUrl;

/**
 * Trait CustomFieldFileUrlPathBuilderCreateTrait
 * @package Sam\Application\Url\Build\Internal\CustomFieldFileUrl
 */
trait CustomFieldFileUrlPathBuilderCreateTrait
{
    /**
     * @var CustomFieldFileUrlPathBuilder|null
     */
    protected ?CustomFieldFileUrlPathBuilder $customFieldFileUrlPathBuilder = null;

    /**
     * @return CustomFieldFileUrlPathBuilder
     */
    protected function createCustomFieldFileUrlPathBuilder(): CustomFieldFileUrlPathBuilder
    {
        return $this->customFieldFileUrlPathBuilder ?: CustomFieldFileUrlPathBuilder::new();
    }

    /**
     * @param CustomFieldFileUrlPathBuilder $customFieldFileUrlPathBuilder
     * @return $this
     * @internal
     */
    public function setCustomFieldFileUrlPathBuilder(CustomFieldFileUrlPathBuilder $customFieldFileUrlPathBuilder): static
    {
        $this->customFieldFileUrlPathBuilder = $customFieldFileUrlPathBuilder;
        return $this;
    }
}
