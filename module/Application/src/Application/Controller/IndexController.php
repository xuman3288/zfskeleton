<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use ApnsPHP_Abstract;
use ApnsPHP_Message;
use ApnsPHP_Push;
use Facebook\FacebookSession;
use MongoClient;
use Zend\Config\Config;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use ZendService\Apple\Apns\Client\Message as Client;
use ZendService\Apple\Apns\Message;
//use ZendService\Apple\Apns\Message\Alert;
use ZendService\Apple\Apns\Response\Message as Response;
use ZendService\Apple\Exception\RuntimeException;

use ZendService\Apple\Apns\Client\Feedback as FClient;
use ZendService\Apple\Apns\Response\Feedback as FResponse;


use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        //var_dump($this->getServiceLocator()->get('ApplicationConfig'));//全局配置
        //var_dump($this->getServiceLocator()->get('Config'));//autoload中的配置
        $now = time();
        $client = new Client();
        $client->open(Client::SANDBOX_URI, BASEDIR .'\data\5010_dev.pem');
        $message = new Message();
        $message->setId($now);
        $message->setToken('464610b2e0beaa1c256ca0c03f28e02aedcd06dd159293ade0b139b9d0b5f852');
        //$message->setBadge(0);
        $message->setSound('default');
        $message->setAlert('message');
        try {
            $response = $client->send($message);
        } catch (RuntimeException $e) {
            echo $e->getMessage() . PHP_EOL;
            exit(1);
        }
        $client->close();
        $code = 1;
        if ($response->getCode() != Response::RESULT_OK) {
            switch ($response->getCode()) {
                case Response::RESULT_PROCESSING_ERROR:
                    // you may want to retry
                    echo "ss";
                    break;
                case Response::RESULT_MISSING_TOKEN:
                    // you were missing a token
                    echo "cc";
                    break;
                case Response::RESULT_MISSING_TOPIC:
                    // you are missing a message id
                    echo "aa";
                    break;
                case Response::RESULT_MISSING_PAYLOAD:
                    // you need to send a payload
                    echo "ee";
                    break;
                case Response::RESULT_INVALID_TOKEN_SIZE:
                    // the token provided was not of the proper size
                    echo "qw";
                    break;
                case Response::RESULT_INVALID_TOPIC_SIZE:
                    // the topic was too long
                    echo "we";
                    break;
                case Response::RESULT_INVALID_PAYLOAD_SIZE:
                    // the payload was too large
                    echo "re";
                    break;
                case Response::RESULT_INVALID_TOKEN:
                    // the token was invalid; remove it from your system
                    echo "fd";
                    break;
                case Response::RESULT_UNKNOWN_ERROR:
                    // apple didn't tell us what happened
                    echo "tt";
                    break;
            }
        }

        var_dump($response->getCode());exit;
        return new ViewModel();
    }


    public function testMongoAction()
    {
        $m = new MongoClient();
        $db = $m->selectDB('mydb');
        $db->testData->insert(['name' => 'xuman']);
        $data = $db->testData->find(['age' => 20]);
        while($d = $data->next())
        {
            $mid = $d['_id']."";
            var_dump($mid);
        }
        echo json_encode(['code' => 0]);
        exit;
    }

    public function testFeedbackAction()
    {


        $client = new FClient();
        $client->open(FClient::SANDBOX_URI, BASEDIR .'\data\cert_dis.pem');
        $responses = $client->feedback();
        $client->close();

        foreach ($responses as $response) {

            echo $response->getTime() . ': ' . $response->getToken();
        }
        exit;
    }

    public function oooAction()
    {
        // An array of configuration data is given
        $configArray = array(
            'webhost'  => 'www.example.com',
            'database' => array(
                'adapter' => 'pdo_mysql',
                'params'  => array(
                    'host'     => 'db.example.com',
                    'username' => 'dbuser',
                    'password' => 'secret',
                    'dbname'   => 'mydatabase'
                )
            )
        );

        // Create the object-oriented wrapper using the configuration data
        $config = new Config($configArray);

        // Print a configuration datum (results in 'www.example.com')
        //echo $config->webhost;
        //echo $config->database->get('params')->get('host');
        echo $config->database->params->dbname;
        exit;
    }

    public function testFacebookAction()
    {
        FacebookSession::setDefaultApplication('736107976457282', 'aea044e96cb997da8235b8c378604ef5');
        $session = new FacebookSession('CAAKdfH5zZCEIBAEFcKME72XjLPK5CWuegVkad99LAMOMR5gx3blBZBfyyiobvSJBEnqlM4BmtcTIfYPQETpZAEk2upH5qyYE9Rw2w30OPb9kQ3B4rxSDvKyZAHgI97RDelQEUjZCGC9XVzZCZChkzQv6CVZCHsedf7jOiAYb2PFowGZAxQ7snK8AzxhLdE646Yd0F6EyOf9ozQ6mhSM7R4jG9');

      /*  FacebookSession::setDefaultApplication('723815064406183', '6a412ec5b1120edd5fe30b8aa3a0d643');
        $session = new FacebookSession('CAAFTxfkYVrsBADVcpZBZCnzAe9t7TdxZC7JagKNLuZCjHzE5HWT71yDgH6FSI2XBv0glrejFdGdcnNDpVuZBrC2vWoiV1ZC3yv7x7IZAFv7hHxZC5jTIreL9JE0hMJS1ixU6IA9MHf2zl5yGwjj2rsinpR5Fn3tAVzwU6jNMJByeq5umZCJ1gXFJbh9ZBmpHKFj15VBv772yzjDS7R7bcWUcxQczGwbjgWvxoZB2wBV56AHmoZCkm7LGvg9y');*/
        if($session) {

            try {

                $user_profile = (new FacebookRequest(
                    $session, 'GET', '/me'
                ))->execute()->getGraphObject(GraphUser::className());

                echo "Name: " . $user_profile->getName();
                var_dump($user_profile);

            } catch(FacebookRequestException $e) {

                echo "Exception occured, code: " . $e->getCode();
                echo " with message: " . $e->getMessage();

            }

        }
        exit;
    }
}
