<?php
namespace Test\Model;

use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Element;
use Zend\Form\Form;

class LoginForm extends Form
{
    protected $captcha;

    public function __construct(CaptchaAdapter $captcha)
    {

        parent::__construct();

        $this->captcha = $captcha;

// add() can take either an Element/Fieldset instance,
// or a specification, from which the appropriate object
// will be built.

        $this->add(
            array(
                'name'       => 'account',
                'options'    => array(
                    'label' => '用户名',
                ),
                'type'       => 'Text',
                'attributes' =>
                    [
                        'class' => 'form-control'
                    ]
            )
        );
        $this->add(
            array(
                'name'       => 'password',
                'options'    => array(
                    'label' => '密码',
                ),
                'type'       => 'Password',
                'attributes' =>
                    [
                        'class' => 'form-control'
                    ]
            )
        );
        $this->add(
            array(
                'type'       => 'Zend\Form\Element\Captcha',
                'name'       => 'captcha',
                'options'    => array(
                    'label'   => '输入验证码',
                    'captcha' => $this->captcha,
                ),
                'attributes' =>
                    [
                        'class' => 'form-control',
                        'id'    => 'login-captcha'
                    ]
            )
        );
        $this->add(new Element\Csrf('security'));
        $this->add(
            array(
                'name'       => 'send',
                'type'       => 'Submit',
                'attributes' => array(
                    'value' => '登录',
                    'class' => 'btn btn-success'
                ),
            )
        );

// We could also define the input filter here, or
// lazy-create it in the getInputFilter() method.
    }
}