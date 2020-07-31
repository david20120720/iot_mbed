<?php
$default_options = array(
    'theme'        => 'default',
    'effect'       => 'random',
    'slices'       => '15',
    'animSpeed'    => '500',
    'pauseTime'    => '5000',
    'startSlide'   => '0',
    'pauseOnHover' => '1',
    'directionNav' => '1',
    'controlNav'   => '1',
    'linkTarget'   => '0',
    'randomStart'  => '0',
    'customCSS'    => '',
);
$options_tree    = array(
    array(
        'label'  => __('Settings', 'flash-album-gallery'),
        'fields' => array(
            'theme'             => array(
                'label'   => __('Choose Theme', 'flash-album-gallery'),
                'tag'     => 'select',
                'attr'    => '',
                'text'    => '',
                'choices' => array(
                    array(
                        'label' => __('Default', 'flash-album-gallery'),
                        'value' => 'default',
                    ),
                    array(
                        'label' => __('Light', 'flash-album-gallery'),
                        'value' => 'light',
                    ),
                    array(
                        'label' => __('Dark', 'flash-album-gallery'),
                        'value' => 'dark',
                    ),
                    array(
                        'label' => __('Bar', 'flash-album-gallery'),
                        'value' => 'bar',
                    ),
                ),
            ),
            'effect'             => array(
                'label'   => __('Choose Effect', 'flash-album-gallery'),
                'tag'     => 'select',
                'attr'    => '',
                'text'    => '',
                'choices' => array(
                    array(
                        'label' => __('Random', 'flash-album-gallery'),
                        'value' => 'random',
                    ),
                    array(
                        'label' => __('Slice Down', 'flash-album-gallery'),
                        'value' => 'sliceDown',
                    ),
                    array(
                        'label' => __('Slice Down Left', 'flash-album-gallery'),
                        'value' => 'sliceDownLeft',
                    ),
                    array(
                        'label' => __('Slice Up', 'flash-album-gallery'),
                        'value' => 'sliceUp',
                    ),
                    array(
                        'label' => __('Slice Up Left', 'flash-album-gallery'),
                        'value' => 'sliceUpLeft',
                    ),
                    array(
                        'label' => __('Slice Up Down', 'flash-album-gallery'),
                        'value' => 'sliceUpDown',
                    ),
                    array(
                        'label' => __('Slice Up Down Left', 'flash-album-gallery'),
                        'value' => 'sliceUpDownLeft',
                    ),
                    array(
                        'label' => __('Fold', 'flash-album-gallery'),
                        'value' => 'fold',
                    ),
                    array(
                        'label' => __('Fade', 'flash-album-gallery'),
                        'value' => 'fade',
                    ),
                    array(
                        'label' => __('Slide In Right', 'flash-album-gallery'),
                        'value' => 'slideInRight',
                    ),
                    array(
                        'label' => __('Slide In Left', 'flash-album-gallery'),
                        'value' => 'slideInLeft',
                    ),
                    array(
                        'label' => __('Box Random', 'flash-album-gallery'),
                        'value' => 'boxRandom',
                    ),
                    array(
                        'label' => __('Box Rain', 'flash-album-gallery'),
                        'value' => 'boxRain',
                    ),
                    array(
                        'label' => __('Box Rain Reverse', 'flash-album-gallery'),
                        'value' => 'boxRainReverse',
                    ),
                    array(
                        'label' => __('Box Rain Grow', 'flash-album-gallery'),
                        'value' => 'boxRainGrow',
                    ),
                    array(
                        'label' => __('Box Rain Grow Reverse', 'flash-album-gallery'),
                        'value' => 'boxRainGrowReverse',
                    ),
                ),
            ),
            'slices'            => array(
                'label' => __('Number of Slices', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="0"',
                'text'  => '',
            ),
            'animSpeed'            => array(
                'label' => __('Animation Speed (ms)', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="0"',
                'text'  => '',
            ),
            'pauseTime'            => array(
                'label' => __('Delay between slides (ms)', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="0"',
                'text'  => '',
            ),
            'startSlide'            => array(
                'label' => __('Start Slide', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="0"',
                'text'  => '',
            ),
            'pauseOnHover'           => array(
                'label' => __('Pause slideshow on mouseover', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => '',
            ),
            'directionNav'            => array(
                'label' => __('Next & Prev navigation', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => '',
            ),
            'controlNav'         => array(
                'label' => __('Bottom navigation', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => '',
            ),
            'linkTarget'         => array(
                'label' => __('Open links in new window', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => '',
            ),
            'randomStart'   => array(
                'label' => __('Random Start', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => '',
            ),
        ),
    ),
    array(
        'label'  => __('Advanced Settings', 'flash-album-gallery'),
        'fields' => array(
            'customCSS'   => array(
                'label' => __('Custom CSS', 'flash-album-gallery'),
                'tag'   => 'textarea',
                'attr'  => 'cols="20" rows="10"',
                'text'  => __('You can enter custom style rules into this box if you\'d like. IE: <i>a{color: red !important;}</i><br />This is an advanced option! This is not recommended for users not fluent in CSS... but if you do know CSS, anything you add here will override the default styles', 'flash-album-gallery'),
            ),
        ),
    ),
);
