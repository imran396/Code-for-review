<?php
/**
 * SAM-6695: Image link prefix detection do not provide default value and are not based on account of context
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\Image\Base;

use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;

/**
 * @package Sam\Application\Url
 */
abstract class AbstractAccountDependentImageUrlConfig extends AbstractImageUrlConfig
{
    /**
     * @param int|null $accountId - pass null when constructing template url for js
     * @param array $options = [
     *     ... // regular options
     * ]
     * @return static
     */
    public function construct(?int $accountId, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        $options[UrlConfigConstants::PARAMS] = [$accountId];
        $options[UrlConfigConstants::OP_ACCOUNT_ID] = $accountId;
        $this->initOptionalAccount($options);
        $this->fromArray($options);
        return $this;
    }

    /**
     * @return int|null
     */
    public function accountId(): ?int
    {
        return $this->readIntParam(0);
    }
}
