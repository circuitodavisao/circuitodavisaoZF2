<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Form\View\Helper;

use Zend\Form\ElementInterface;

class FormNumber extends FormInput
{
    /**
     * Attributes valid for the input tag type="number"
     *
     * @var array
     */
    protected $validTagAttributes = [
        'name'           => true,
        'autocomplete'   => true,
        'autofocus'      => true,
        'disabled'       => true,
        'form'           => true,
        'list'           => true,
        'max'            => true,
        'min'            => true,
        'step'           => true,
        'placeholder'    => true,
        'readonly'       => true,
        'required'       => true,
        'type'           => true,
        'value'          => true
    ];

    /**
     * Determine input type to use
     *
     * @param  ElementInterface $element
     * @return string
     */
    protected function getType(ElementInterface $element)
    {
        return 'number';
    }
}
