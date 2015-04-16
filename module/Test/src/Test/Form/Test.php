<?php

namespace Test\Form;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilter;


/**
 * Class Test
 * @package Test\Form
 * @author Xuman
 * @version $Id$
 */
class Test extends Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->add(array(
                'name'      => 'test',
                'type'      => 'text',
                'options'   => array(
                    'label' => 'Input Text:'
                )
            ));
        $this->add(array(
                'name'      => 'tests',
                'type'      => 'submit',
                'attributes' => [
                    'value' => 'submit'
                ]
            ));
    }
    public function init()
    {
        $inputFilter = new InputFilter();
        $factory = new Factory();

        $inputFilter->add(
            $factory->createInput(
                array(
                    'name' => 'test',
                    'filters' => array(
                        array('name' => 'StringTrim')
                    ),
                    'validators' => [
                        [
                            'name' => 'StringLength',
                            'options' => [
                                'min' => 6,
                                'max' => 16
                            ]
                        ]
                    ]
                )
            )

        );
        $this->setInputFilter($inputFilter);
    }
}