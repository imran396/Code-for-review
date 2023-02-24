<?php
/**
 * Helper class for searching when data indexing is not used
 *
 * SAM-6474: Move full-text search query building and queue management logic to \Sam\SearchIndex namespace
 * SAM-1020: Front End - Search Page - Keyword Search Improvements
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Mar 01, 2012
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package com.swb.sam2.api
 */

namespace Sam\SearchIndex\Engine\Entity;

use Sam\Core\Service\CustomizableClass;
use Sam\SearchIndex\Helper\SearchIndexNormalizationHelperCreateTrait;

/**
 * Class EntitySearchQueryNormalizer
 * @package Sam\SearchIndex\Engine\Entity
 */
class EntitySearchQueryNormalizer extends CustomizableClass
{
    use SearchIndexNormalizationHelperCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        $instance = self::_new(self::class);
        return $instance;
    }

    /**
     * Normalize input data to correspond No-indexed search rules
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
            $remainText = $helper->filterByLength($remainText, 2);
            $remainText = $helper->filterToUniqueTokenData($remainText);
            $text = trim($lotNumberList . ' ' . $remainText);
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
