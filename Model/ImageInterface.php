<?php
/**
 * User: Eugeny Fomin <info@jeka.ru>
 * Date: 22.04.13
 */

namespace Jeka\ImageBundle\Model;

interface ImageInterface
{

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId();

    /**
     * Set width
     *
     * @param int $width
     */
    public function setWidth($width);

    /**
     * Get width
     *
     * @return int $width
     */
    public function getWidth();

    /**
     * Set height
     *
     * @param int $height
     */
    public function setHeight($height);

    /**
     * Get height
     *
     * @return int $height
     */
    public function getHeight();

    /**
     * Set code
     *
     * @param string $code
     */
    public function setCode($code);

    /**
     * Get code
     *
     * @return string $code
     */
    public function getCode();

    /**
     * Set mime
     *
     * @param string $mime
     */
    public function setMime($mime);

    /**
     * Get mime
     *
     * @return string $mime
     */
    public function getMime();

    /**
     * Set size
     *
     * @param int $size
     */
    public function setSize($size);

    /**
     * Get size
     *
     * @return int $size
     */
    public function getSize();

    /**
     * Set parent_dir
     *
     * @param int $parentDir
     */
    public function setParentDir($parentDir);

    /**
     * Get parent_dir
     *
     * @return int $parentDir
     */
    public function getParentDir();

    /**
     * Set alt
     *
     * @param string $alt
     */
    public function setAlt($alt);

    /**
     * Get alt
     *
     * @return string $alt
     */
    public function getAlt();

    /**
     * Set pos
     *
     * @param int $pos
     */
    public function setPos($pos);

    /**
     * Get pos
     *
     * @return int $pos
     */
    public function getPos();

    /**
     * Set extension
     *
     * @param string $extension
     */
    public function setExtension($extension);

    /**
     * Get extension
     *
     * @return string $extension
     */
    public function getExtension();

    public function getBaseImageDir();

    /**
     * image file name (eq: 111_abcdef0193.jpg)
     *
     * @return string
     */
    public function getFileName();

    public function getSrcImageDir();

    /**
     * src from image tag
     *
     * @return string
     */
    public function getSrc($options = array());

    public function toHTML();

    public function __toString();
}