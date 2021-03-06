<?php

namespace Jeka\ImageBundle\Document;

class ImageManager
{

    private $class;
    private $dm;
    private $repository;
    private $kernel;

    public function __construct($class, \Doctrine\ODM\MongoDB\DocumentManager $dm,$kernel)
    {
        $this->class = $class;
        $this->dm = $dm;
        $this->repository = $dm->getRepository($this->class);
        $this->kernel=$kernel;
        //print get_class($kernel);exit;
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

    /**
     * eq: /home/jeka.ru/web/uploads/images/111_abcdef0193.jpg
     * @return string
     */
    public function realFileName(Image $image){
        $root_dir = $this->kernel->getRootDir().'/../web';
        $fname = $root_dir . $image->getSrc();
        $fname = str_replace("/", DIRECTORY_SEPARATOR, $fname);
        return $fname;
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
        $dir = dirname($this->realFileName($image));
        if (!file_exists($dir))
        {
            mkdir($dir,0755,true);
        }

        if (isset($params['remove_source']) && $params['remove_source'])
        {
            rename($file,$this->realFileName($image));
        }
        else{
            copy($file,$this->realFileName($image));
        }

        $this->reloadInfo($image);
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

        if (file_exists($this->realFileName($image)))
        {
            @unlink($this->realFileName($image));
        }
    }


    /**
     * Reload image info (width, height, filesize...)
     * @return bool
     */
    public function reloadInfo($image)
    {
        $types = array("gif", "jpg", "png");
        $info = array();

        if (!($info = GetImageSize($this->realFileName($image))))
            return false;

        if (!isset($types[$info[2] - 1]))
            return false;

        $image->setWidth($info[0]);
        $image->setHeight($info[1]);

        $image->setSize(filesize($this->realFileName($image)));

        return true;
    }

}