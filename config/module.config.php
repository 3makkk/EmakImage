<?php
return array(
    'filters' => array(
        'invokables' => array(
            'File\Image\Thumbnail' => 'EmakImage\Filter\File\Thumbnail',
            'File\Image\Resize' => 'EmakImage\Filter\File\Resize',
            'File\Image\Watermark' => 'EmakImage\Filter\File\Watermark'
        )
    )
);
