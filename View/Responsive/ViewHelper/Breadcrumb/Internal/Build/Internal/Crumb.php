<?php
/**
 * SAM-4500: Front-end breadcrumb
 * https://bidpath.atlassian.net/browse/SAM-4500
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\ViewHelper\Breadcrumb\Internal\Build\Internal;

use Sam\Core\Service\CustomizableClass;

/**
 * Class Crumb
 * @package Sam\View\Responsive\ViewHelper\Breadcrumb
 */
class Crumb extends CustomizableClass
{
    // --- Incoming values ---

    protected string $cssClassName;
    protected string $title;
    protected ?string $url;

    // --Constructor-- //

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Crumb constructor.
     * @param string $title
     * @param string|null $url
     * @param string $cssClassName
     * @return static
     */
    public function construct(string $title, string $cssClassName, ?string $url): static
    {
        $this->title = $title;
        $this->url = $url;
        $this->cssClassName = 'crumb-' . strtolower($cssClassName);
        return $this;
    }

    //Crumb builder method

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'url' => $this->url,
            'cssClassName' => $this->cssClassName,
        ];
    }
}
