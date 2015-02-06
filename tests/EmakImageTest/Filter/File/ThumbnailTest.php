<?php

namespace EmakImageTest\Filter\File;

use EmakImage\Filter\File\Thumbnail;
use Imagine\Image\ImageInterface;

/**
 *
 * @author   Sven Friedemann <sven@ertellbar.de>
 * @licence  MIT
 */
class ThumbnailTest extends \PHPUnit_Framework_TestCase {

    protected $_filesPath;

    protected $_origImage;

    protected $_image;


    /**
     * @var Thumbnail
     */
    protected $_filter;

    public function setUp()
    {
        $this->_filter = new Thumbnail();
        $this->_filesPath  = dirname(__DIR__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR;
        $this->_origImage   = $this->_filesPath . 'original.file';
        $this->_image    = $this->_filesPath . 'plant.jpg';

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


    public function testConstructorFullOptionsArray() {
        $filter = new Thumbnail(array(
            'width' => 50,
            'height' => 100,
            'mode' => ImageInterface::THUMBNAIL_INSET
        ));

        $this->assertEquals(array(
            'width' => 50,
            'height' => 100,
            'mode' => ImageInterface::THUMBNAIL_INSET
        ), $filter->getOptions());
    }

    /**
     * Ensure that getModle() returns expected default value
     * @return void
     */
    public function testGetMode()
    {
        $this->assertEquals(ImageInterface::THUMBNAIL_OUTBOUND, $this->_filter->getMode());
    }

    /**
     * Ensure that setMode() follows expected behavior
     */
    public function testSetMode()
    {
        $this->_filter->setMode(ImageInterface::THUMBNAIL_INSET);
        $this->assertEquals(ImageInterface::THUMBNAIL_INSET, $this->_filter->getMode());

        $this->setExpectedException('InvalidArgumentException', 'mode');
        $this->_filter->setMode('invalid_mode');
    }

    /**
     * Ensure that getWidth() return expected default value
     */
    public function testGetWith() {
        $this->assertEquals('200', $this->_filter->getWidth());
    }

    /**
     * Ensure that setWidth() follows expected behavior
     */
    public function testSetWith()
    {
        $this->_filter->setWidth('100');
        $this->assertEquals('100', $this->_filter->getWidth());
    }

    /**
     * Ensure that getHeight() return expected default value
     */
    public function testGetHeight() {
        $this->assertEquals('200', $this->_filter->getHeight());
    }

    /**
     * Ensure that setWidth() follows expected behavior
     */
    public function testSetHeight()
    {
        $this->_filter->setHeight('100');
        $this->assertEquals('100', $this->_filter->getHeight());
    }

    public function testFilterShouldReturnImage()
    {
        $result = $this->_filter->filter($this->_image);
        $this->assertSame($this->_image, $result);
    }

    public function testFilterWithFileArrayShouldReturnImage()
    {
        $result = $this->_filter->filter($this->_image);
        $this->assertSame(array('tmp_name' => $this->_image), array('tmp_name' => $result));
    }
}