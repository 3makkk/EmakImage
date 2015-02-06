Emak Image
==========

Created by Sven Friedemann

[![Build Status](https://travis-ci.org/3makkk/EmakImage.svg)](https://travis-ci.org/3makkk/EmakImage)

Introduction
------------

Image is a Zend Framework 2 Module to manipulate images via filter.
It uses the high configurable Zend Framework Filter system to transform and manipulate images and based on Imagine.

Installation
------------

Add "3makkk\emak-image" to your composer.json file and update your dependencies.
Enable "EmakImage" in your ```application.config.php```.

Or simply clone it into your vendor directory.

Filters
-------

Each Filter is a implementation of ```Zend\Filter\FilterInterface``` and is available in the filter manager.



**Thumbnail**:
Generate a thumbnail from current image.

The following options are Supported for ```Image\Filter\File\Thumbnail```

* ```width```: Width of thumbnail.
* ```height```: Height of thumbnail.
* ```mode```: The mode specify the behavior of the crop/resize mechanism. It can be inset or outbound.



**Resize**:
Resize the current image. (not proportional)

The following options are supported for ```Image\Filter\File\Resize```

* ```width```: Width of resized image.
* ```height```: Height of resized image.



**Watermark**:
Watermark current image

The following options are supported for ```EmakImage\Filter\File\Watermark```
* ```watermark_image_path```: Path to Watermark image.
* ```position_x```: position of watermark on X axis  (```Watermark:POSITION_X_LEFT``` | ```Watermark::POSITION_X_CENTER``` | ```Watermark::POSITION_X_RIGHT```)
* ```position_y```: position of watermark on Y axis (```Watermark:POSITION_Y_TOP``` | ```Watermark::POSITION_Y_CENTER``` | ```Watermark::POSITION_Y_BOTTOM```)
* ```offset_x```: Watermark offset on X axis relative to position.
* ```offset_y```: Watermark offset on Y axis relative to position.

All filters are available via the filter manager.
 * File\Filter\Image\Thumbnail => ```EmakImage\Filter\File\Thumbnail```
 * File\Filter\Image\Resize => ```EmakImage\Filter\File\Resize```
 * File\Filter\Image\Watermark => ```EmakImage\Filter\File\Watermark```












