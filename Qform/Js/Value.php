<?php
/**
 * It's a very simple wrapper for a variable value.
 *
 * This wrapper is needed when we render an injection to js we need to iterate by all php variables and it's impossible
 * to separate if it's a namespace with variables (eg ["namespace => [key1=>val1,...keyN => valN ]]
 * or a key with array variable (eg [[key1=>val1,...keyN => valN ]]).
 * With Value class we can be sure that a value of a variable always in the Value class.
 *
 * You can see the render method with the iteration includes/classes/Sam/Qform/Js/ValueImporter.php , renderJs method
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 22, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\Js;

/**
 * Class Value
 */
class Value
{
    private mixed $value;

    /**
     * Value constructor.
     * @param $value
     */
    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }
}
