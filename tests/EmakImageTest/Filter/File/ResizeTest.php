<?php

namespace EmakImageTest\Filter\File;

use EmakImage\Filter\File\Resize;

/**
 *
 * @author   Sven Friedemann <sven@ertellbar.de>
 * @licence  MIT
 */
class ResizeTest extends \PHPUnit_Framework_TestCase {

    protected $_filesPath;

    protected $_origImage;

    protected $_image;



    /**
     * @var Resize
     */
    protected $_filter;

    public function setUp()
    {
        $this->_filter = new Resize();
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

    public function testGetWidth()
    {
        $this->assertEquals(200, $this->_filter->getWidth());
    }

    public function testSetWidth()
    {
        $expected = 123;
        $this->_filter->setWidth($expected);
        $this->assertEquals($expected, $this->_filter->getWidth());
    }

    public function testGetHeight()
    {
        $this->assertEquals(200, $this->_filter->getHeight());
    }

    public function testSetHeight()
    {
        $expected = 123;
        $this->_filter->setHeight($expected);
        $this->assertEquals($expected, $this->_filter->getHeight());
    }

    public function testConstructorWithFullArray()
    {
        $filter = new Resize(array(
            'width' => 123,
            'height' => 456
        ));

        $this->assertEquals(array(
            'width' => 123,
            'height' => 456
        ), $filter->getOptions());
    }

    public function testFilterReturnImage()
    {
        $this->assertSame($this->_image, $this->_filter->filter($this->_image));
    }

    public function testFilterThrowExceptionWrongValue()
    {
        $this->setExpectedException('RuntimeException');
        $this->assertEquals('wrong-image', $this->_filter->filter('wrong-image'));
    }

    public function testFilterWithFileArray()
    {
        $this->assertEquals(
            array('tmp_name' => $this->_image),
            $this->_filter->filter(array('tmp_name' => $this->_image))
        );
    }



}