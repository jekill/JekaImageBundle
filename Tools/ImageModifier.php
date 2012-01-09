<?php
namespace Jeka\ImageBundle\Tools;

use \Jeka\ImageBundle\Document\Image;

class ImageModifier implements ImageModifierInterface
{
    private $container;

    private $mime2ext = array(
        'image/jpeg' => 'jpg',
        'image/pjpeg' => 'jpg',
        'image/gif' => 'gif',
        'image/png' => 'png'
    );

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function resize(Image $image, $width, $height = null, $scale = true)
    {
        $m = new \Imagine\Gd\Imagine();
        $i = $m->open($image->realFileName());
        $size = new \Imagine\Image\Box($width,$height);
        $i->thumbnail($size);
        $i->save($image->realFileName());
        $this->reloadInfo($image);
        $dm = $this->container->get('doctrine.odm.mongodb.document_manager');
        $dm->persist($image);
        $dm->flush();

        return $image;
    }

    /**
     * Creating image from file
     * @param string $file
     * @param array $params array('parent_dir'=>'def','remove_source'=>false)
     * @return Image
     */
    public function createImageFromFile($file, $params = array())
    {
        $image = new Image();



        if (isset($params['parent_dir']))
        {
            $image->setParentDir($params['parent_dir']);
        }

        /** @var $manager \Doctrine\ODM\MongoDB\DocumentManager */
        $manager = $this->container->get('doctrine.odm.mongodb.document_manager');
        //print get_class($manager);exit;

        $manager->persist($image);
        $manager->flush();


        $image->setCode(rand(0, 99999999));
        $info = @getimagesize($file);

        if (!$info)
        {
            return null;
        }

        $image->setMime($info['mime']);
        $image->setExtension($this->getExtensionByMime($info['mime']));

        $dir = dirname($image->realFileName());
        if (!file_exists($dir))
        {
            mkdir($dir, 0755, true);
        }

        if (isset($params['remove_source']) && $params['remove_source'] == true)
        {
            rename($file, $image->realFileName());
        }
        else
        {
            copy($file, $image->realFileName());
        }

        $this->reloadInfo($image);


        $manager->persist($image);
        $manager->flush();

        return $image;
    }

    /**
     * Reload image info (width, height, filesize...)
     * This method don't save information to database
     * @return Image
     */
    public function reloadInfo(Image $image)
    {
        $types = array("gif", "jpg", "png");
        $info = array();

        if (!($info = GetImageSize($image->realFileName())))
            return false;

        if (!isset($types[$info[2] - 1]))
            return false;

        $image->setWidth($info[0]);
        $image->setHeight($info[1]);

        $image->setSize(filesize($image->realFileName()));

        return $image;
    }


    public function getExtensionByMime($mime_type, $default='jpg')
    {
        return isset($this->mime2ext[$mime_type]) ? $this->mime2ext[$mime_type] : $default;
    }
}