Emak Image
==========

Created by Sven Friedemann

Introduction
------------

Image is a Zend Framework 2 Module to manipulate images.
It uses the high configurable Zend Framework Filter system to transform and manipulate images.

Installation
------------

Add "emak\image" to your composer.json file and update your dependencies.
Enable "Image" in your ```application.config.php```.

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

All filters are available via the filter manager.
 * File\Filter\Image\Thumbnail => ```Image\Filter\File\Thumbnail```
 * File\Filter\Image\Resize => ```Image\Filter\File\Resize```










