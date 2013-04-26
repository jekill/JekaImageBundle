<?php

namespace Jeka\ImageBundle\Model;

class AbstractImage implements ImageInterface
{
    protected $id;

    /**
     * @var integer
     */
    protected $width;

    /**
     * @var integer
     */
    protected $height;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $mime = 'image/jpeg';

    /**
     * @var string
     */
    protected $size;

    /**
     * @var string
     */
    protected $parentDir = 'def';

    /**
     * @var string
     */
    protected $extension = 'jpg';

    /**
     * @var string
     */
    protected $alt;

    /**
     * value for 'order by'
     * @var integer
     */
    protected $pos = 0;

    /**
     * @var \DateTime
     */
    protected $createdAt;


    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set width
     *
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Get width
     *
     * @return int $width
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * Get height
     *
     * @return int $height
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set code
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return string $code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set mime
     *
     * @param string $mime
     */
    public function setMime($mime)
    {
        $this->mime = $mime;
    }

    /**
     * Get mime
     *
     * @return string $mime
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Set size
     *
     * @param int $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * Get size
     *
     * @return int $size
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set parent_dir
     *
     * @param int $parentDir
     */
    public function setParentDir($parentDir)
    {
        $this->parentDir = $parentDir;
    }

    /**
     * Get parent_dir
     *
     * @return int $parentDir
     */
    public function getParentDir()
    {
        return $this->parentDir;
    }

    /**
     * Set alt
     *
     * @param string $alt
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
    }

    /**
     * Get alt
     *
     * @return string $alt
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set pos
     *
     * @param int $pos
     */
    public function setPos($pos)
    {
        $this->pos = $pos;
    }

    /**
     * Get pos
     *
     * @return int $pos
     */
    public function getPos()
    {
        return $this->pos;
    }

    /**
     * Set extension
     *
     * @param string $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * Get extension
     *
     * @return string $extension
     */
    public function getExtension()
    {
        return $this->extension;
    }


    public function getBaseImageDir()
    {
        return '/uploads/images';
    }


    /**
     * image file name (eq: 111_abcdef0193.jpg)
     *
     * @return string
     */
    function getFileName()
    {
        return $this->getId() . '_' . $this->getCode() . '.' . $this->getExtension();
    }


    public function getSrcImageDir()
    {
        $src_dir = $this->getBaseImageDir();
        if ($this->getParentDir()) {
            $src_dir .= '/' . $this->getParentDir();
        }

        return $src_dir;
    }

    /**
     * src from image tag
     *
     * @return string
     */
    public function getSrc($options = array())
    {
        $fname = $this->getFileName();
        $src   = $this->getSrcImageDir() . '/';
        if (isset($options['without_uploads_dir']) && $options['without_uploads_dir']) {
            $src = str_replace('/uploads/', '', $src);
        }
        $src .= $fname;
        $src = str_replace(DIRECTORY_SEPARATOR, '/', $src);

        return $src;
    }


    public function toHTML()
    {
        return '<img src="' . $this->getSrc() . '" width="' . $this->getWidth() . '" height="' . $this->getHeight() . '" alt="' . $this->getAlt() . '"/>';
    }

    public function __toString()
    {
        return $this->toHTML();
        //return $this->getSrc();
    }
}