<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApplicationTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Description of IndexControllerTest
 *
 * @author leonardo
 */
class IndexControllerTest extends AbstractHttpControllerTestCase {

    public function setUp() {
        $this->setApplicationConfig(
                include '/var/www/html/circuitodavisaoZF2/config/application.config.php'
        );
        parent::setUp();
    }

    public function testIndexActionCanBeAccessed() {
        $this->dispatch('/');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Application');
        $this->assertControllerName('Application\Controller\Index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');
    }

}
