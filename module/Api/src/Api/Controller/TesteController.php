<?php

namespace Api\Controller;

use Application\Model\ORM\RepositorioORM;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class TesteController extends AbstractRestfulController {

    public function getList() {   // Action used for GET requests without resource Id
        $this->setResponseWithHeader();
        return new JsonModel(
                array('data' =>
            array(
                array('id' => 1, 'name' => 'Mothership', 'band' => 'Led Zeppelin'),
                array('id' => 2, 'name' => 'Coda', 'band' => 'Led Zeppelin'),
            )
                )
        );
    }

    public function get($id) {   // Action used for GET requests with resource Id
        return new JsonModel(array("data" => array('id' => 1, 'name' => 'Mothership', 'band' => 'Led Zeppelin')));
    }

    public function create($data) {   // Action used for POST requests
        $this->setResponseWithHeader();
        return new JsonModel(array('data' => array('id' => 3, 'name' => 'New Album', 'band' => 'New Band')));
    }

    public function update($id, $data) {   // Action used for PUT requests
        $this->setResponseWithHeader();
        return new JsonModel(array('data' => array('id' => 3, 'name' => 'Updated Album', 'band' => 'Updated Band')));
    }

    public function delete($id) {   // Action used for DELETE requests
        $this->setResponseWithHeader();
        return new JsonModel(array('data' => 'album id 3 deleted'));
    }

    public function setResponseWithHeader() {
        $response = $this->getResponse();
        $response->getHeaders()
                ->addHeaderLine('Access-Control-Allow-Origin', '*')
                ->addHeaderLine('Access-Control-Allow-Methods', 'POST PUT DELETE GET');
    }

    private $_doctrineORMEntityManager;
    private $repositorio;

    public function __construct(EntityManager $doctrineORMEntityManager = null) {

        if (!is_null($doctrineORMEntityManager)) {
            $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
        }
    }

    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }

    public function getRepositorio() {
        if (empty($this->repositorio)) {
            $this->repositorio = new RepositorioORM($this->getDoctrineORMEntityManager());
        }
        return $this->repositorio;
    }

}
