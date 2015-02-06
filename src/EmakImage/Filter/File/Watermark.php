<?php

namespace EmakImage\Filter\File;

use Imagine\Exception\InvalidArgumentException;
use Imagine\Gd\Imagine;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use Zend\Filter\AbstractFilter;
use Zend\Filter\Exception;

/**
 * Watermark Filter
 *
 * @author   Sven Friedemann <sven.friedemann@gmx.de>
 * @licence  MIT
 */
class Watermark extends AbstractFilter
{

    const POS_Y_TOP = 'top';
    const POS_Y_BOTTOM = 'bottom';
    const POS_Y_CENTER = 'center';

    const POS_X_LEFT = 'left';
    const POS_X_RIGHT = 'right';
    const POS_X_CENTER = 'center';


    protected $options = array(
        'watermark_image_path' => '/path/to/image',
        'position_x' => self::POS_X_RIGHT,
        'position_y' => self::POS_Y_BOTTOM,
        'offset_x' => 0,
        'offset_y' => 0,
    );

    public function getWaterMarkImagePath()
    {
        return $this->options['watermark_image_path'];
    }

    public function getPositionX()
    {
        return $this->options['position_x'];

    }

    public function getPositionY()
    {
        return $this->options['position_y'];
    }

    public function getOffsetX()
    {
        return $this->options['offset_x'];
    }

    public function getOffsetY()
    {
        return $this->options['offset_y'];
    }

    public function setWaterMarkImagePath($path)
    {
        if (is_file($path)) {
            $this->options['watermark_image_path'] = $path;
            return $this;
        };

        throw new \DomainException(
            sprintf('%s does not exists.', $path)
        );

    }

    public function setPositionX($positionX)
    {
        if (defined('self::POS_X_'. strtoupper($positionX))) {
            $this->options['position_x'] = $positionX;

            return $this;
        }
        throw new \InvalidArgumentException(
            sprintf('%s is not a defined x position', $positionX)
        );
    }

    public function setPositionY($positionY)
    {
        if (defined('self::POS_Y_'. strtoupper($positionY))) {
            $this->options['position_y'] = $positionY;

            return $this;
        }
        throw new \InvalidArgumentException(
            sprintf('%s is not a defined y position', $positionY)
        );
    }

    public function setOffsetY($offsetY)
    {
        if (is_int($offsetY)) {
            $this->options['offset_y'] = $offsetY;

            return $this;
        }
        throw new \InvalidArgumentException(
            sprintf('%s is not an integer', $offsetY)
        );
    }

    public function setOffsetX($offsetX)
    {
        if (is_int($offsetX)) {
            $this->options['offset_x'] = $offsetX;

            return $this;

        }
        throw new \InvalidArgumentException(
            sprintf('%s is not an integer', $offsetX)
        );
    }


    /**
     * Available options:
     *      'position_x' => [left|center|right]
     *      'position_y' => [top|center|bottom]
     *      'offset_x' => integer
     *      'offset_y' => integer
     *      'watermark/image/path' => 'path/to/file'
     * @param $options
     * @throws \DomainException
     */
    public function __construct($options = array())
    {
        $this->setOptions($options);
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
            $imagine = new Imagine();
            $image = $imagine->open($sourceFile);

            $watermarkImage = $this->getWatermarkImage();
            $pastePoint = $this->getPastePoint($image);

            $image->paste($watermarkImage, $pastePoint);
            $image->save();
        } catch(InvalidArgumentException $e) {
            throw new \RuntimeException($e->getMessage());
        }

        return $value;

    }

    /**
     * calculate watermark paste point base on position and offset Options
     *
     * @param \Imagine\Image\ImageInterface $image
     * @throws \OutOfRangeException
     * @return Point
     */
    protected function getPastePoint(ImageInterface $image)
    {
        $imageBox = $image->getSize();

        $watermarkImage = $this->getWatermarkImage();
        $watermarkBox = $watermarkImage->getSize();

        $x = $this->getXCoordinate($imageBox, $watermarkBox) + $this->getOffsetX();
        $y = $this->getYCoordinate($imageBox, $watermarkBox) + $this->getOffsetY();

        $point = new Point($x, $y);

        if ($imageBox->contains($watermarkBox, $point) === false) {
            throw new \OutOfRangeException('Position is out of image');
        }

        return $point;

    }

    /**
     * calculate X coordinate
     *
     * @param $imageBox
     * @param $watermarkBox
     * @return float|int
     */
    private function getXCoordinate(BoxInterface $imageBox, BoxInterface $watermarkBox)
    {
        switch ($this->getPositionX()) {
            case self::POS_X_LEFT:
                $xCoordinate = 0;
                break;
            case self::POS_X_CENTER:
                $xCoordinate = $imageBox->getWidth() / 2 - ($watermarkBox->getWidth() / 2);
                break;
            case self::POS_X_RIGHT:
                $xCoordinate = $imageBox->getWidth() - ($watermarkBox->getWidth());
                break;
            default:
                $xCoordinate = 0;
                exit;
        }

        return $xCoordinate;
    }

    /**
     * Calculate Y coordinate
     *
     * @param $imageBox
     * @param $watermarkBox
     * @return float|int
     */
    private function getYCoordinate(BoxInterface $imageBox, BoxInterface $watermarkBox)
    {
        switch ($this->getPositionY()) {
            case self::POS_Y_TOP:
                $yCoordinate = 0;
                break;
            case self::POS_X_CENTER:
                $yCoordinate = $imageBox->getHeight() / 2 - ($watermarkBox->getHeight() / 2);
                break;
            case self::POS_Y_BOTTOM:
                $yCoordinate = $imageBox->getHeight() - ($watermarkBox->getHeight());
                break;
            default:
                $yCoordinate = 0;
                exit;
        }

        return $yCoordinate;
    }

    /**
     * Get watermark image as imagine image object
     * @return \Imagine\Gd\Image|ImageInterface
     */
    protected function getWatermarkImage()
    {
        $imagine = new Imagine();
        $watermarkImage = $imagine->open($this->getWaterMarkImagePath());

        return $watermarkImage;
    }
}
