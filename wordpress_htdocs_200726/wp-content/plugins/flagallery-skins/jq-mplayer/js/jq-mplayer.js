/*
 * Title      : Music Player Module for FlaGallery plugin
 * Copyright  : 2013-2016 CodEasily.com
 * Website    : http://www.codeasily.com
 */
(function ($) {
    $.fn.flagMusicPlayer = function (playlist, userOptions) {
        var $self = this, opt_str, opt_int, opt_bool, opt_obj, options, cssSelector, appMgr, playlistMgr, interfaceMgr, ratingsMgr,
            layout, ratings, myPlaylist, current,
            $uid = $(this).data('uid');
        cssSelector = {
            jPlayer: ".flag-music-player",
            jPlayerInterface: '.flagmp-' + $uid,
            playerPrevious: ".flagmp-interface .flagmp-previous",
            playerNext: ".flagmp-interface .flagmp-next",
            trackList: '.flagmp-tracklist',
            tracksWrapper: '.flagmp-tracks-wrapper',
            tracks: '.flagmp-tracks',
            track: '.flagmp-track',
            title: '.flagmp-track-title',
            text: '.flagmp-track-description',
            duration: '.flagmp-duration',
            button: '.flagmp-button',
            buttonNotActive: '.flagmp-not-active',
            playing: '.flagmp-playing',
            moreButton: '.flagmp-more',
            player: '.flagmp-player',
            //artist:'.flagmp-artist',
            //artistOuter:'.flagmp-artist-outer',
            albumCover: '.flagmp-img',
            description: '.flagmp-description',
            descriptionShowing: '.flagmp-showing',

            play: ".flagmp-play",
            pause: ".flagmp-pause",
            stop: ".flagmp-stop",
            mute: ".flagmp-mute",
            unmute: ".flagmp-unmute",
            currentTime: ".flagmp-current-time",
            duration: ".flagmp-duration"

        };

        opt_str = {
            width: 'auto',
            buttonText: 'Download',
            moreText: 'View More...',
            ajaxurl: ''
        };
        opt_int = {
            maxwidth: 0,
            tracksToShow: 5
        };
        opt_bool = {
            autoplay: false,
            loop: true,
        };
        opt_obj = {
            jPlayer: {
                swfPath: userOptions.pluginUrl + '/assets/jplayer'
            }
        };

        options = $.extend(true, {}, opt_str, opt_int, opt_bool, opt_obj, userOptions);
        $.each(options, function (key, val) {
            if (key in opt_bool) {
                options[key] = (!(!val || val == '0' || val == 'false'));
            } else if (key in opt_int) {
                options[key] = parseInt(val);
            }
        });

        myPlaylist = playlist;

        current = 0;

        appMgr = function () {
            playlist = new playlistMgr();
            layout = new interfaceMgr();

            layout.buildInterface();
            playlist.init(options.jPlayer);

            $self.on('mbPlaylistLoaded', function () {
                layout.init();

            });
        };

        playlistMgr = function () {

            var playing = false, markup, $myJplayer = {}, $tracks, $tracksWrapper, $tracksList, $more;

            markup = {
                listItem: '<li class="flagmp-track"><section>' +
                '<span class="flagmp-maxwidth"><span class="flagmp-track-title-wrapper">&nbsp;<span class="flagmp-track-title"></span></span></span>' +
                '<span>' +
                '<span class="flagmp-duration">&nbsp;</span>' +
                '<a href="#" class="flagmp-button flagmp-not-active" target="_blank"></a>' +
                '</span>' +
                '</section></li>'
            };

            function init(playlistOptions) {
                $myJplayer = $('.flag-music-player .jPlayer-container', $self);


                var jPlayerDefaults, jPlayerOptions;

                jPlayerDefaults = {
                    swfPath: "jplayer",
                    supplied: "mp3, oga",
                    cssSelectorAncestor: cssSelector.jPlayerInterface,
                    cssSelector: cssSelector,
                    errorAlerts: false,
                    warningAlerts: false
                };

                //apply any user defined jPlayer options
                jPlayerOptions = $.extend(true, {}, jPlayerDefaults, playlistOptions);

                $myJplayer.on($.jPlayer.event.ready, function () {

                    //Bind jPlayer events. Do not want to pass in options object to prevent them from being overridden by the user
                    $myJplayer.on($.jPlayer.event.ended, function (event) {
                        playlistNext();
                    });

                    $myJplayer.on($.jPlayer.event.play, function (event) {
                        $myJplayer.jPlayer("pauseOthers");
                        $tracks.eq(current).addClass(attr(cssSelector.playing)).siblings().removeClass(attr(cssSelector.playing));
                    });

                    $myJplayer.on($.jPlayer.event.playing, function (event) {
                        playing = true;
                    });

                    $myJplayer.on($.jPlayer.event.pause, function (event) {
                        playing = false;
                    });

                    $myJplayer.on($.jPlayer.event.loadeddata, function (event) {
                        if (event.jPlayer.status.duration != 'NaN') {
                            $tracks.eq(current).find(cssSelector.duration).text($.jPlayer.convertTime(event.jPlayer.status.duration));
                        }
                    });

                    //Bind next/prev click events
                    $(cssSelector.playerPrevious, $self).click(function () {
                        playlistPrev();
                        $(this).blur();
                        return false;
                    });

                    $(cssSelector.playerNext, $self).click(function () {
                        playlistNext();
                        $(this).blur();
                        return false;
                    });

                    $self.on('mbInitPlaylistAdvance', function (e) {
                        var changeTo = this.getData('mbInitPlaylistAdvance');

                        if (changeTo != current) {
                            current = changeTo;
                            playlistAdvance(current);
                        }
                        else {
                            if (!$myJplayer.data('jPlayer').status.srcSet) {
                                playlistAdvance(0);
                            }
                            else {
                                togglePlay();
                            }
                        }
                    });

                    buildPlaylist();
                    //If the user doesn't want to wait for widget loads, start playlist now
                    $self.trigger('mbPlaylistLoaded');

                    playlistInit(options.autoplay);

                });

                //Initialize jPlayer
                $myJplayer.jPlayer(jPlayerOptions);
            }

            function playlistInit(autoplay) {
                current = 0;

                if (autoplay) {
                    playlistAdvance(current);
                }
                else {
                    playlistConfig(current);
                    $self.trigger('mbPlaylistInit');
                }
            }

            function playlistConfig(index) {
                current = index;
                $myJplayer.jPlayer("setMedia", myPlaylist[current]);
            }

            function playlistAdvance(index) {
                playlistConfig(index);

                if (index >= options.tracksToShow)
                    showMore();

                $self.trigger('mbPlaylistAdvance');
                $myJplayer.jPlayer("play");
            }

            function playlistNext() {
                var index = current + 1;
                if(index >= myPlaylist.length){
                    if(!options.loop){
                        return;
                    }
                    index = 0;
                }
                playlistAdvance(index);
            }

            function playlistPrev() {
                var index = (current - 1 >= 0) ? current - 1 : myPlaylist.length - 1;
                playlistAdvance(index);
            }

            function togglePlay() {
                if (!playing)
                    $myJplayer.jPlayer("play");
                else $myJplayer.jPlayer("pause");
            }

            function buildPlaylist() {
                $tracksList = $(cssSelector.tracks, $self);
                $tracksWrapper = $(cssSelector.tracksWrapper, $self);

                for (var j = 0; j < myPlaylist.length; j++) {
                    var $track = $(markup.listItem, $self);

                    $track.find(cssSelector.title).html(trackName(j));

                    setLink($track, j);

                    $track.data('index', j);

                    $tracksList.append($track);
                }

                $tracks = $(cssSelector.track, $self);

                $tracks.eq(options.tracksToShow - 1).nextAll().hide();

                if (options.tracksToShow < myPlaylist.length) {
                    var $trackList = $(cssSelector.trackList, $self);

                    $trackList.addClass('flagmp-show-more-button');

                    $trackList.find(cssSelector.moreButton).click(function () {
                        $more = $(this);

                        showMore();
                    });
                }

                $tracks.find(cssSelector.title).click(function () {
                    playlistAdvance($(this).parents('li').data('index'));
                });
            }

            function showMore() {
                if (isUndefined($more))
                    $more = $self.find(cssSelector.moreButton);
                $tracksWrapper.css('height', $tracksList.height());
                $tracks.show();
                //var tracks_height = Math.ceil($tracks.eq(0).outerHeight()) * myPlaylist.length + 1;
                var tracks_height = $tracksList.height() + 1;
                $tracksWrapper.animate({height: tracks_height}, 400);
                $more.removeClass('anim').animate({'height': 0}, 400, function () {
                    $more.parents(cssSelector.trackList).removeClass('flagmp-show-more-button');
                    $more.remove();
                });
            }

            function setLink($track, index) {
                if (myPlaylist[index].button !== '') {
                    $track.find(cssSelector.button).removeClass(attr(cssSelector.buttonNotActive)).attr('href', myPlaylist[index].button).html(options.buttonText);
                    var ext = myPlaylist[index].button.slice(-4);
                    if (('.mp3' == ext) || ('.ogg' == ext)) {
                        $track.find(cssSelector.button).attr('download', '');
                    }
                }
            }

            return {
                init: init,
                playlistInit: playlistInit,
                playlistAdvance: playlistAdvance,
                playlistNext: playlistNext,
                playlistPrev: playlistPrev,
                togglePlay: togglePlay,
                $myJplayer: $myJplayer
            };

        };

        interfaceMgr = function () {

            var $player, $title, $text, $artist, $albumCover;


            function init() {
                $player = $(cssSelector.player, $self),
                    $title = $player.find(cssSelector.title),
                    $text = $player.find(cssSelector.text),
                    //$artist = $player.find(cssSelector.artist),
                    $albumCover = $player.find(cssSelector.albumCover);

                setDescription();

                $self.on('mbPlaylistAdvance mbPlaylistInit', function () {
                    setTitle();
                    //setArtist();
                    setText();
                    setCover();
                });
            }

            function buildInterface() {
                var markup, $interface;

                //I would normally use the templating plugin for something like this, but I wanted to keep this plugin's footprint as small as possible
                markup =
                    '<div class="flag-music-player">' +
                    '	<div class="flagmp-player flagmp-interface flagmp-' + $uid + '">' +
                    '		<div class="flagmp-album-cover">' +
                    '			<span class="flagmp-img"></span>' +
                    '   	<span class="flagmp-highlight"></span>' +
                    '   </div>' +
                    '   <div class="flagmp-track-title"></div>' +
                    '   <div class="flagmp-player-controls">' +
                    '   	<div class="flagmp-main">' +
                    '     	<div class="flagmp-previous flagmp-previous"></div>' +
                    '       <div class="flagmp-play flagmp-play"></div>' +
                    '       <div class="flagmp-pause flagmp-pause"></div>' +
                    '       <div class="flagmp-next flagmp-next"></div>' +
                    '<!-- These controls aren\'t used by this plugin, but jPlayer seems to require that they exist -->' +
                    '       <span class="flagmp-unused-controls">' +
                    '       	<span class="jp-video-play"></span>' +
                    '         <span class="flagmp-stop"></span>' +
                    '         <span class="flagmp-mute"></span>' +
                    '         <span class="jp-unmute"></span>' +
                    '         <span class="jp-volume-bar"></span>' +
                    '         <span class="jp-volume-bar-value"></span>' +
                    '         <span class="jp-volume-max"></span>' +
                    '         <span class="flagmp-current-time"></span>' +
                    '         <span class="flagmp-duration"></span>' +
                    '         <span class="jp-full-screen"></span>' +
                    '         <span class="jp-restore-screen"></span>' +
                    '         <span class="jp-repeat"></span>' +
                    '         <span class="jp-repeat-off"></span>' +
                    '         <span class="jp-gui"></span>' +
                    '       </span>' +
                    '     </div>' +
                    '     <div class="flagmp-progress-wrapper">' +
                    '     	<div class="flagmp-progress jp-seek-bar">' +
                    '       	<div class="flagmp-elapsed jp-play-bar"></div>' +
                    '       </div>' +
                    '     </div>' +
                    '   </div>' +
                    ' 	<div class="flagmp-track-description"></div>' +
                    ' </div>' +
                    ' <div class="flagmp-description"></div>' +
                    ' <div class="flagmp-tracklist">' +
                    ' 	<div class="flagmp-tracks-wrapper"><ol class="flagmp-tracks"></ol></div>' +
                    '   <div class="flagmp-more flagmp-anim">' + options.moreText + '</div>' +
                    ' </div>' +
                    ' <div class="jPlayer-container"></div>' +
                    '</div>';

                var mw = (0 === options.maxwidth) ? 'none' : options.maxwidth;
                $interface = $(markup).css({display: 'none', opacity: 0, width: options.width, 'max-width': mw}).appendTo($self).slideDown('slow', function () {
                    $interface.animate({opacity: 1});

                    $self.trigger('mbInterfaceBuilt');
                });
            }

            function setTitle() {
                $title.html(trackName(current));
            }

            /*
             function setArtist() {
             if (isUndefined(myPlaylist[current].artist))
             $artist.parent(cssSelector.artistOuter).animate({opacity:0}, 'fast');
             else {
             $artist.html(myPlaylist[current].artist).parent(cssSelector.artistOuter).animate({opacity:1}, 'fast');
             }
             }
             */

            function setText() {
                if (myPlaylist[current].text === '')
                    $text.animate({opacity: 0}, 'fast', function () {
                        $(this).empty()
                    });
                else {
                    $text.html(myPlaylist[current].text).animate({opacity: 1}, 'fast');
                }
            }

            function setCover() {
                $albumCover.animate({opacity: 0}, 'fast', function () {
                    $(this).empty();
                    if (!isUndefined(myPlaylist[current].cover) || myPlaylist[current].cover !== '') {
                        var now = current;
                        $('<img src="' + myPlaylist[current].cover + '" alt="album cover" />').load(function () {
                            if (now == current)
                                $albumCover.html($(this)).animate({opacity: 1})
                        });
                    }
                });
            }

            function setDescription() {
                if (!isUndefined(options.description))
                    $self.find(cssSelector.description).html(options.description).addClass(attr(cssSelector.descriptionShowing)).slideDown();
            }

            return {
                buildInterface: buildInterface,
                init: init
            }

        };

        /** Common Functions **/
        function trackName(index) {
            if (myPlaylist[index].title !== '')
                return myPlaylist[index].title;
            if (myPlaylist[index].mp3 !== '')
                return fileName(myPlaylist[index].mp3);
            if (myPlaylist[index].oga !== '')
                return fileName(myPlaylist[index].oga);
            return 'NaN';
        }

        function fileName(path) {
            path = path.split('/');
            return path[path.length - 1];
        }

        /** Utility Functions **/
        function attr(selector) {
            return selector.substr(1);
        }

        /*
         function runCallback(callback) {
         var functionArgs = Array.prototype.slice.call(arguments, 1);

         if ($.isFunction(callback)) {
         callback.apply(this, functionArgs);
         }
         }
         */

        function isUndefined(value) {
            return typeof value == 'undefined';
        }

        appMgr();
    };
})(jQuery);
