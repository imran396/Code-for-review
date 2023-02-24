<?php
/**
 * Helping methods for html tags rendering
 *
 * SAM-4400: Refactor zf view helpers to customized classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 31, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Base;

use Sam\Core\Application\Ui\Ui;
use Sam\Core\Service\CustomizableClass;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

/**
 * Class HtmlRenderer
 * @package Sam\View\Base
 */
class HtmlRenderer extends CustomizableClass
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
     * @param string $content
     * @param array $attributes
     * @return string
     */
    public function div(string $content, array $attributes = []): string
    {
        return $this->tag('div', $content, $attributes);
    }

    /**
     * Get template using Zend View
     * @param string $name
     * @param array $data
     * @param Ui $ui
     * @return string
     */
    public function getTemplate(string $name, array $data, Ui $ui): string
    {
        $path = path()->viewScript($ui);
        return (new PhpRenderer())
            ->setResolver((new TemplatePathStack())->setPaths([$path]))
            ->render((new ViewModel($data))->setTemplate($name));
    }

    /**
     * Get template using Zend View
     * @param string $name
     * @param array $data
     * @param string $path
     * @return string
     */
    public function getLocalTemplate(string $name, array $data, string $path): string
    {
        return (new PhpRenderer())
            ->setResolver((new TemplatePathStack())->setPaths([$path]))
            ->render((new ViewModel($data))->setTemplate($name));
    }

    /**
     * Generate an html h1 tag
     * @param string $content
     * @param array $attributes
     * @return string
     */
    public function h1(string $content, array $attributes = []): string
    {
        return $this->tag('h1', $content, $attributes);
    }

    /**
     * Generate an html link
     * @param string $url
     * @param string $title
     * @param string[] $attributes
     * @return string
     */
    public function link(string $url, string $title, array $attributes = []): string
    {
        return $this->tag('a', $title, array_merge(['href' => $url], $attributes));
    }

    /**
     * Generate a link to a css file
     * @param string $url
     * @return string
     */
    public function cssLink(string $url): string
    {
        return '<link rel="stylesheet" type="text/css" href="' . $url . '">';
    }

    /**
     * Generate an html meta link
     * @param string $content
     * @param string[] $attributes
     * @return string
     */
    public function meta(string $content, array $attributes = []): string
    {
        $result = '';
        foreach ($attributes as $key => $value) {
            $result .= " $key=\"$value\"";
        }
        $output = '<meta ' . $result . ' content="' . $this->seoStripTags($content) . '" />';
        return $output;
    }

    /**
     * Generate p tag
     * @param string $content
     * @param string[] $attributes
     * @return string
     */
    public function p(string $content, array $attributes = []): string
    {
        return $this->tag('p', $content, $attributes);
    }

    /**
     * Generate a link to a javascript file
     * @param string|null $url
     * @param string $content
     * @return string
     */
    public function script(?string $url, string $content = ''): string
    {
        return '<script' . ($url ? ' src="' . $url . '"' : '') . '>' . $content . '</script>';
    }

    /**
     * @param string $content
     * @return string
     */
    public function style(string $content = ''): string
    {
        return '<style type="text/css">' . $content . '</style>';
    }

    /**
     * Strip text from html tags, remove whitespaces, plus sign, double quotes
     * @param string $text
     * @return string
     */
    public function seoStripTags(string $text): string
    {
        return trim(preg_replace('/[\s+"]+/', ' ', strip_tags($text)));
    }

    /**
     * Create title html tag
     * @param string $content
     * @return string
     */
    public function title(string $content): string
    {
        return $this->tag('title', $this->seoStripTags($content));
    }

    /**
     * Create html tag
     * @param string $name
     * @param string $content
     * @param array $attributes
     * @return string
     */
    protected function tag(string $name, string $content, array $attributes = []): string
    {
        $attributeList = '';
        foreach ($attributes as $key => $value) {
            $attributeList .= " $key=\"$value\"";
        }
        return "<{$name}{$attributeList}>{$content}</{$name}>";
    }
}
