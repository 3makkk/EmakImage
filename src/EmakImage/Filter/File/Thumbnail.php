<?php

namespace EmakImage\Filter\File;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Zend\Filter\AbstractFilter;
use Zend\Filter\Exception;

/**
 * Thumbnail Filter
 *
 * @author   Sven Friedemann <sven.friedemann@gmx.de>
 * @license  MIT
 */
class Thumbnail extends AbstractFilter
{


    protected $options = array(
        'width' => 200,
        'height' => 200,
        'mode' => ImageInterface::THUMBNAIL_OUTBOUND
    );


    /**
     * {@inheritdoc}
     * 'width'    => Width of thumbnail
     * 'height'   => height of thumbnail
     * 'mode'     => inset or outbound?
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->setOptions($options);
    }


    public function setWidth($width)
    {
        $this->options['width'] = $width;
    }

    public function getWidth()
    {
        return $this->options['width'];
    }

    public function setHeight($height)
    {
        $this->options['height'] = $height;
    }

    public function getHeight()
    {
        return $this->options['height'];
    }

    public function setMode($mode)
    {
        if ($mode !== ImageInterface::THUMBNAIL_INSET &&
            $mode !== ImageInterface::THUMBNAIL_OUTBOUND) {
            throw new \InvalidArgumentException('Invalid mode specified');
        }
        $this->options['mode'] = $mode;
    }

    public function getMode()
    {
        return $this->options['mode'];
    }

    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     * @throws Exception\RuntimeException If filtering $value is impossible
     * @return mixed
     */
    public function filter($value)
    {
        // An uploaded file? Retrieve the 'tmp_name'
        $isFileUpload = (is_array($value) && isset($value['tmp_name']));
        if ($isFileUpload) {
            $sourceFile = $value['tmp_name'];
        } else {
            $sourceFile = $value;
        }

        $box = new Box($this->getWidth(), $this->getHeight());

        $imagine = new Imagine();
        $image = $imagine->open($sourceFile);

        $thumbnail = $image->thumbnail($box, $this->getMode());
        $thumbnail->save();

        return $value;
    }
}
