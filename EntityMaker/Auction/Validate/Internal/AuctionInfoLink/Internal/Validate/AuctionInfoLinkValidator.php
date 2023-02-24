<?php
/**
 * SAM-10211: External Auction Info Link Breaking Auction Name Link in Invoice_Html
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           02-05, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\AuctionInfoLink\Internal\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;

/**
 * Class AuctionInfoLinkValidator
 * @package Sam\EntityMaker\Auction\Validate\Internal\AuctionInfoLink\Internal\Validate
 */
class AuctionInfoLinkValidator extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(AuctionInfoLinkValidationInput $input): bool
    {
        return $this->validateValue($input);
    }

    protected function validateValue(AuctionInfoLinkValidationInput $input): bool
    {
        if ($input->url === '') {
            return true;
        }

        $urlParser = UrlParser::new();
        if (!$urlParser->isUrl($input->url)) {
            return false;
        }

        if (
            $urlParser->hasScheme($input->url)
            && !$urlParser->hasScheme($input->url, ['http', 'https'])
        ) {
            return false;
        }

        return true;
    }
}
