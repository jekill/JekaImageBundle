<?php

namespace Jeka\ImageBundle\Document;

class ImageManager
{

    private $class;
    private $dm;
    private $repository;

    public function __construct($class, \Doctrine\ODM\MongoDB\DocumentManager $dm)
    {
        $this->class = $class;
        $this->dm = $dm;
        $this->repository = $dm->getRepository($this->class);
    }

    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param $id
     * @return Image
     */
    public function findImageById($id)
    {
        return $this->repository->find($id);
    }
}