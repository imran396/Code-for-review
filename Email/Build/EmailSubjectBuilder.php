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
 * Class EmailSubjectBuilder
 * @package Sam\Email\Build
 */
class EmailSubjectBuilder extends CustomizableClass
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
     * @param string $emailTplSubject
     * @return string
     */
    public function build(PlaceholdersAbstractBuilder $placeholdersAbstractBuilder, string $emailTplSubject): string
    {
        $subject = $emailTplSubject;
        $placeholders = $placeholdersAbstractBuilder->build();
        foreach ($placeholders as $name => $value) {
            $subject = str_replace('{' . $name . '}', (string)$value, $subject);
        }
        return $subject;
    }
}
