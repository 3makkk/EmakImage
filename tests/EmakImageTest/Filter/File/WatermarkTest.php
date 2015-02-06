<?php
/**
 *
 * @author   Sven Friedemann <sven@ertellbar.de>
 * @licence  MIT
 */

namespace EmakImageTest\Filter\File;


use EmakImage\Filter\File\Watermark;

class WatermarkTest extends \PHPUnit_Framework_TestCase {


    protected $_filesPath;

    protected $_watermarkImage;

    protected $_origImage;

    protected $_image;

    protected $_newImage;


    public function setUp()
    {
        $this->_filesPath  = dirname(__DIR__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR;
        $this->_origImage   = $this->_filesPath . 'original.file';
        $this->_image    = $this->_filesPath . 'plant.jpg';

        $this->_watermarkImage   = $this->_filesPath . 'watermark.png';

        // Delete Existing Old File Backup
        if (file_exists($this->_origImage)) {
            unlink($this->_origImage);
        }

        // Copy Old File to origFile As Backup
        copy($this->_image, $this->_origImage);

    }

    /**
     * Sets the path to test files
     *
     * @return void
     */
    public function tearDown()
    {
        // Copy Backup to OldFile
        copy($this->_origImage, $this->_image);

        // Delete old File Backup
        if (file_exists($this->_origImage)) {
            unlink($this->_origImage);
        }
    }




    /**
     * Test full array parameter filter
     *
     * @return void
     */
    public function testConstructFullOptionsArray()
    {
        $filter = new Watermark(array(
            'watermark_image_path' => $this->_watermarkImage,
            'position_x' => Watermark::POS_X_CENTER,
            'position_y' => Watermark::POS_Y_CENTER,
            'offset_x' => 20,
            'offset_y' => -10,
        ));

        $this->assertEquals(array(
            'watermark_image_path' => $this->_watermarkImage,
            'position_x' => Watermark::POS_X_CENTER,
            'position_y' => Watermark::POS_Y_CENTER,
            'offset_x' => 20,
            'offset_y' => -10,
        ), $filter->getOptions()
        );
        $this->assertEquals($this->_image, $filter->filter($this->_image));
    }

    /**
     * Ensure that getPositionX() return expected default value
     */
    public function testGetPositionX()
    {
        $filter = new Watermark();
        $this->assertEquals(Watermark::POS_X_RIGHT, $filter->getPositionX());
    }

    /**
     * Ensure that getPositionY() return expected default value
     */
    public function testGetPositionY()
    {
        $filter = new Watermark();
        $this->assertEquals(Watermark::POS_Y_BOTTOM, $filter->getPositionY());
    }

    /**
     * Ensure that getOffsetX() return expected default value
     */
    public function testGetOffsetX()
    {
        $filter = new Watermark();
        $this->assertEquals(0, $filter->getOffsetX());
    }

    /**
     * Ensure that getOffsetY() return expected default value
     */
    public function testGetOffsetY()
    {
        $filter = new Watermark();
        $this->assertEquals(0, $filter->getOffsetY());
    }

    /**
     * Ensure that setWatermarkImagePath() follows expected behavior
     */
    public function testSetWatermarkImagePath()
    {
        $filter = new Watermark();
        $filter->setWaterMarkImagePath($this->_watermarkImage);

        $this->assertEquals($this->_watermarkImage, $filter->getWaterMarkImagePath());
    }

    /**
     * Ensure that setWatermarkImagePath throws Excpetion
     */
    public function testThrowsExceptionWithWrongWatermark()
    {
        $this->setExpectedException('DomainException', 'does not exists.');
        $filter = new Watermark();
        $filter->setWaterMarkImagePath('wrongpath');
    }

    /**
     * Ensure that filter() throw Exception
     */
    public function testThrowsExceptionWithWrongImage()
    {
        $filter = new Watermark(array('watermark_image_path' => $this->_watermarkImage));
        $this->setExpectedException('RuntimeException', 'does not exist.');
        $this->assertEquals('falseimage', $filter('falseimage'));
    }


    /**
     * Ensure that filter throws exception of watermark is out of image
     */
    public function testThrowsExceptionWatermarkOutOfImage()
    {
        $this->setExpectedException('OutOfRangeException', 'out of image');
        $filter = new Watermark(array(
            'watermark_image_path' => $this->_watermarkImage,
            'position_x' => Watermark::POS_X_RIGHT,
            'position_y' => Watermark::POS_Y_BOTTOM,
            'offset_x' => 20,
            'offset_y' => 20
        ));
        $filter($this->_image);
    }


    public function testThrowsExceptionWithWrongXPosition()
    {
        $filter = new Watermark();

        $this->setExpectedException('InvalidArgumentException', 'is not a defined x position');
        $filter->setPositionX('invalid x');
    }

    public function testThrowExceptionWithWrongYPosition()
    {
        $filter = new Watermark();

        $this->setExpectedException('InvalidArgumentException', 'is not a defined y position');
        $filter->setPositionY('invalid y');
    }

    public function testThrowsExceptionWithWrongXOffset()
    {
        $filter = new Watermark();

        $this->setExpectedException('InvalidArgumentException');
        $filter->setOffsetX('invalid int');
    }

    public function testThrowsExceptionWithWrongYOffset()
    {
        $filter = new Watermark();

        $this->setExpectedException('InvalidArgumentException');
        $filter->setOffsetY('invalid int');
    }

    public function testSetOffsetX()
    {
        $expected = array(Watermark::POS_X_LEFT, Watermark::POS_X_CENTER, Watermark::POS_X_LEFT);
        $filter   = new Watermark();

        foreach($expected as $positionX) {
            $filter->setPositionX($positionX);
            $this->assertEquals($positionX, $filter->getPositionX());
        }
    }

    public function testSetOffsetY()
    {
        $expected = array(Watermark::POS_Y_BOTTOM, Watermark::POS_Y_CENTER, Watermark::POS_Y_TOP);
        $filter   = new Watermark();

        foreach($expected as $positionY) {
            $filter->setPositionY($positionY);
            $this->assertEquals($positionY, $filter->getPositionY());
        }
    }


    public function testWithFilesArray()
    {
        $filter = new Watermark(array('watermark_image_path' => $this->_watermarkImage));
        $this->assertEquals(array(
            'watermark_image_path' => $this->_watermarkImage,
            'position_x' => $filter::POS_X_RIGHT,
            'position_y' => $filter::POS_Y_BOTTOM,
            'offset_x' => 0,
            'offset_y' => 0
        ), $filter->getOptions());

        $this->assertEquals(
            array('tmp_name' => $this->_image),
            $filter(array('tmp_name' => $this->_image))
        );
    }
}
