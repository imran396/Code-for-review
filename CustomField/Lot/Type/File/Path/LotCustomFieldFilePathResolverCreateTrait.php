<?php
/**
 * SAM-5608: Refactor lot custom field file download for web
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 01, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Type\File\Path;

/**
 * Trait LotCustomFieldFilePathResolverCreateTrait
 * @package Sam\CustomField\Lot\Type\File\Path
 */
trait LotCustomFieldFilePathResolverCreateTrait
{
    /**
     * @var LotCustomFieldFilePathResolver|null
     */
    protected ?LotCustomFieldFilePathResolver $lotCustomFieldFilePathResolver = null;

    /**
     * @return LotCustomFieldFilePathResolver
     */
    protected function createLotCustomFieldFilePathResolver(): LotCustomFieldFilePathResolver
    {
        return $this->lotCustomFieldFilePathResolver ?: LotCustomFieldFilePathResolver::new();
    }

    /**
     * @param LotCustomFieldFilePathResolver $lotCustomFieldFilePathResolver
     * @return $this
     * @internal
     */
    public function setLotCustomFieldFilePathResolver(LotCustomFieldFilePathResolver $lotCustomFieldFilePathResolver): static
    {
        $this->lotCustomFieldFilePathResolver = $lotCustomFieldFilePathResolver;
        return $this;
    }
}
