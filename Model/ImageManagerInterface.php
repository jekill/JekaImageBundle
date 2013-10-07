<?php
/**
 * User: Eugeny Fomin <info@jeka.ru>
 * Date: 22.04.13
 */

namespace Jeka\ImageBundle\Model;

interface ImageManagerInterface
{

    /**
     * @param $id
     *
     * @return ImageInterface
     */
    public function findImageById($id);

    /**
     * @return \Jeka\ImageBundle\Document\Image
     */
    public function createImage();

    /**
     * eq: /home/jeka.ru/web/uploads/images/111_abcdef0193.jpg
     * @return string
     */
    public function realFileName(ImageInterface $image);

    public function createImageFromFile($file, $params = array());

    public function update($image, $flush);

    public function flush($entity = null);

    public function remove(ImageInterface $image);

    /**
     * Reload image info (width, height, filesize...)
     * @return bool
     */
    public function reloadInfo(ImageInterface $image);
}