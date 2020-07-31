<?php
$default_options = array(
    //Splash Settings
    'splashType'=>'grid',
    'coverСolumns' => '3',
    'coverRecomendedWidth' => '250',
    'coverThumbSpacing' => '10',
    'coverHieghtRation' => '1',
    'coverHoverColor' => 'rgba(0,0,0,0.65)',
    'coverTitleEnable' => '1',
    'coverTitleFontSize' => '22',
    'coverTitleTextColor' => 'rgba(255,255,255,1)',
    'coverCollectoinAmount' => '1',
    //Slider Page
    'sliderPpointsNaviEnable' => '1',
    'sliderButtonsNaviEnable' => '1',
    'navigationColor' => 'rgba(0,0,0,1)',
    'navigationColorHover' => 'rgba(0,0,0,0.7)',
    'navigationIconColor' => 'rgba(255,255,255,1)',
    //Modal Window
    'modaBgColor' => 'rgba(0,0,0,0.9)',
    'modalInfoBoxBgColor' => 'rgba(255,255,255,1)',
    'modalInfoBoxTitleTextColor' => 'rgba(0,0,0,1)',
    'modalInfoBoxTextColor' => 'rgba(70,70,70,1)',
    'infoBarExifEnable' => '1',
    'infoBarCountersEnable' => '1',
    'infoBarDateInfoEnable' => '1',
    // Slider Page
    'copyR_Protection' => '1',
    'copyR_Alert' => 'Hello, this photo is mine!',
    'sliderScrollNavi' => '0',
    'sliderNextPrevAnimation' => 'animation',
    'sliderPreloaderColor' => '#ffffff',
    'sliderBgColor' => 'rgba(0,0,0,0.8)',
    'sliderHeaderFooterBgColor' => '#000000',
    'sliderNavigationColor' => 'rgba(0,0,0,1)',
    'sliderNavigationIconColor' => 'rgba(255,255,255,1)',
    'sliderNavigationColorOver' => 'rgba(255,255,255,1)',
    'sliderNavigationIconColorOver' => 'rgba(0,0,0,1)',
    'sliderItemTitleEnable' => '1',
    'sliderItemTitleFontSize' => '24',
    'sliderItemTitleTextColor' => '#ffffff',
    'sliderThumbBarEnable' => '1',
    'sliderThumbBarHoverColor' => '#ffffff',
    'sliderThumbSubMenuBackgroundColor' => 'rgba(0,0,0,1)',
    'sliderThumbSubMenuBackgroundColorOver' => 'rgba(255,255,255,1)',
    'sliderThumbSubMenuIconColor' => 'rgba(255,255,255,1)',
    'sliderThumbSubMenuIconHoverColor' => 'rgba(0,0,0,1)',
    'sliderPlayButton' => '1',
    'slideshowDelay' => '8',
    'slideshowProgressBarColor' => 'rgba(255,255,255,1)',
    'slideshowProgressBarBGColor' => 'rgba(255,255,255,0.6)',
    'sliderInfoEnable' => '1',
    'sliderZoomButton' => '1',
    'sliderItemDownload' => '1',
    'sliderSocialShareEnabled' => '1',
    'sliderLikesEnabled' => '1',
    'sliderFullScreen' => '1',
    // Custom CSS
    'customCSS' => ''
);
$options_tree = array(
    array(
        'label' => 'Splash page',
        'fields' => array(
            'splashType'           => array(
				'label'   => 'Album layout',
				'tag'     => 'select',
				'attr'    => 'data-watch="change"',
				'text'    => '',
				'choices' => array(
					array(
						'label' => 'Grid',
						'value' => 'grid'
					),
					array(
						'label' => 'Masonry',
						'value' => 'masonry'
					),
					array(
						'label' => 'Slider',
						'value' => 'slider'
					)
                    ),
                'premium' => true
            ),
            'coverСolumns' => array('label' => 'Thumbnail Columns',
				'tag' => 'input',
				'attr' => 'type="number" min="1" max="10"',
				'text' => 'Number of columns'
			),
            'coverRecomendedWidth' => array(
                'label' => 'Minimum Gallery Cover Width',
                'tag' => 'input',
                'attr' => 'type="number" min="100" max="900"',
                'text' => ''
            ),
            'coverHieghtRation' => array(
                'label' => 'Cover Size ratio',
                'tag' => 'input',
                'attr' => 'type="number" min="0.1" max="2" step="0.1"',
                'text' => 'Height / Width = Ratio. Value for Grid and Slider layout'
            ),
            'coverThumbSpacing' => array(
                'label' => 'Space between covers',
                'tag' => 'input',
                'attr' => 'type="number" min="0" max="30"',
                'text' => ''
            ),
            'coverHoverColor' => array(
                'label' => 'Cover Title/Description Background Color',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba"',
                'text' => 'For Grid/Masonry Types',
                'premium' => true
            ),
            'coverTitleEnable' => array(
                'label' => 'Show Gallery title',
                'tag' => 'checkbox',
                'attr' => 'data-watch="change"',
                'text' => ''
            ),
            'coverTitleFontSize' => array(
                'label' => 'Gallery Title - font size',
                'tag' => 'input',
                'attr' => 'type="number" min="14" max="36" step="1"',
                'text' => ''
            ),
            'coverTitleTextColor' => array(
                'label' => 'Collection Title - text color',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba"',
                'text' => ''
            ),
            'coverCollectoinAmount' => array(
                'label' => 'Show the number of items in the gallery',
                'tag' => 'checkbox',
                'attr' => 'data-watch="change"',
                'text' => ''
            ),
            'sliderPpointsNaviEnable' => array(
                'label' => 'Show Slider Points  Navi',
                'tag' => 'checkbox',
                'attr' => 'data-watch="change"',
                'text' => 'Only Slider Type',
                'premium' => true
            ),
            'sliderButtonsNaviEnable' => array(
                'label' => 'Show Slider Next/Prev. buttons',
                'tag' => 'checkbox',
                'attr' => 'data-watch="change"',
                'text' => 'Only Slider Type',
                'premium' => true
            ),
            'navigationColor' => array(
                'label' => 'Slider Navigation Color',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba"',
                'text' => 'Only Slider Type',
                'premium' => true
            ),
            'navigationColorHover' => array(
                'label' => 'Slider Navigation Hover Color',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba"',
                'text' => 'Only Slider Type',
                'premium' => true
            ),
            'navigationIconColor' => array(
                'label' => 'Slider Navigation Icon Color',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba"',
                'text' => 'Only Slider Type',
                'premium' => true
            ),
        )
    ),
    array(
        'label' => 'Modal Window Settings (Item Info Bar)',
        'fields' => array(
            'modaBgColor' => array(
                'label' => 'Overlap Color',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba"',
                'text' => ''
            ),
            'modalInfoBoxBgColor' => array(
                'label' => 'Info Bar Color',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba"',
                'text' => ''
            ),
            'modalInfoBoxTitleTextColor' => array(
                'label' => 'Info Bar Title text Color',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba"',
                'text' => ''
            ),
            'modalInfoBoxTextColor' => array(
                'label' => 'Info Bar Text Color',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba"',
                'text' => ''
            ),
            'infoBarExifEnable' => array(
                'label' => 'Show Exif Data',
                'tag' => 'checkbox',
                'attr' => 'data-watch="change"',
                'text' => '',
                'premium' => true
            ),
            'infoBarCountersEnable' => array(
                'label' => 'Show View/Likes/Comments',
                'tag' => 'checkbox',
                'attr' => 'data-watch="change"',
                'text' => ''
            ),
            'infoBarDateInfoEnable' => array(
                'label' => 'Show item date',
                'tag' => 'checkbox',
                'attr' => 'data-watch="change"',
                'text' => ''
            )
        )
    ),
    array(
        'label' => 'Lightbox Settings',
        'fields' => array(
            'copyR_Protection' => array(
                'label' => 'Enable Download Protection',
                'tag' => 'checkbox',
                'attr' => 'data-watch="change"',
                'text' => 'Disable right click to protect content from download',
                'premium' => true
            ),
            'copyR_Alert' => array(
                'label' => 'Copyright protection - Alert',
                'tag' => 'input',
                'attr' => 'type="text"',
                'text' => 'This message is displayed when a visitor clicks the right mouse button on a photo in a lightbox.',
                'premium' => true
            ),
            'sliderScrollNavi' => array('label' => 'Scroll to navigate (mouse wheel)',
				'tag' => 'checkbox',
				'attr' => '',
                'text' => 'Using this disable mouse wheel scaling!',
                'premium' => true
            ),
            'sliderNextPrevAnimation' => array(
				'label'   => 'Items Transition Type',
				'tag'     => 'select',
				'attr'    => 'data-watch="change"',
				'text'    => '',
				'choices' => array(
					array(
						'label' => 'Slipping',
						'value' => 'animation'
					),
					array(
						'label' => 'Fade-In',
						'value' => 'fade'
					),
                ),
                'premium' => true
			),
            'sliderPreloaderColor' => array(
                'label' => 'Preloader Color',
                'tag' => 'input',
                'attr' => 'type="text" data-type="color"',
                'text' => ''
            ),
            'sliderBgColor' => array(
                'label' => 'Background color',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba"',
                'text' => ''
            ),
            'sliderHeaderFooterBgColor' => array(
                'label' => 'Header & Footer background color',
                'tag' => 'input',
                'attr' => 'type="text" data-type="color"',
                'text' => 'Gradient color'
            ),
            'sliderNavigationColor' => array(
                'label' => 'Navigation button color',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba"',
                'text' => ''
            ),
            'sliderNavigationColorOver' => array(
                'label' => 'Navigation button color (over)',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba"',
                'text' => ''
            ),
            'sliderNavigationIconColor' => array(
                'label' => 'Navigation button Icons color',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba"',
                'text' => ''
            ),
            'sliderNavigationIconColorOver' => array(
                'label' => 'Navigation button Icons color (over)',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba"',
                'text' => ''
            ),
            'sliderItemTitleEnable' => array('label' => 'Show Items Title',
				'tag' => 'checkbox',
				'attr' => 'data-watch="change"',
				'text' => ''
			),
            'sliderItemTitleFontSize' => array(
                'label' => 'Item Title - font Size',
                'tag' => 'input',
                'attr' => 'type="number" min="11" max="34" step="1"',
                'text' => ''
            ),
            'sliderItemTitleTextColor' => array(
                'label' => 'Item Title text color',
                'tag' => 'input',
                'attr' => 'type="text" data-type="color"',
                'text' => ''
            ),
            'sliderThumbBarEnable' => array('label' => 'Show Items Thumbnails',
				'tag' => 'checkbox',
				'attr' => 'data-watch="change"',
                'text' => '',
                'premium' => true
			),
			'sliderThumbBarHoverColor' => array('label' => 'Thumbnails Border Color (select mode)',
				'tag' => 'input',
				'attr' => 'type="text" data-type="color"',
                'text' => '',
                'premium' => true
			),
            'sliderThumbSubMenuBackgroundColor' => array(
                'label' => 'Item Submenu Button color',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba"',
                'text' => ''
            ),
            'sliderThumbSubMenuIconColor' => array(
                'label' => 'Item Submenu Button Icon color',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba"',
                'text' => ''
            ),
            'sliderThumbSubMenuBackgroundColorOver' => array(
                'label' => 'Item Submenu Button color (over)',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba"',
                'text' => ''
            ),
            'sliderThumbSubMenuIconHoverColor' => array(
                'label' => 'Item Submenu Button Icon color (over)',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba"',
                'text' => ''
            ),
            'sliderInfoEnable' => array(
                'label' => 'Item Info button',
                'tag' => 'checkbox',
                'attr' => 'data-watch="change"',
                'text' => ''
            ),
            'sliderPlayButton' => array('label' => 'Slideshow Play Button Show',
                'tag' => 'checkbox',
                'attr' => 'data-watch="change"',
                'text' => '',
                'premium' => true
            ),
			'slideshowDelay'      => array(
                'label' => 'Slideshow Delay',
                'tag'   => 'input',
                'attr'  => 'type="number" min="1" data-sliderPlayButton="is:1"',
                'text'  => 'Delay between change slides in seconds',
                'premium' => true
            ),
            'slideshowProgressBarColor' => array('label' => 'Slideshow progress bar color',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba" data-sliderPlayButton="is:1"',
                'text' => '',
                'premium' => true
            ),
            'slideshowProgressBarBGColor' => array('label' => 'Slideshow progress bar Background color',
                'tag' => 'input',
                'attr' => 'type="text" data-type="rgba" data-sliderPlayButton="is:1"',
                'text' => '',
                'premium' => true
            ),
            'sliderZoomButton' => array('label' => 'Zoom Button Show',
                'tag' => 'checkbox',
                'attr' => '',
                'text' => ''
            ),
            'sliderItemDownload' => array(
                'label' => 'Item Download button',
                'tag' => 'checkbox',
                'attr' => 'data-watch="change"',
                'text' => '',
                'premium' => true
            ),
            'sliderSocialShareEnabled' => array(
                'label' => 'Item Share button',
                'tag' => 'checkbox',
                'attr' => 'data-watch="change"',
                'text' => ''
            ),
            'sliderLikesEnabled' => array(
                'label' => 'Item Like button',
                'tag' => 'checkbox',
                'attr' => 'data-watch="change"',
                'text' => ''
            ),
            'sliderFullScreen' => array('label' => 'FullScreen Button Show',
                'tag' => 'checkbox',
                'attr' => '',
                'text' => ''
            ),
        )
    ),
    array(
        'label' => 'Advanced Settings',
        'fields' => array(
            'customCSS' => array(
                'label' => 'Custom CSS',
                'tag' => 'textarea',
                'attr' => 'cols="20" rows="10"',
                'text' => 'You can enter custom style rules into this box if you\'d like. IE: <i>a{color: red !important;}</i>
                                                                      <br />This is an advanced option! This is not recommended for users not fluent in CSS... but if you do know CSS, 
                                                                      anything you add here will override the default styles'
            )
            /*,
			'loveLink' => array(
				'label' => 'Display LoveLink?',
				'tag' => 'checkbox',
				'attr' => '',
				'text' => 'Selecting "Yes" will show the lovelink icon (codeasily.com) somewhere on the gallery'
			)*/
        )
    )
);
