<?php

namespace Test\Controller;

use Test\Model\TestTable;
use Zend\Crypt\PublicKey\Rsa;
use Zend\Crypt\PublicKey\RsaOptions;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

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

}

