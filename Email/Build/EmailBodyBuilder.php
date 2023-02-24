<?php
/**
 * SAM-5018 Refactor Email_Template to sub classes
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 10, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Email\Build;


use Sam\Core\Service\CustomizableClass;

/**
 * Class EmailBodyBuilder
 * @package Sam\Email\Build
 */
class EmailBodyBuilder extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param PlaceholdersAbstractBuilder $placeholdersAbstractBuilder
     * @param string $emailTplMessage
     * @return string
     */
    public function build(PlaceholdersAbstractBuilder $placeholdersAbstractBuilder, string $emailTplMessage): string
    {
        $body = $emailTplMessage;
        $placeholders = $placeholdersAbstractBuilder->build();
        foreach ($placeholders as $name => $value) {
            $body = str_replace('{' . $name . '}', $value, $body);
        }
        $body = $this->buildWithRepeatedPlaceholders($body, $placeholdersAbstractBuilder->buildRepeated());
        return $body;
    }

    /**
     * Parse repeater block and build body
     * @param string $body
     * @param array $repeatedPlaceholders
     * @return string
     */
    protected function buildWithRepeatedPlaceholders(string $body, array $repeatedPlaceholders): string
    {
        if (empty($repeatedPlaceholders)) {
            return $body;
        }
        $matches = [];
        $isFound = preg_match('/{repeater_begin}(.*){repeater_end}/is', $body, $matches);
        if ($isFound) {
            $repeaterContent = $matches[1];
            $repeaterResults = '';
            foreach ($repeatedPlaceholders as $mixRepeatedVarPairs) {
                $repeaterText = $repeaterContent;
                foreach ($mixRepeatedVarPairs as $name => $value) {
                    $repeaterText = str_replace('{' . $name . '}', $value, $repeaterText);
                }
                $repeaterResults .= $repeaterText;
            }
            // clear deleted or renamed custom fields
            $repeaterResults = preg_replace('/{custom_field_[^}]*}/', '', $repeaterResults);
            $repeaterResults = str_replace('$', '\$', $repeaterResults);
            $body = preg_replace('/{repeater_begin}.*{repeater_end}/is', $repeaterResults, $body);
        }
        return $body;
    }
}
