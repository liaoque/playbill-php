<?php

use Jcupitt\Vips;

class PlaybillController extends Yaf_Controller_Abstract
{
    protected MongoDB\Driver\Manager $manager;

    public function init()
    {
        $this->manager = Yaf_Registry::get(MongoDB\Driver\Manager::class);
    }

    /**
     * @param int $id
     * @return bool
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function queryAction($id = 0)
    {
        if (empty($id)) {
            return \AppResponse\AppResponse::success([]);
        }
        $query = new MongoDB\Driver\Query([
            '_id' => new \MongoDB\BSON\ObjectId($id)
        ]);
        $rows = $this->manager->executeQuery('playbill.poster', $query);
        return \AppResponse\AppResponse::success($rows->toArray());
    }

    public function saveAction()
    {
        $params = $this->getRequest()->getParams();
        $oid = '';
        if ($params) {
            $bulkWrite = new MongoDB\Driver\BulkWrite();
            $insert = $bulkWrite->insert($params);
            $this->manager->executeBulkWrite('playbill.poster', $bulkWrite);
            $oid = $insert->__toString();
        }
        return \AppResponse\AppResponse::success(['oid' => $oid]);
    }

    /**
     * @param int $id
     * @throws Yaf_Exception
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function viewAction($id = 0)
    {
        if (empty($id)) {
            throw new Yaf_Exception('11', \AppResponse\AppResponsePlayBill::CODE_VIEW);
        }
        $query = new MongoDB\Driver\Query([
            '_id' => new \MongoDB\BSON\ObjectId($id)
        ]);
        $rows = $this->manager->executeQuery('playbill.poster', $query);

        foreach ($rows as $row){
            $this->loadJson($row->data);

        }
    }


    public function loadJson($data){
        $image = Vips\Image::newFromArray([[1, 2, 3], [4, 5, 6]]);
        $image = $image->linear(1, 1);

        $objects = $data->objects;
        $backgroundImage = $data->backgroundImage;
        var_dump(($backgroundImage));
//        foreach ($objects as $value ){
//            foreach ($value as $key => $value2 ){
//                if(!isset($backgroundImage->$key)){
//                    var_dump($key);
//                }
//            }
//        }


    }


}
