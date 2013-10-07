<?php

namespace Jeka\ImageBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;

class ImageManager implements ImageManagerInterface
{

    private $class;
    private $objectManager;
    private $repository;
    /** @var  KernelInterface */
    private $kernel;

    public function __construct($class, ObjectManager $objectManager, $kernel)
    {
        $this->class         = $class;
        $this->objectManager = $objectManager;
        $this->repository    = $objectManager->getRepository($this->class);
        $this->kernel        = $kernel;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param $id
     *
     * @return ImageInterface
     */
    public function findImageById($id)
    {
        return $this->getRepository()->find($id);
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
    public function realFileName(ImageInterface $image)
    {
        $root_dir = $this->kernel->getRootDir() . '/../web';
        $fname    = $root_dir . $image->getSrc();
        $fname    = str_replace("/", DIRECTORY_SEPARATOR, $fname);

        return $fname;
    }

    public function createImageFromFile($file, $params = array())
    {
        $image = $this->createImage();
        if (isset($params['parent_dir'])) {
            $image->setParentDir($params['parent_dir']);
        }
        $image->setCode(rand(0, 99999999));

        $this->update($image,true);
        $info = @getimagesize($file);
        if (!$info) {
            return null;
        }

        $image->setMime($info['mime']);
        $dir = dirname($this->realFileName($image));
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        if (isset($params['remove_source']) && $params['remove_source']) {
            rename($file, $this->realFileName($image));
        } else {
            copy($file, $this->realFileName($image));
        }

        $this->reloadInfo($image);
        $this->update($image, true);

        return $image;
    }


    public function update($image, $flush)
    {
        $this->objectManager->persist($image);
        if ($flush) {
            $this->flush($image);
        }
    }

    public function flush($object = null)
    {
        $this->objectManager->flush($object);
    }


    public function remove(ImageInterface $image)
    {
        $this->objectManager->remove($image);
        $this->flush();

        if (file_exists($this->realFileName($image))) {
            @unlink($this->realFileName($image));
        }
    }


    /**
     * Reload image info (width, height, filesize...)
     * @return bool
     */
    public function reloadInfo(ImageInterface $image)
    {
        $types = array("gif", "jpg", "png");
        $info  = array();

        if (!($info = GetImageSize($this->realFileName($image)))) {
            return false;
        }

        if (!isset($types[$info[2] - 1])) {
            return false;
        }

        $image->setWidth($info[0]);
        $image->setHeight($info[1]);

        $image->setSize(filesize($this->realFileName($image)));

        return true;
    }
}