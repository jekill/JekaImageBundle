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

    /**
     * @return \Jeka\ImageBundle\Document\Image
     */
    public function createImage()
    {
        return new $this->class;
    }

    public function createImageFromFile($file, $params = array())
    {
        $image = $this->createImage();
        if (isset($params['parent_dir'])){
            $image->setParentDir($params['parent_dir']);
        }
        $this->persist($image);
        $image->setCode(rand(0,99999999));
        $info = @getimagesize($file);
        if (!$info)
        {
            return null;
        }

        $image->setMime($info['mime']);
        $dir = dirname($image->realFileName());
        if (!file_exists($dir))
        {
            mkdir($dir,0755,true);
        }

        if (isset($params['remove_source']) && $params['remove_source'])
        {
            rename($file,$image->realFileName());
        }
        else{
            copy($file,$image->realFileName());
        }
        //chmod($image->realFileName(),0644);

        $image->reloadInfo();
        $this->persist($image);
        return $image;
    }

    public function persist($image,$flush=true)
    {
        $this->dm->persist($image);
        if($flush)
        {
            $this->flush();
        }
    }

    public function flush()
    {
        $this->dm->flush();
    }


    public function remove(Image $image)
    {
        $this->dm->remove($image);
        $this->flush();

        if (file_exists($image->realFileName()))
        {
            @unlink($image->realFileName());
        }
    }


}