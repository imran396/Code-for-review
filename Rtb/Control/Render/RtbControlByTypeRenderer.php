<?php
/**
 * SAM-6732: Rtb console control rendering at server side for v3.5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           04-08, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Control\Render;

use Sam\Core\Service\CustomizableClass;

/**
 * Class RtbControlByTypeRenderer
 * @package Sam\Rtb\Control\Render
 */
class RtbControlByTypeRenderer extends CustomizableClass
{

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * YV, SAM-6732, 09.04.2021 TODO: $controlParams should be a VO instance of RtbControlDescriptor.
     * We will build it from array.
     *
     * RtbControlDescriptor will have following fields:
     *  RtbControlDescriptor::type (string),
     *  RtbControlDescriptor::id (string),
     *  RtbControlDescriptor::href (string),
     *  RtbControlDescriptor::title (string),
     *  RtbControlDescriptor::target (string),
     *  RtbControlDescriptor::style (string),
     *  RtbControlDescriptor::class (string),
     *  RtbControlDescriptor::body (string),
     *  RtbControlDescriptor::src (string),
     *
     * @param array $controlParams
     * @return string
     */
    public function renderLink(array $controlParams): string
    {
        $extraAttributes = $body = '';

        // extra attributes
        if (isset($controlParams['href'])) {
            $extraAttributes .= sprintf('href="%s" ', $controlParams['href']);
        }
        if (isset($controlParams['title'])) {
            $extraAttributes .= sprintf('title="%s" ', $controlParams['title']);
        }
        if (isset($controlParams['target'])) {
            $extraAttributes .= sprintf('target="%s" ', $controlParams['target']);
        }
        if (isset($controlParams['style'])) {
            $extraAttributes .= sprintf('style="%s" ', $controlParams['style']);
        }
        if (isset($controlParams['class'])) {
            $extraAttributes .= sprintf('class="%s" ', $controlParams['class']);
        }
        if (isset($controlParams['accesskey'])) {
            $extraAttributes .= sprintf('accesskey="%s" ', $controlParams['accesskey']);
        }

        // body
        if (isset($controlParams['html'])) {
            $body = str_replace('~', '=', $controlParams['html']);
        }

        $output = sprintf("<a id='{$controlParams['id']}' %s>%s</a>", $extraAttributes, $body);

        return $output;
    }

    /**
     * @param array $controlParams
     * @return string
     */
    public function renderDiv(array $controlParams): string
    {
        $extraAttributes = $body = '';

        // extra attributes
        if (isset($controlParams['style'])) {
            $extraAttributes .= sprintf('style="%s" ', $controlParams['style']);
        }
        if (isset($controlParams['class'])) {
            $extraAttributes .= sprintf('class="%s" ', $controlParams['class']);
        }
        // body
        if (isset($controlParams['html'])) {
            $body = str_replace('~', '=', $controlParams['html']);
        }

        $output = sprintf("<div id='{$controlParams['id']}' %s>%s</div>", $extraAttributes, $body);
        return $output;
    }

    /**
     * @param array $controlParams
     * @return string
     */
    public function renderScript(array $controlParams): string
    {
        $extraAttributes = $body = '';

        // extra attributes
        if (isset($controlParams['id'])) {
            $extraAttributes .= sprintf('id="%s" ', $controlParams['id']);
        }
        if (isset($controlParams['src'])) {
            $extraAttributes .= sprintf('src="%s" ', $controlParams['src']);
        }
        // body
        if (isset($controlParams['script'])) {
            $body = str_replace('~', '=', $controlParams['script']);
        }

        $output = sprintf("<script %s>%s</script>", $extraAttributes, $body);
        return $output;
    }

