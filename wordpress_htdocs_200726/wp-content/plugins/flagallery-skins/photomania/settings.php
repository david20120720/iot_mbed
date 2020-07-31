<?php
$default_options = array(
    'base_gallery_width'      => '800',
    'base_gallery_height'     => '500',
    'gallery_min_height'      => '230',
    'scale_mode'              => 'fit',
    'initial_slide'           => '0',
    'slideshow_autoplay'      => '0',
    'slideshow_delay'         => '7000',
    'gallery_focus'           => '0',
    'gallery_maximized'       => '0',
    'gallery_focus_maximized' => '0',
    'keyboard_help'           => '1',
    'show_download_button'    => '1',
    'show_link_button'        => '1',
    'show_description'        => '1',
    'show_share_button'       => '1',
    'show_like_button'        => '1',
    'link_color'              => '#0099e5',
    'link_color_hover'        => '#02adea',
    'download_button_text'    => __('Download', 'flash-album-gallery'),
    'link_button_text'        => __('Open Link', 'flash-album-gallery'),
    'description_title'       => __('Description', 'flash-album-gallery'),
    'customCSS'               => ''
);
$options_tree    = array(
    array(
        'label'  => __('Common Settings', 'flash-album-gallery'),
        'fields' => array(
            'base_gallery_width'   => array(
                'label' => __('Base Width', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="1"',
                'text'  => '',
            ),
            'base_gallery_height'  => array(
                'label' => __('Base Height', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="1"',
                'text'  => __('Slider will autocalculate the ratio based on these values', 'flash-album-gallery')
            ),
            'gallery_min_height'   => array(
                'label' => __('Minimal Height', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="230"',
                'text'  => '',
            ),
            'gallery_maximized'    => array(
                'label' => __('Auto Height for Each Slide', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => __('Change slider height on change slide to best fit image in it', 'flash-album-gallery')
            ),
            'scale_mode'           => array(
                'label'   => __('Image Scale Mode', 'flash-album-gallery'),
                'tag'     => 'select',
                'attr'    => '',
                'text'    => __('Default value: Fit. Note \'Fill\' - can work inproperly on IE browser', 'flash-album-gallery'),
                'choices' => array(
                    array(
                        'label' => __('Fit', 'flash-album-gallery'),
                        'value' => 'fit'
                    ),
                    array(
                        'label' => __('Fill', 'flash-album-gallery'),
                        'value' => 'fill'
                    )
                )
            ),
            'initial_slide'        => array(
                'label' => __('Initial Slide', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="0"',
                'text'  => '',
            ),
            'slideshow_autoplay'   => array(
                'label' => __('Autoplay On Load', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => __('Start slideshow automatically on gallery load', 'flash-album-gallery')
            ),
            'slideshow_delay'      => array(
                'label' => __('Slideshow Delay', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="1000"',
                'text'  => __('Delay between change slides in miliseconds', 'flash-album-gallery')
            ),
            'show_download_button' => array(
                'label' => __('Show Download Button', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => 'data-watch="change"',
                'text'  => __('Download original file.', 'flash-album-gallery')
            ),
            'show_link_button'     => array(
                'label' => __('Show Link Button', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => 'data-watch="change"',
                'text'  => __('Uses link field from the item', 'flash-album-gallery')
            ),
            'show_description'     => array(
                'label' => __('Show Slide Description', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => 'data-watch="change"',
                'text'  => '',
            ),
            'show_share_button'    => array(
                'label' => __('Show Share Button', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => '',
            ),
            'show_like_button'     => array(
                'label' => __('Show Like Button', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => '',
            )
        )
    ),
    array(
        'label'  => __('Colors', 'flash-album-gallery'),
        'fields' => array(
            'link_color'       => array(
                'label' => __('Links and Buttons Color', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text" data-type="color"',
                'text'  => '',
            ),
            'link_color_hover' => array(
                'label' => __('Links and Buttons Color on Hover', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text" data-type="color"',
                'text'  => '',
            )
        )
    ),
    array(
        'label'  => __('Translate Strings', 'flash-album-gallery'),
        'fields' => array(
            'download_button_text' => array(
                'label' => __('Download Button Name', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text"',
                'text'  => '',
            ),
            'link_button_text'     => array(
                'label' => __('Link Button Name', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text"',
                'text'  => '',
            ),
            'description_title'    => array(
                'label' => __('Slide Description Title', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text"',
                'text'  => '',
            ),
        )
    ),
    array(
        'label'  => __('Advanced Settings', 'flash-album-gallery'),
        'fields' => array(
            'gallery_focus'           => array(
                'label' => __('Full Window Mode on Start', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => '',
            ),
            'gallery_focus_maximized' => array(
                'label' => __('Maximized Full Window Mode', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => '',
            ),
            'keyboard_help'           => array(
                'label' => __('Show Keyboard Help', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => '',
            ),
            'customCSS'               => array(
                'label' => __('Custom CSS', 'flash-album-gallery'),
                'tag'   => 'textarea',
                'attr'  => 'cols="20" rows="10"',
                'text'  => __('You can enter custom style rules into this box if you\'d like. IE: <i>a{color: red !important;}</i><br />This is an advanced option! This is not recommended for users not fluent in CSS... but if you do know CSS, anything you add here will override the default styles', 'flash-album-gallery')
            )
        )
    )
);
