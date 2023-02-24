<?php
/**
 * SAM-6474: Move full-text search query building and queue management logic to \Sam\SearchIndex namespace
 * SAM-1020: Front End - Search Page - Keyword Search Improvements
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\SearchIndex\Engine\Fulltext;


use Sam\Core\Service\CustomizableClass;
use Sam\SearchIndex\Helper\SearchIndexNormalizationHelperCreateTrait;

/**
 * Class FulltextSearchQueryNormalizer
 * @package Sam\SearchIndex\Engine\Fulltext
 */
class FulltextSearchQueryNormalizer extends CustomizableClass
{
    use SearchIndexNormalizationHelperCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Normalize input data to correspond Fulltext search index rules
     * We extract possible lot numbers and place them at the beginning of result
     *
     * @param string $text
     * @return string
     */
    public function normalize(string $text): string
    {
        if ($text !== '') {
            $helper = $this->createSearchIndexNormalizationHelper();
            $extractedData = $helper->extractLotNumbers($text);
            $lotNumberList = implode(' ', $extractedData['lot_numbers']);
            $remainText = $extractedData['remain_data'];
            $remainText = $helper->filter($remainText);
            $remainText = $helper->filterToUniqueTokenData($remainText);
            $text = trim($lotNumberList . ' ' . $remainText);
            $text = $helper->filterByLength($text, 2);
        }
        return $text;
    }

    /**
     * Return array of unique search tokens.
     * At first we extract lot#, then normalize remaining data and splitting it to token array
     *
     * @param string $text
     * @return array
     */
    public function splitToTokens(string $text): array
    {
        $text = $this->normalize($text);
        return explode(' ', $text);
    }
}
