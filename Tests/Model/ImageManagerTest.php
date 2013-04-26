<?php
/**
 * User: Eugeny Fomin <info@jeka.ru>
 * Date: 24.04.13
 */

namespace Jeka\ImageBundle\Tests\Model;

use Jeka\ImageBundle\Model\ImageManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ImageManagerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->createClient();
    }


    public function testRealFileName()
    {
        $imageManager = $this->getImageManager();
        $image        = $this->getImage();

        $webRoot = self::$kernel->getRootDir();

        $this->assertEquals($webRoot . '/uploads/images/1/2/22_009988.png', $imageManager->realFileName($image));
    }

    private function getImageManager()
    {
        $container = $this->getContainer();
        $im        = new ImageManager('\Jeka\ImageBundle\Entity\Image', $container->get('doctrine')->getEntityManager(), self::$kernel);

        return $im;
    }

    /**
     * @return ContainerInterface
     */
    private function getContainer()
    {
        return self::$kernel->getContainer();
    }

    private function getImage()
    {
        $image = $this->getMockForAbstractClass('Jeka/ImageBundle/Model/AbstractImage');
        $image->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(22));
        $image->expects($this->any())
            ->method('getCode')
            ->will($this->returnValue('009988'));
        $image->expects($this->any())
            ->method('getExtension')
            ->will($this->returnValue('png'));
        $image->expects($this->any())
            ->method('getParentDir')
            ->will($this->returnValue('1/2'));

        return $image;
    }
}
