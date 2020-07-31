jQuery(function($){
    var opt_bool = [
            'pauseOnHover',
            'directionNav',
            'controlNav',
            'linkTarget',
            'randomStart',
        ],
        opt_int = [
            'slices',
            'animSpeed',
            'pauseTime',
            'startSlide',
        ];
    $('.flagallery_nivoSlider').each(function(){
        var data = $(this).attr('data-settings');
        data = JSON.parse(data);
        $.each(data, function(key, val) {
            if(opt_bool.indexOf(key) !== -1) {
                data[key] = (!(!val || val == '0' || val == 'false'));
            } else if(opt_int.indexOf(key) !== -1) {
                data[key] = parseInt(val);
            }
        });
        jQuery(this).nivoSlider(data);
    });    
});
