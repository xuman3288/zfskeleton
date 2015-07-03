<?php

namespace Test\Controller;

use Zend\Crypt\PublicKey\Rsa;
use Zend\Crypt\PublicKey\RsaOptions;
use Zend\Json\Server\Client;
use Zend\Mail\Transport\Sendmail;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function onDispatch(MvcEvent $e)
    {
        $this->layout('layout/test');
        return parent::onDispatch($e);
    }
    public function indexAction()
    {
        /** @var \Test\Model\TestTable $testTable */
        /*$testTable = TestTable::getInstance();
        $data = $testTable->find(1);
        var_dump($data);exit;*/
        return new ViewModel();
    }
    public function genarateRsaAction()
    {
        $rsaOptions = new RsaOptions(array(
        ));

        $rsaOptions->generateKeys(array(
                'private_key_bits' => 2048,
            ));

        file_put_contents("private.pem",$rsaOptions->getPrivateKey());
        file_put_contents("public1.pub",$rsaOptions->getPublicKey());
        return new JsonModel(['success']);
    }
    public function testAction()
    {
        $public  = file_get_contents('public1.pub');
        $private = file_get_contents('private.pem');
        //$public_key = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAixZ73r2x+HUmAB4YZ849LnoZO1Cr2aEkAn2jhBCfG6agBnLZE3i6ga65nmlqJ9QrKCiPQS5EKFpx1ZIcU0w3/NOreJRGqkIAlV1qCSR1WQZjYW0aGVO0dnlEt1sc/+CJxaEi1kjmGxbZ00DbIIchBh/syXa+s99CfEEAcuzT8dw/C9REXH3y8TFA1ngs2prqj0PFbwKJSUmgJ9pkNexE2iowTTAclUZ7BRcSBUGrfQzd/LqGdZT3Dj9/NQW4F0U0DgO9vY9ma9zWN6iCwCnkGAiG7rTYBvjPFZ/KfYYh+irTq7U8syk/hNgf9oR5OK8iWsHV8QNXXRHs3LwCoxGKqQIDAQAB";
        //$public_key = "-----BEGIN PUBLIC KEY-----\n" . chunk_split($public_key, 64, "\n") .  "-----END PUBLIC KEY-----";

        $rsa = Rsa::factory(array(
                'public_key'    => $public,
                'private_key'   => $private,
                //'binary_output' => false
            ));
        echo $rsa->getOpensslErrorString();
        $text = '{"orderId":"12999763169054705758.1387852643861011","packageName":"com.ztgame.jrzshtest.LOLTD.baidu","productId":"buy1","purchaseTime":1428481055884,"purchaseState":0,"developerPayload":"1428481051009865","purchaseToken":"cpjkjemhiolebomkdhpopahl.AO-J1OyPKMAAoxDArLMyHorUgcnoG3cLz6ITxIfL81nectOFv89p_bZ578vBU-6-OKgQ7P-WeCpoLsYN8lJT6ESINLijkTQJpwyYYaZnslDLcJ1u0vLziUPvKLkqM_RjHHyOsMn7lr1X"}';
        //$signature  = $rsa->sign($text);
        //var_dump($signature);exit;
        file_put_contents('signature1.sig',$rsa->sign($text));
        /*$signature = file_get_contents('signature.sig');
        var_dump(strlen($signature));*/
        $signature1 = file_get_contents('signature1.sig');

        $verify     = $rsa->verify($text, $signature1);

        echo $rsa->getOpensslErrorString();
        var_dump($verify);exit;
        //var_dump($verify);exit;
        return new ViewModel();
    }
    public function testgAction()
    {
        $public_key = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAixZ73r2x+HUmAB4YZ849LnoZO1Cr2aEkAn2jhBCfG6agBnLZE3i6ga65nmlqJ9QrKCiPQS5EKFpx1ZIcU0w3/NOreJRGqkIAlV1qCSR1WQZjYW0aGVO0dnlEt1sc/+CJxaEi1kjmGxbZ00DbIIchBh/syXa+s99CfEEAcuzT8dw/C9REXH3y8TFA1ngs2prqj0PFbwKJSUmgJ9pkNexE2iowTTAclUZ7BRcSBUGrfQzd/LqGdZT3Dj9/NQW4F0U0DgO9vY9ma9zWN6iCwCnkGAiG7rTYBvjPFZ/KfYYh+irTq7U8syk/hNgf9oR5OK8iWsHV8QNXXRHs3LwCoxGKqQIDAQAB";
        $public_key = "-----BEGIN PUBLIC KEY-----\n" . chunk_split($public_key, 64, "\n") .  "-----END PUBLIC KEY-----";

        $rsa = Rsa::factory(array(
                'public_key'    => $public_key
            ));
        echo $rsa->getOpensslErrorString();
        $text = '{"orderId":"12999763169054705758.1387852643861011","packageName":"com.ztgame.jrzshtest.LOLTD.baidu","productId":"buy1","purchaseTime":1428481055884,"purchaseState":0,"developerPayload":"1428481051009865","purchaseToken":"cpjkjemhiolebomkdhpopahl.AO-J1OyPKMAAoxDArLMyHorUgcnoG3cLz6ITxIfL81nectOFv89p_bZ578vBU-6-OKgQ7P-WeCpoLsYN8lJT6ESINLijkTQJpwyYYaZnslDLcJ1u0vLziUPvKLkqM_RjHHyOsMn7lr1X"}';
        $sign = 'CAAKdfH5zZCEIBAOZB9ASCiXe29uNprRwN0NhdN4Y9OZB9PnZCRRQh9efKnjTqSVTZAtVfB3awpt03erXTmkPAhNOpRyvWsROKXx04aZB4akp6lg3q9Ekwymnz3FhjDcEhvmHZAWMA8665sblfPRC4ZC3J0sIcVs5wBh88OZAmGd1b936s1tcKVZBxPXOcZCQteRRKaXjNuqbfjHcOSxQwEgCziC';
        $verify     = $rsa->verify($text, $sign);

        echo $rsa->getOpensslErrorString();
        var_dump($verify);exit;
    }
    public function testMailAction() {
        $mailSender = new Sendmail();
    }

    public function TestKendoAction()
    {
        $dl = new \Kendo\UI\DropDownList('asdf');
        $dl->dataTextField('text')
            ->dataValueField('value')
            ->attr('style', 'width:250px')
            ->dataSource([
                    ['text' => 'ssss','value' => 1],
                    ['text' => 'ssss','value' => 1],
                    ['text' => 'ssss','value' => 1],
                    ['text' => 'ssss','value' => 1],
                    ['text' => 'ssss','value' => 1],
                ]);
        $panelbar = new \Kendo\UI\PanelBar('panelbar');

        $today = new \Kendo\UI\PanelBarItem("Today");
        $today->expanded(true)
            ->addItem(
                new \Kendo\UI\PanelBarItem("Jane King"),
                new \Kendo\UI\PanelBarItem("Bob Fuller"),
                new \Kendo\UI\PanelBarItem("Lynda Kallahan"),
                new \Kendo\UI\PanelBarItem("Matt Sutnar")
            );
        $panelbar->addItem($today);

        $yesterday = new \Kendo\UI\PanelBarItem("Today");
        $yesterday
            ->addItem(
                new \Kendo\UI\PanelBarItem("Stewart "),
                new \Kendo\UI\PanelBarItem("Jane King"),
                new \Kendo\UI\PanelBarItem("Steven"),
                new \Kendo\UI\PanelBarItem("Ken Stone")
            );

        $panelbar->addItem($yesterday);

        $wednesday = new \Kendo\UI\PanelBarItem("Wednesday, February 01, 2012");
        $wednesday->addItem(
            new \Kendo\UI\PanelBarItem("Jane King"),
            new \Kendo\UI\PanelBarItem("Lynda Kallahan"),
            new \Kendo\UI\PanelBarItem("Todd "),
            new \Kendo\UI\PanelBarItem("Bob Fuller")
        );
        $panelbar->addItem($wednesday);
        $panelbar->expandMode("single");
        $tuesday = new \Kendo\UI\PanelBarItem("Tuesday, January 31, 2012");
        $tuesday->addItem(
            new \Kendo\UI\PanelBarItem("Emily Davolio"),
            new \Kendo\UI\PanelBarItem("Matt Sutnar"),
            new \Kendo\UI\PanelBarItem("Bob Fuller"),
            new \Kendo\UI\PanelBarItem("Jenn Heinlein")
        );
        $panelbar->addItem($tuesday);

        $monday = new \Kendo\UI\PanelBarItem("Monday, January 30, 2012");
        $monday->addItem(
            new \Kendo\UI\PanelBarItem("Matt Sutnar"),
            new \Kendo\UI\PanelBarItem("Joshua"),
            new \Kendo\UI\PanelBarItem("Michael"),
            new \Kendo\UI\PanelBarItem("Jenn Heinlein")
        );
        $panelbar->addItem($monday);

        return new ViewModel(['dl' => $dl->render(), 'pb' => $panelbar->render()]);
    }

    public function getMenuAction()
    {
        $menu = array(

            'wjtx' => array(
                'text'     => '武极天下',
                'index'    => 0,
                'expanded' => true,
                'items'    => array(
                    0 => array(
                        'text'  => '用户查询',
                        'index' => 0,
                        'url'   => './admin/user',
                    ),
                    1 => array(
                        'text'  => '公告管理',
                        'index' => 1,
                        'url'   => './admin/role',
                    ),
                    2 => array(
                        'text'  => '权限管理',
                        'index' => 2,
                        'url'   => './admin/permission',
                    ),
                    4 => array(
                        'text'  => '个人信息',
                        'index' => 4,
                        'url'   => './ui/admin/index/self',
                    ),
                ),
            ),
            'sss' => array(
                'text'     => '黑猫警长',
                'index'    => 0,
                'expanded' => true,
                'items'    => array(
                    0 => array(
                        'text'  => '用户查询',
                        'index' => 0,
                        'url'   => './admin/user',
                    ),
                    1 => array(
                        'text'  => '公告管理',
                        'index' => 1,
                        'url'   => './admin/role',
                    ),
                    2 => array(
                        'text'  => '权限管理',
                        'index' => 2,
                        'url'   => './admin/permission',
                    ),
                    4 => array(
                        'text'  => '个人信息',
                        'index' => 4,
                        'url'   => './ui/admin/index/self',
                    ),
                ),
            ),
        );
        /** @var \Closure $menuFilter */
        $menuFilter = null;
        $menuFilter = function ($menu) use (&$menuFilter) {
            foreach ($menu as $key => $item) {
                if (isset($item['items'])) {
                    $menu[$key]['items'] = array_values($menuFilter($item['items'], $user));
                }
            }
            return $menu;
        };

        $menu = $menuFilter($menu);

        return array(
            'menu' => array_values($menu)
        );
    }

    public function formerAction()
    {
        if ($this->request->isPost()) {
            return new JsonModel(['code' => 0, 'data' => $_POST]);
        }
        $api = $this->params('api');
        //var_dump($api);exit;
        $client = new Client('http://zf2-skeleton.localhost/server/api/' . $api);
        $config = $client->call('formConfig', []);
        //var_dump($config);exit;
        return new ViewModel(['config' => $config]);

    }

    public function gmAction()
    {
        $this->layout('layout/gm');
        $menu = array(

            'wjtx' => array(
                'text'     => '武极天下',
                'index'    => 0,
                'expanded' => true,
                'items'    => array(
                    array(
                        'text'  => '用户查询',
                        'index' => 0,
                        'url'   => './admin/user',
                    ),
                    array(
                        'text'  => '公告管理',
                        'index' => 1,
                        'url'   => './admin/role',
                    ),
                    array(
                        'text'  => '权限管理',
                        'index' => 2,
                        'url'   => './admin/permission',
                    ),
                    array(
                        'text'  => '个人信息',
                        'index' => 4,
                        'url'   => './ui/admin/index/self',
                    ),
                ),
            ),
            'sss' => array(
                'text'     => '黑猫警长',
                'index'    => 0,
                'expanded' => false,
                'items'    => array(
                    array(
                        'text'  => '用户查询',
                        'index' => 0,
                        'url'   => './admin/user',
                    ),
                    array(
                        'text'  => '公告管理',
                        'index' => 1,
                        'url'   => './admin/role',
                    ),
                    array(
                        'text'  => '权限管理',
                        'index' => 2,
                        'url'   => './admin/permission',
                    ),
                    array(
                        'text'  => '个人信息',
                        'index' => 4,
                        'url'   => './ui/admin/index/self',
                    ),
                ),
            ),
        );
        return new ViewModel(['menu' => array_values($menu)]);
    }
}

