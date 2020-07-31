<?php
$default_options = array(
    'maxheight'               => '0',
    'thumbCols'               => '0',
    'thumbRows'               => '0',
    'thumbsNavigation'        => 'scroll',
    'bgColor'                 => '#ffffff',
    'bgAlpha'                 => '0',
    'thumbWidth'              => '200',
    'thumbHeight'             => '180',
    'thumbWidthMobile'        => '150',
    'thumbHeightMobile'       => '135',
    'thumbsSpacing'           => '8',
    'thumbsVerticalPadding'   => '4',
    'thumbsHorizontalPadding' => '4',
    'thumbsAlign'             => 'center',
    'thumbScale'              => '1',
    'thumbBG'                 => '#ffffff',
    'thumbAlpha'              => '90',
    'thumbAlphaHover'         => '100',
    'thumbBorderSize'         => '1',
    'thumbBorderColor'        => '#cccccc',
    'thumbPadding'            => '2',
    'thumbsInfo'              => 'label',
    'labelOnHover'            => '1',
    'labelTextColor'          => '#ffffff',
    'labelLinkColor'          => '#e7e179',
    'label8TextColor'         => '#0b0b0b',
    'label8LinkColor'         => '#3695E7',
    'tooltipTextColor'        => '#0b0b0b',
    'tooltipBgColor'          => '#ffffff',
    'tooltipStrokeColor'      => '#000000',
    'lightboxControlsColor'   => '#ffffff',
    'lightboxTitleColor'      => '#f3f3f3',
    'lightboxTextColor'       => '#f3f3f3',
    'lightboxBGColor'         => '#0b0b0b',
    'lightboxBGAlpha'         => '80',
    'socialShareEnabled'      => '1',
    'deepLinks'               => '1',
    'sidebarBGColor'          => '#ffffff',
    'lightbox800HideArrows'   => '0',
    'viewsEnabled'            => '1',
    'likesEnabled'            => '1',
    'thumb2link'              => '0',
    'show_title'              => '1',
    'initRPdelay'             => '200',
    'customCSS'               => ''
);
$options_tree    = array(
    array(
        'label'  => __('Common Settings', 'flash-album-gallery'),
        'fields' => array(
            'maxheight'            => array(
                'label' => __('Max-Height', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="0" data-watch="change"',
                'text'  => __('Set the maximum height of the gallery. Leave 0 to disable max-height. If value is 0, then Thumbnail Rows value ignored and Thumbnail Columns is a max value', 'flash-album-gallery')
            ),
            'thumbCols'            => array(
                'label' => __('Thumbnail Columns', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="0"',
                'text'  => __('Number of Columns (number, 0 = auto). Set the number of columns for the grid. If value is 0, then number of columns will be relative to content width or relative to Thumbnail Rows (if rows not auto). This will be ignored if Height value is 0', 'flash-album-gallery')
            ),
            'thumbRows'            => array(
                'label' => __('Thumbnail Rows', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="0"',
                'text'  => __('Number of Lines (number, 0 = auto). Default value: 0. Set the number of lines for the grid. This will be ignored if Thumbnail Columns value is not 0 or if Height value is 0', 'flash-album-gallery')
            ),
            'thumbsNavigation'     => array(
                'label'   => __('Grid Navigation', 'flash-album-gallery'),
                'tag'     => 'select',
                'attr'    => 'data-maxheight="!=:0"',
                'text'    => __('Set how you navigate through the thumbnails. Ignore this option if Height value is 0', 'flash-album-gallery'),
                'choices' => array(
                    array(
                        'label' => __('Mouse Move', 'flash-album-gallery'),
                        'value' => 'mouse'
                    ),
                    array(
                        'label' => __('Scroll Bars', 'flash-album-gallery'),
                        'value' => 'scroll'
                    )
                )

            ),
            'bgColor'              => array(
                'label' => __('Background Color', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text" data-type="color"',
                'text'  => __('Set gallery background color', 'flash-album-gallery')
            ),
            'bgAlpha'              => array(
                'label' => __('Background Alpha', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="0" max="100" step="5"',
                'text'  => __('Set gallery background alpha opacity', 'flash-album-gallery')
            ),
            'thumb2link'           => array(
                'label' => __('Thumbnail to Link', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => __('If item have Link, then open Link instead of lightbox. Note: Link also will be available via item Title on the thumbnail\'s label and in the lightbox', 'flash-album-gallery')
            ),
            'deepLinks'            => array(
                'label' => __('Deep Links', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => __('Change URL hash in the address bar for each big image', 'flash-album-gallery')
            ),
            'viewsEnabled'         => array(
                'label' => __('Views Counter', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => __('Show Views counter?', 'flash-album-gallery')
            ),
            'likesEnabled'         => array(
                'label' => __('Like Button', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => __('Enable Like Button?', 'flash-album-gallery')
            ),
            'socialShareEnabled'   => array(
                'label' => __('Show Share Button', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => 'data-watch="change"',
                'text'  => ''
            ),
            'show_title'           => array(
                'label' => __('Show Title in Lightbox', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => ''
            ),
        )
    ),
    array(
        'label'  => __('Thumb Grid General', 'flash-album-gallery'),
        'fields' => array(
            'thumbWidth'              => array(
                'label' => __('Thumbnail Width', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="10" max="400"',
                'text'  => ''
            ),
            'thumbHeight'             => array(
                'label' => __('Thumbnail Height', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="10" max="400"',
                'text'  => ''
            ),
            'thumbWidthMobile'        => array(
                'label' => __('Thumbnail Width Mobile', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="10" max="400"',
                'text'  => __('Set width for thumbnail if window width is less than 640px', 'flash-album-gallery')
            ),
            'thumbHeightMobile'       => array(
                'label' => __('Thumbnail Height Mobile', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="10" max="400"',
                'text'  => __('Set height for thumbnail if window width is less than 640px', 'flash-album-gallery')
            ),
            'thumbsSpacing'           => array(
                'label' => __('Thumbnails Spacing', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="0"',
                'text'  => __('Set the space between thumbnails', 'flash-album-gallery')
            ),
            'thumbsVerticalPadding'   => array(
                'label' => __('Grid Vertical Padding', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="0"',
                'text'  => __('Set the vertical padding for the thumbnails grid', 'flash-album-gallery')
            ),
            'thumbsHorizontalPadding' => array(
                'label' => __('Grid Horizontal Padding', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="0"',
                'text'  => __('Set the horizontal padding for the thumbnails grid', 'flash-album-gallery')
            ),
            'thumbsAlign'             => array(
                'label'   => __('Thumbnails Align', 'flash-album-gallery'),
                'tag'     => 'select',
                'attr'    => '',
                'text'    => __('Align thumbnails grid in container. Applied only if grid width less than gallery width', 'flash-album-gallery'),
                'choices' => array(
                    array(
                        'label' => __('Left', 'flash-album-gallery'),
                        'value' => 'left'
                    ),
                    array(
                        'label' => __('Center', 'flash-album-gallery'),
                        'value' => 'center'
                    ),
                    array(
                        'label' => __('Right', 'flash-album-gallery'),
                        'value' => 'right'
                    )
                )
            )
        )
    ),
    array(
        'label'  => __('Thumbnail Style', 'flash-album-gallery'),
        'fields' => array(
            'thumbScale'       => array(
                'label' => __('Thumbnail Scale on mouseover', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => ''
            ),
            'thumbBG'          => array(
                'label' => __('Thumbnail Container Background', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text" data-type="color"',
                'text'  => __('Set empty for transparent background', 'flash-album-gallery')
            ),
            'thumbAlpha'       => array(
                'label' => __('Thumbnail Alpha', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="0" max="100" step="5"',
                'text'  => __('Set the transparency of a thumbnail', 'flash-album-gallery')
            ),
            'thumbAlphaHover'  => array(
                'label' => __('Thumbnail Alpha Hover', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="0" max="100" step="5"',
                'text'  => __('Set the transparancy of a thumbnail when hover', 'flash-album-gallery')
            ),
            'thumbBorderSize'  => array(
                'label' => __('Thumbnail Border Size', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="0"',
                'text'  => __('Set border size for thumbnail', 'flash-album-gallery')
            ),
            'thumbBorderColor' => array(
                'label' => __('Thumbnail Border Color', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text" data-type="color"',
                'text'  => __('Set the color of a thumbnail\'s border', 'flash-album-gallery')
            ),
            'thumbPadding'     => array(
                'label' => __('Thumbnail Padding', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="0"',
                'text'  => __('Set padding for the thumbnail', 'flash-album-gallery')
            )
        )
    ),
    array(
        'label'  => __('Thumbnails Title', 'flash-album-gallery'),
        'fields' => array(
            'thumbsInfo'         => array(
                'label'   => __('Display Thumbnails Title', 'flash-album-gallery'),
                'tag'     => 'select',
                'attr'    => 'data-watch="change"',
                'text'    => __('Default value: Label. Display a small info text on the thumbnails, a tooltip or a label.', 'flash-album-gallery'),
                'choices' => array(
                    array(
                        'label' => __('Label Over Image', 'flash-album-gallery'),
                        'value' => 'label'
                    ),
                    array(
                        'label' => __('Label Under Image', 'flash-album-gallery'),
                        'value' => 'label_bottom'
                    ),
                    array(
                        'label' => __('Tooltip', 'flash-album-gallery'),
                        'value' => 'tooltip'
                    ),
                    array(
                        'label' => __('None', 'flash-album-gallery'),
                        'value' => 'none'
                    )
                )

            ),
            'labelOnHover'       => array(
                'label' => __('Show Label on Mouseover', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => 'data-thumbsinfo="is:label:0"',
                'text'  => __('Uncheck to show thumbnail\'s label all time', 'flash-album-gallery')
            ),
            'labelTextColor'     => array(
                'label' => __('Label-Over Text Color', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text" data-type="color" data-thumbsinfo="is:label"',
                'text'  => __('Set Label-Over text color', 'flash-album-gallery')
            ),
            'labelLinkColor'     => array(
                'label' => __('Label-Over Link Color', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text" data-type="color" data-thumbsinfo="is:label"',
                'text'  => __('Set Label-Over link color', 'flash-album-gallery')
            ),
            'label8TextColor'    => array(
                'label' => __('Label Text Color', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text" data-type="color" data-thumbsinfo="is:label_bottom"',
                'text'  => __('Set Label text color', 'flash-album-gallery')
            ),
            'label8LinkColor'    => array(
                'label' => __('Label Link Color', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text" data-type="color" data-thumbsinfo="is:label_bottom"',
                'text'  => __('Set Label-Bottom link color', 'flash-album-gallery')
            ),
            'tooltipTextColor'   => array(
                'label' => __('Tooltip Text Color', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text" data-type="color" data-thumbsinfo="is:tooltip"',
                'text'  => __('Set Tooltip text color', 'flash-album-gallery')
            ),
            'tooltipBgColor'     => array(
                'label' => __('Tooltip Background Color', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text" data-type="color" data-thumbsinfo="is:tooltip"',
                'text'  => __('Set tooltip background color. Ignore this if Display Thumbnails Title value is not Tooltip', 'flash-album-gallery')
            ),
            'tooltipStrokeColor' => array(
                'label' => __('Tooltip Stroke Color', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text" data-type="color" data-thumbsinfo="is:tooltip"',
                'text'  => __('Set tooltip stroke color. Ignore this if Display Thumbnails Title value is not Tooltip', 'flash-album-gallery')
            )
        )
    ),
    array(
        'label'  => __('Lightbox Settings', 'flash-album-gallery'),
        'fields' => array(
            'lightboxControlsColor' => array(
                'label' => __('Lightbox Controls / Buttons Color', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text" data-type="color"',
                'text'  => __('Set the color for lightbox control buttons', 'flash-album-gallery')
            ),
            'lightboxTitleColor'    => array(
                'label' => __('Lightbox Image Title Color', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text" data-type="color"',
                'text'  => __('Set the text color for image title', 'flash-album-gallery')
            ),
            'lightboxTextColor'     => array(
                'label' => __('Lightbox Image Description Color', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text" data-type="color"',
                'text'  => __('Set the text color for image caption', 'flash-album-gallery')
            ),
            'lightboxBGColor'       => array(
                'label' => __('Lightbox Window Color', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="text" data-type="color"',
                'text'  => __('Set the background color for the lightbox window', 'flash-album-gallery')
            ),
            'lightboxBGAlpha'       => array(
                'label' => __('Lightbox Window Alpha', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="0" max="100" step="5"',
                'text'  => __('Set the transparancy for the lightbox window', 'flash-album-gallery')
            ),
            'lightbox800HideArrows' => array(
                'label' => __('Hide Arrows when small window', 'flash-album-gallery'),
                'tag'   => 'checkbox',
                'attr'  => '',
                'text'  => __('Hide Arrows if window width less than 800px', 'flash-album-gallery')
            )
        )
    ),
    array(
        'label'  => __('Advanced Settings', 'flash-album-gallery'),
        'fields' => array(
            'initRPdelay' => array(
                'label' => __('Delay for Thumbnail Positioning', 'flash-album-gallery'),
                'tag'   => 'input',
                'attr'  => 'type="number" min="0" max="5000" step="1"',
                'text'  => __('Set delay in miliseconds. Set more if gallery render wrong grid.', 'flash-album-gallery')
            ),
            'customCSS'   => array(
                'label' => __('Custom CSS', 'flash-album-gallery'),
                'tag'   => 'textarea',
                'attr'  => 'cols="20" rows="10"',
                'text'  => __('You can enter custom style rules into this box if you\'d like. IE: <i>a{color: red !important;}</i><br />This is an advanced option! This is not recommended for users not fluent in CSS... but if you do know CSS, anything you add here will override the default styles', 'flash-album-gallery')
            )
        )
    )
);
