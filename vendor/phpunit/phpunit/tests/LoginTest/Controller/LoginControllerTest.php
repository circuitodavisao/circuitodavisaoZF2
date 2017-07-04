<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Login\Entity\Pessoa;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Description of IndexControllerTest
 *
 * @author leonardo
 */
class LoginControllerTest extends AbstractHttpControllerTestCase {

    protected $traceError = true;

    public function setUp() {
        $this->setApplicationConfig(
                include '/var/www/html/circuitodavisaoZF2/config/application.config.php'
        );
        parent::setUp();
    }

    public function testLoginActionCanBeAccessed() {
        $this->dispatch('/');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Login');
        $this->assertControllerName('Login\Controller\Login');
        $this->assertControllerClass('LoginController');
        $this->assertMatchedRouteName('login');
    }

//    public function testLogarActionRedirectsAfterValidPost() {
//
//        $authService = $this->getMockBuilder('Zend\Authentication\AuthenticationService')
//                ->disableOriginalConstructor()
//                ->getMock();
//        $entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
//                ->disableOriginalConstructor()
//                ->getMock();
//
//        $serviceManager = $this->getApplicationServiceLocator();
//
//        $serviceManager->setAllowOverride(true);
//
//        $serviceManager->setService(
//                'Zend\Authentication\AuthenticationService', $authService
//        );
//
//        $serviceManager->setService(
//                'Doctrine\ORM\EntityManager', $entityManager
//        );
//
//        $postData = array(
//            'email' => 'falecomleonardopereira@gmail.com',
//            'senha' => '123',
//        );
//        $this->dispatch('/logar', 'POST', $postData);
//        $this->assertResponseStatusCode(200);
//
//        $this->assertRedirectTo('/acesso');
//    }

//    protected function mockLogin() {
//        $userSessionModel = new Pessoa();
//        $userSessionModel->setId(1);
//        $userSessionModel->setNome('Tester');
//
//        $authService = $this->getMock('Zend\Authentication\AuthenticationService');
//        $authService->expects($this->any())
//                ->method('getIdentity')
//                ->will($this->returnValue($userSessionModel));
//
//        $authService->expects($this->any())
//                ->method('hasIdentity')
//                ->will($this->returnValue(true));
//
//        $this->getApplicationServiceLocator()->setAllowOverride(true);
//        $this->getApplicationServiceLocator()->setService('Zend\Authentication\AuthenticationService', $authService);
//    }
}
