<?php

namespace Jeka\ImageBundle\Tools;

use \Jeka\ImageBundle\Document\Image;

interface ImageModifierInterface
{
    public function __construct($container);

    public function getContainer();

    public function resize(Image $image, $width, $height = null, $scale = true);

    /**
     * Creating image from file
     * @param string $file
     * @param array $params
     * @return Image
     */
    public function createImageFromFile($file, $params = array());

    /**
     * Reload image info (width, height, filesize...)
     * This method don't save information to database
     * @return Image
     */
    public function reloadInfo(Image $image);

    public function getExtensionByMime($mime_type);

}