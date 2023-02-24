<?php
/**
 * SAM-4629: Refactor custom lot report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\CustomList\Media\PrintTable;

use Sam\Core\Service\CustomizableClass;

/**
 * This class contains methods for rendering report in HTML format
 *
 * Class LotCustomListPrintTableRenderer
 * @package Sam\Report\Lot\CustomList\Media\PrintTable
 */
class LotCustomListPrintTableRenderer extends CustomizableClass
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
     * @param array $fieldsTitles
     * @return string
     */
    public function renderHeaderRow(array $fieldsTitles): string
    {
        $cells = array_map(
            static function ($title) {
                return sprintf('<th>%s</th>', $title);
            },
            $fieldsTitles
        );
        return sprintf("<thead><tr>%s</tr>\n", implode('', $cells));
    }

    /**
     * @param array $rowData
     * @return string
     */
    public function renderRow(array $rowData): string
    {
        $cells = [];
        foreach ($rowData as $field => $value) {
            $cells[] = $this->renderCell($field, $value);
        }
        return sprintf("<tr>%s</tr>\n", implode('', $cells));
    }

    /**
     * @param string $field
     * @param string $value
     * @return string
     */
    private function renderCell(string $field, string $value): string
    {
        $class = strtolower($field);
        $value = strip_tags(trim($value));
        $cellContent = '';
        if ($this->isImageUrl($field, $value)) {
            $imageUrls = explode("|", $value);
            $cellContent = $this->makeImages($imageUrls, $class);
        } elseif ($value !== '') {
            $cellContent = "<div>$value</div>";
        }

        return $this->makeTableCell($cellContent, ['class' => "custom-$class"]);
    }

    private function isImageUrl(string $field, string $value): bool
    {
        if ($field === 'WinnerReferrer') {
            return false;
        }

        return filter_var($value, FILTER_VALIDATE_URL);
    }

    /**
     * @param array $urls
     * @param string $class
     * @return string
     */
    private function makeImages(array $urls, string $class): string
    {
        $imageCells = '';
        foreach ($urls as $imageUrl) {
            $imageCells .= $this->makeTableCell(
                $this->makeImage($imageUrl, $class),
                ['style' => 'border-style:none']
            );
        }

        return "<table><tbody><tr>{$imageCells}</tr></tbody></table>";
    }

    /**
     * @param string $url
     * @param string $class
     * @return string
     */
    private function makeImage(string $url, string $class): string
    {
        return sprintf('<div><img style="max-width: 80px; max-height: 55px;" src="%s" class="custom-%s"></div>', $url, $class);
    }

    /**
     * @param string $content
     * @param array $tagAttributes
     * @return string
     */
    private function makeTableCell(string $content, array $tagAttributes): string
    {
        return sprintf('<td %s>%s</td>', $this->makeTagAttributes($tagAttributes), $content);
    }

    /**
     * @param array $attributes
     * @return string
     */
    private function makeTagAttributes(array $attributes): string
    {
        $madeAttributes = [];
        foreach ($attributes as $attribute => $value) {
            $madeAttributes[] = sprintf('%s="%s"', $attribute, $value);
        }
        return implode('', $madeAttributes);
    }
}
