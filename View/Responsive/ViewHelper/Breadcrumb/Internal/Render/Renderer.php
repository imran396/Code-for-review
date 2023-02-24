<?php
/**
 * SAM-4500: Front-end breadcrumb
 * https://bidpath.atlassian.net/browse/SAM-4500
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\ViewHelper\Breadcrumb\Internal\Render;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\View\Responsive\ViewHelper\Breadcrumb\Internal\Build\Internal\Collection;

/**
 * Class Renderer
 * @package Sam\View\Responsive\ViewHelper\Breadcrumb\Internal\Render
 */
class Renderer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Collection $collection
     * @return string
     */
    public function render(Collection $collection): string
    {
        $separator = $this->cfg()->get('core->responsive->breadcrumb->separator');
        $data = $collection->getData();
        if (!$data) {
            return '';
        }

        $output = '<ol class="breadcrumbs" vocab="https://schema.org/" typeof="BreadcrumbList">';
        foreach ($data as $key => $crumb) {
            if ($key > 0) {
                $output .= $separator;
            }
            if ($crumb['url'] === null) {
                $output .= '<li class="' . $crumb['cssClassName'] . '" property="itemListElement" typeof="ListItem">'
                    . '<span property="name">' . $crumb['title'] . '</span>'
                    . '<meta property="position" content="' . ($key + 1) . '"></li>';
            } else {
                $output .= '<li class="' . $crumb['cssClassName'] . '" property="itemListElement" typeof="ListItem">'
                    . '<a property="item" typeof="WebPage" href="' . $crumb['url'] . '">'
                    . '<span property="name">' . $crumb['title'] . '</span></a><meta property="position" content="' . ($key + 1) . '"><li>';
            }
        }
        return $output . "</ol>";
    }
}
