<?php
$default_options = array(
    'maxwidth'      => '0',
    'autoplay'      => '0',
    'loop'          => '1',
    'buttonText'    => __('Download', 'flash-album-gallery'),
    'downloadTrack' => '0',
    'tracksToShow'  => '5',
    'moreText'      => __('View More...', 'flash-album-gallery'),
    'customCSS'     => ''
);
$options_tree    = array(
    array(
        'label'  => __('Common Settings', 'flash-album-gallery'),
        'fields' => array(
            'maxwidth'      => array(
                'label' => __('Max-Width', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="0"',
                'text'  => __('Set the maximum width of the player. Leave 0 to disable max-width.', 'flash-album-gallery')
            ),
            'autoplay'      => array(
                'label' => __('Autoplay', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => ''
            ),
            'loop'          => array(
                'label' => __('Loop Playback', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => ''
            ),
            'downloadTrack' => array(
                'label' => __('Download Button', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => ''
            ),
            'buttonText'    => array(
                'label' => __('Download Button Text', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text"',
                'text'  => ''
            ),
            'tracksToShow'  => array(
                'label' => __('# of Tracks to Show', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="-1"',
                'text'  => __('Set how many tracks to see on page load. Others be hided and More button shows.', 'flash-album-gallery')
            ),
            'moreText'      => array(
                'label' => __('More Button Text', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text"',
                'text'  => __('Button to show more tracks.', 'flash-album-gallery')
            )
        )
    ),
    array(
        'label'  => __('Advanced Settings', 'flash-album-gallery'),
        'fields' => array(
            'customCSS' => array(
                'label' => __('Custom CSS', 'flash-album-gallery'),
                'tag'   => 'textarea',
                'attr'  => 'cols="20" rows="10"',
                'text'  => __('You can enter custom style rules into this box if you\'d like. IE: <i>a{color: red !important;}</i><br />This is an advanced option! This is not recommended for users not fluent in CSS... but if you do know CSS, anything you add here will override the default styles', 'flash-album-gallery')
            )
            /*,
            'loveLink' => array(
                'label'  => __('Display LoveLink?', 'flash-album-gallery'),
                'tag' => 'checkbox',
                'attr' => '',
                'text' => __('Selecting "Yes" will show the lovelink icon (codeasily.com) somewhere on the gallery', 'flash-album-gallery')
            )*/
        )
    )
);
