<?php
namespace EmakImage\Filter\File;

use Imagine\Exception\InvalidArgumentException;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Zend\Filter\AbstractFilter;
use Zend\Filter\Exception;

/**
 * Resize Filter
 *
 * @author   Sven Friedemann <sven.friedemann@gmx.de>
 * @license  MIT
 */
class Resize extends AbstractFilter
{


    protected $options = array(
        'width' => 200,
        'height' => 200
    );


    /**
     * {@inheritdoc}
     * 'width'    => new width
     * 'height'   => new height
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

        try {
            $box = new Box($this->getWidth(), $this->getHeight());

            $imagine = new Imagine();
            $image = $imagine->open($sourceFile);
            $image = $image->resize($box);
            $image->save();
        } catch(InvalidArgumentException $e) {
            throw new \RuntimeException($e->getMessage());
        }

        return $value;
    }
}