    /**
     * @param array $controlParams
     * @return string
     */
    public function renderUl(array $controlParams): string
    {
        $extraAttributes = $body = '';

        // extra attributes
        if (isset($controlParams['style'])) {
            $extraAttributes .= sprintf('style="%s" ', $controlParams['style']);
        }
        if (isset($controlParams['class'])) {
            $extraAttributes .= sprintf('class="%s" ', $controlParams['class']);
        }
        // body
        if (isset($controlParams['html'])) {
            $body = str_replace('~', '=', $controlParams['html']);
        }

        $output = sprintf("<ul id='{$controlParams['id']}' %s>%s</ul>", $extraAttributes, $body);

        return $output;
    }

    /**
     * @param array $controlParams
     * @return string
     */
    public function renderTable(array $controlParams): string
    {
        $extraAttributes = $body = '';

        // extra attributes
        if (isset($controlParams['style'])) {
            $extraAttributes .= sprintf('style="%s" ', $controlParams['style']);
        }
        if (isset($controlParams['class'])) {
            $extraAttributes .= sprintf('class="%s" ', $controlParams['class']);
        }
        if (isset($controlParams['cellpadding'])) {
            $extraAttributes .= sprintf('cellpadding="%s" ', $controlParams['cellpadding']);
        }
        if (isset($controlParams['cellspacing'])) {
            $extraAttributes .= sprintf('cellspacing="%s" ', $controlParams['cellspacing']);
        }
        if (isset($controlParams['border'])) {
            $extraAttributes .= sprintf('border="%s" ', $controlParams['border']);
        }
        // body
        if (isset($controlParams['html'])) {
            $body = str_replace('~', '=', $controlParams['html']);
        }

        $output = sprintf("<table id='{$controlParams['id']}' %s>%s</table>", $extraAttributes, $body);

        return $output;
    }

    /**
     * @param array $controlParams
     * @return string
     */
    public function renderSpan(array $controlParams): string
    {
        $extraAttributes = $body = '';

        // extra attributes
        if (isset($controlParams['style'])) {
            $extraAttributes .= sprintf('style="%s" ', $controlParams['style']);
        }
        if (isset($controlParams['class'])) {
            $extraAttributes .= sprintf('class="%s" ', $controlParams['class']);
        }
        // body
        if (isset($controlParams['html'])) {
            $body = str_replace('~', '=', $controlParams['html']);
        }

        $output = sprintf("<span id='{$controlParams['id']}' %s>%s</span>", $extraAttributes, $body);

        return $output;
    }

    /**
     * @param array $controlParams
     * @return string
     */
    public function renderImg(array $controlParams): string
    {
        $extraAttributes = '';

        // extra attributes
        if (isset($controlParams['src'])) {
            $extraAttributes .= sprintf('src="%s" ', $controlParams['src']);
        }
        if (isset($controlParams['class'])) {
            $extraAttributes .= sprintf('class="%s" ', $controlParams['class']);
        }
        if (isset($controlParams['style'])) {
            $extraAttributes .= sprintf('style="%s" ', $controlParams['style']);
        }

        $output = sprintf("<img id='{$controlParams['id']}' %s/>", $extraAttributes);

        return $output;
    }

    /**
     * @param array $controlParams
     * @return string
     */
    public function renderButton(array $controlParams): string
    {
        $extraAttributes = $body = '';

        // extra attributes
        if (isset($controlParams['type'])) {
            $extraAttributes .= sprintf('type="%s" ', $controlParams['type']);
        }
        if (isset($controlParams['value'])) {
            $extraAttributes .= sprintf('value="%s" ', $controlParams['value']);
        }
        if (isset($controlParams['style'])) {
            $extraAttributes .= sprintf('style="%s" ', $controlParams['style']);
        }
        if (isset($controlParams['class'])) {
            $extraAttributes .= sprintf('class="%s" ', $controlParams['class']);
        }
        if (isset($controlParams['accesskey'])) {
            $extraAttributes .= sprintf('accesskey="%s" ', $controlParams['accesskey']);
        }
        if (isset($controlParams['disabled'])) {
            $extraAttributes .= "disabled ";
        }
        // body
        if (isset($controlParams['html'])) {
            $body = str_replace('~', '=', $controlParams['html']);
        }

        $output = sprintf(
            "<button id='{$controlParams['id']}' name='{$controlParams['id']}' %s>%s</button>",
            $extraAttributes,
            $body
        );
        return $output;
    }

    /**
     * @param array $controlParams
     * @return string
     */
    public function renderText(array $controlParams): string
    {
        $extraAttributes = '';

        // extra attributes
        if (isset($controlParams['value'])) {
            $extraAttributes .= sprintf('value="%s" ', $controlParams['value']);
        }
        if (isset($controlParams['style'])) {
            $extraAttributes .= sprintf('style="%s" ', $controlParams['style']);
        }
        if (isset($controlParams['class'])) {
            $extraAttributes .= sprintf('class="%s" ', $controlParams['class']);
        }
        if (isset($controlParams['maxlength'])) {
            $extraAttributes .= sprintf('maxlength="%s" ', $controlParams['maxlength']);
        }
        if (isset($controlParams['accesskey'])) {
            $extraAttributes .= sprintf('accesskey="%s" ', $controlParams['accesskey']);
        }
        if (isset($controlParams['disabled'])) {
            $extraAttributes .= 'disabled ';
        }

        $output = sprintf(
            "<input type='text' id='{$controlParams['id']}' name='{$controlParams['id']}' %s/>",
            $extraAttributes
        );
        return $output;
    }

    /**
     * @param array $controlParams
     * @return string
     */
    public function renderHidden(array $controlParams): string
    {
        $extraAttributes = '';

        // extra attributes
        if (isset($controlParams['value'])) {
            $extraAttributes .= sprintf('value="%s" ', $controlParams['value']);
        }
        if (isset($controlParams['style'])) {
            $extraAttributes .= sprintf('style="%s" ', $controlParams['style']);
        }
        if (isset($controlParams['class'])) {
            $extraAttributes .= sprintf('class="%s" ', $controlParams['class']);
        }
        if (isset($controlParams['disabled'])) {
            $extraAttributes .= 'disabled ';
        }

        $output = sprintf(
            "<input type='hidden' id='{$controlParams['id']}' name='{$controlParams['id']}' %s/>",
            $extraAttributes
        );
        return $output;
    }

    /**
     * @param array $controlParams
     * @return string
     */
    public function renderTextarea(array $controlParams): string
    {
        $extraAttributes = $body = '';

        // extra attributes
        if (isset($controlParams['style'])) {
            $extraAttributes .= sprintf('style="%s" ', $controlParams['style']);
        }
        if (isset($controlParams['class'])) {
            $extraAttributes .= sprintf('class="%s" ', $controlParams['class']);
        }
        if (isset($controlParams['disabled'])) {
            $extraAttributes .= 'disabled ';
        }
        // body
        if (isset($controlParams['value'])) {
            $body = $controlParams['value'];
        }

        $output = sprintf(
            "<textarea id='{$controlParams['id']}' name='{$controlParams['id']}' %s>%s</textarea>",
            $extraAttributes,
            $body
        );
        return $output;
    }

    /**
     * @param array $controlParams
     * @return string
     */
    public function renderSelect(array $controlParams): string
    {
        $extraAttributes = $body = '';

        // extra attributes
        if (isset($controlParams['style'])) {
            $extraAttributes .= sprintf('style="%s" ', $controlParams['style']);
        }
        if (isset($controlParams['class'])) {
            $extraAttributes .= sprintf('class="%s" ', $controlParams['class']);
        }
        if (isset($controlParams['onscroll'])) {
            $extraAttributes .= sprintf('onscroll="%s" ', $controlParams['onscroll']);
        }
        if (isset($controlParams['accesskey'])) {
            $extraAttributes .= sprintf('accesskey="%s" ', $controlParams['accesskey']);
        }
        if (isset($controlParams['disabled'])) {
            $extraAttributes .= 'disabled ';
        }
        if (isset($controlParams['multiple'])) {
            $extraAttributes .= 'multiple ';
        }
        // body
        if (
            isset($controlParams['option'])
            && $controlParams['option']
        ) {
            $options = explode('|', $controlParams['option']);
            foreach ($options as $option) {
                //list($label, $value) = explode(':', $Option);
                $fields = explode(':', $option);
                $label = $fields[0] ?? '';
                $value = $fields[1] ?? '';
                $body .= sprintf('<option value="%s">%s</option>', $value, $label);
            }
        }

        $output = sprintf(
            "<select id='{$controlParams['id']}' name='{$controlParams['id']}' %s>%s</select>",
            $extraAttributes,
            $body
        );
        return $output;
    }

    /**
     * @param array $controlParams
     * @return string
     */
    public function renderCheckbox(array $controlParams): string
    {
        $extraAttributes = '';

        // extra attributes
        if (isset($controlParams['value'])) {
            $extraAttributes .= sprintf('value="%s" ', $controlParams['value']);
        }
        if (isset($controlParams['style'])) {
            $extraAttributes .= sprintf('style="%s" ', $controlParams['style']);
        }
        if (isset($controlParams['class'])) {
            $extraAttributes .= sprintf('class="%s" ', $controlParams['class']);
        }
        if (isset($controlParams['accesskey'])) {
            $extraAttributes .= sprintf('accesskey="%s" ', $controlParams['accesskey']);
        }
        if (isset($controlParams['disabled'])) {
            $extraAttributes .= 'disabled ';
        }
        if (isset($controlParams['checked'])) {
            $extraAttributes .= 'checked ';
        }

        $output = sprintf(
            "<input type='checkbox' id='{$controlParams['id']}' name='{$controlParams['id']}' %s/>",
            $extraAttributes
        );
        return $output;
    }

    /**
     * @param array $controlParams
     * @return string
     */
    public function renderIframe(array $controlParams): string
    {
        $extraAttributes = $body = '';

        // extra attributes
        if (isset($controlParams['src'])) {
            $extraAttributes .= sprintf('src="%s" ', $controlParams['src']);
        }
        if (isset($controlParams['target'])) {
            $extraAttributes .= sprintf('target="%s" ', $controlParams['target']);
        }
        if (isset($controlParams['style'])) {
            $extraAttributes .= sprintf('style="%s" ', $controlParams['style']);
        }
        if (isset($controlParams['class'])) {
            $extraAttributes .= sprintf('class="%s" ', $controlParams['class']);
        }
        // body
        if (isset($controlParams['html'])) {
            $body = str_replace('~', '=', $controlParams['html']);
        }

        $output = sprintf(
            "<iframe id='{$controlParams['id']}' %s sandbox>%s</iframe>",
            $extraAttributes,
            $body
        );
        return $output;
    }

    /**
     * @param array $controlParams
     * @return string
     */
    public function renderRadio(array $controlParams): string
    {
        $extraAttributes = '';

        // extra attributes
        if (isset($controlParams['value'])) {
            $extraAttributes .= sprintf('value="%s" ', $controlParams['value']);
        }
        if (isset($controlParams['style'])) {
            $extraAttributes .= sprintf('style="%s" ', $controlParams['style']);
        }
        if (isset($controlParams['class'])) {
            $extraAttributes .= sprintf('class="%s" ', $controlParams['class']);
        }
        if (isset($controlParams['accesskey'])) {
            $extraAttributes .= sprintf('accesskey="%s" ', $controlParams['accesskey']);
        }
        if (isset($controlParams['disabled'])) {
            $extraAttributes .= 'disabled ';
        }
        if (isset($controlParams['checked'])) {
            $extraAttributes .= 'checked ';
        }

        $name = $controlParams['name'] ?? $controlParams['id'];
        $output = sprintf(
            "<input type='radio' id='{$controlParams['id']}' name='{$name}' %s/>",
            $extraAttributes
        );
        return $output;
    }
}
