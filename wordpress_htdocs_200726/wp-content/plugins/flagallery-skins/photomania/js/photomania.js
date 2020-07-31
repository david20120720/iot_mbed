/** @preserve
 * PhotoMania
 */
(function($, window, document, undefined) {

  'use strict';

  // undefined is used here as the undefined global variable in ECMAScript 3 is
  // mutable (ie. it can be changed by someone else). undefined isn't really being
  // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
  // can no longer be modified.

  // window and document are passed through as local variable rather than global
  // as this (slightly) quickens the resolution process and can be more efficiently
  // minified (especially when both are regularly referenced in your plugin).

  // Create the defaults once
  var pluginName = 'photomania';

  // The actual plugin constructor
  function Plugin(element, options, content) {
    this.el = element;
    this.$el = $(element);
    this.content = content;
    this._options = options;
    this._name = pluginName;

    this.init();
  }

  // Avoid Plugin.prototype conflicts
  $.extend(Plugin.prototype, {

    _defaults: {
      description_title: 'Description',
      download_button_text: 'Download',
      link_button_text: 'Open Link',
      scale_mode: 'fit',
      link_color: '0099e5',
      link_color_hover: '02adea',
      ajaxurl: ((FlaGallery && FlaGallery.ajaxurl) ? FlaGallery.ajaxurl : '')
    },
    _defaults_int: {
      base_gallery_width: 800,
      base_gallery_height: 500,
      gallery_min_height: 230,
      thumbs_per_view: 4,
      thumbs_space_between: 2,
      initial_slide: 0,
      slideshow_delay: 7000
    },
    _defaults_bool: {
      slideshow_autoplay: false,
      gallery_focus: false,
      gallery_maximized: false,
      gallery_focus_maximized: false,
      keyboard_help: true,
      show_download_button: true,
      show_link_button: true,
      show_description: true,
      show_share_button: true,
      show_like_button: true
    },

    init: function() {
      this.gallID = this._options.gallID;
      this._options = this.sanitizeOptions(this._options);
      this.opts = $.extend(true, {}, this._defaults, this._defaults_int, this._defaults_bool, this._options);
      this.timeout = 0;
      this.prepareDom();

      var self = this;
      var gallery_params = {
        navigation: {
          nextEl: $('.flagpm_photo_arrow_next', self.el),
          prevEl: $('.flagpm_photo_arrow_previous', self.el)
        },
        hashNavigation: self.opts.hashnav,
        spaceBetween: 10,
        keyboardControl: false,
        preloadImages: false,
        lazy: {
          loadPrevNext: true
        },

        initialSlide: self.opts.initial_slide,

        on: {
          init: function() {
            var swiper = this;
            var ai = swiper.activeIndex;
            var item = self.content.data[ai];
            if (ai) {
              self.swiper_onSlideChangeStart(swiper);
              self.swiper_onTransitionEnd(swiper);
            }

            setTimeout(function() {
              self.slideRate();
            }, 900);

            if (self.opts.show_like_button) {
              if (self.storage[item.id] && self.storage[item.id].status && 'liked' == self.storage[item.id].status) {
                $('.flagpm_like', self.el).addClass('flagpm_liked');
              }
              else {
                $('.flagpm_like', self.el).removeClass('flagpm_liked');
              }
            }

            $('.flagpm_photo_show', self.el).removeAttr('style').addClass('flagpm_prepare').removeClass('flagpm_preload');

            self.button_state_gallery(swiper);
          }
        }
      };
      if (self.opts.slideshow_autoplay) {
        gallery_params.autoplay = { delay: self.opts.slideshow_delay };
      }
      var thumbs_params = {
        slideActiveClass: 'firstofset',
        slideNextClass: 'secondofset',
        slidePrevClass: 'zeroofset',
        centeredSlides: false,
        watchSlidesProgress: true,
        watchSlidesVisibility: true,
        preloadImages: false,
        lazy: {
          loadPrevNext: true
        },

        spaceBetween: self.opts.thumbs_space_between,
        slidesPerView: self.opts.thumbs_per_view,
        slidesPerGroup: self.opts.thumbs_per_view,
        initialSlide: self.opts.initial_slide,

        on: {
          init: function() {
            var swiper = this;
            if (!Math.floor(self.opts.initial_slide / self.opts.thumbs_per_view)) {
              swiper.lazy.load();
            }

            var is = self.opts.initial_slide;
            $(swiper.slides[is]).addClass('swiper-slide-active');

          }
        }
      };
      this.swiper_thumbs_el = $('.swiper-small-images', this.el);
      this.swiper_gallery_el = $('.swiper-big-images', this.el);
      this.swiper_thumbs = new Swiper(this.swiper_thumbs_el, thumbs_params);
      this.swiper_gallery = new Swiper(this.swiper_gallery_el, gallery_params);

      this.prepareGallery();
      this.eventsHandler();

      var fullScreenApi = {
          ok: false,
          is: function() {
            return false;
          },
          request: function() {
          },
          cancel: function() {
          },
          event: '',
          prefix: ''
        },
        browserPrefixes = 'webkit moz o ms khtml'.split(' ');

      // check for native support
      if (typeof document.cancelFullScreen != 'undefined') {
        fullScreenApi.ok = true;
      }
      else {
        // check for fullscreen support by vendor prefix
        for (var i = 0, il = browserPrefixes.length; i < il; i++) {
          fullScreenApi.prefix = browserPrefixes[i];
          if (typeof document[fullScreenApi.prefix + 'CancelFullScreen'] != 'undefined') {
            fullScreenApi.ok = true;
            break;
          }
        }
      }

      // update methods to do something useful
      if (fullScreenApi.ok) {
        fullScreenApi.event = fullScreenApi.prefix + 'fullscreenchange';
        fullScreenApi.is = function() {
          switch (this.prefix) {
            case '':
              return document.fullScreen;
            case 'webkit':
              return document.webkitIsFullScreen;
            default:
              return document[this.prefix + 'FullScreen'];
          }
        };
        fullScreenApi.request = function(el) {
          return (this.prefix === '') ? el.requestFullScreen() : el[this.prefix + 'RequestFullScreen']();
        };
        fullScreenApi.cancel = function(el) {
          return (this.prefix === '') ? document.cancelFullScreen() : document[this.prefix + 'CancelFullScreen']();
        };
      }

      this.fsApi = fullScreenApi;

      //this.galleryAutoHeight();

    },
    sanitizeOptions: function(options) {
      var self = this;
      return $.each(options, function(key, val) {
        if (key in self._defaults_bool) {
          options[key] = (!(!val || val == '0' || val == 'false'));
        }
        else if (key in self._defaults_int) {
          options[key] = parseInt(val);
        }
      });

    },
    prepareDom: function() {
      $('.swiper-wrapper img', this.el).removeAttr('alt');

      this.storage = {};
      if (window.sessionStorage) {
        var elid = this.$el.attr('id');
        this.storage = sessionStorage.getItem(elid);
        if (this.storage) {
          this.storage = JSON.parse(this.storage);
        }
        else {
          this.storage = {};
        }
      }
    },
    prepareGallery: function() {

      //this.gps_location();

      var sw_thumbs = $('.swiper-small-images', this.el),
        slide_count = $('.swiper-slide', sw_thumbs).length;
      if (slide_count < this.opts.thumbs_per_view) {
        var placeholders = [],
          p_count = this.opts.thumbs_per_view - slide_count,
          sl_mr = this.opts.thumbs_space_between,
          sl_w = (sw_thumbs.width() + sl_mr) / this.opts.thumbs_per_view - sl_mr;
        for (var i = 0; i < p_count; i++) {
          if (i == (p_count - 1)) {
            sl_mr = 0;
          }
          placeholders.push($('<div class="swiper-slide-placeholder"></div>').css({width: sl_w, marginRight: sl_mr}));
        }

        $('.swiper-wrapper', sw_thumbs).append(placeholders);
      }
      this.galleryWidth();
      this.galleryAutoHeight();

      this.button_state_thumbs(this.swiper_thumbs);
    },
    galleryWidth: function() {
      var el_w = this.$el.width(),
        photo_show = $('.flagpm_photo_show', this.$el);
      if (el_w <= 960) {
        photo_show.addClass('flagpm_w960');
        if (el_w <= 800) {
          photo_show.addClass('flagpm_w640');
          if (el_w <= 560) {
            photo_show.addClass('flagpm_w480');
          }
          else {
            photo_show.removeClass('flagpm_w480');
          }
        }
        else {
          photo_show.removeClass('flagpm_w640 flagpm_w480');
        }
      }
      else {
        photo_show.removeClass('flagpm_w960 flagpm_w640 flagpm_w480');
      }

    },
    galleryAutoHeight: function() {
      var self = this,
        photo_wrap = $('.flagpm_photo_wrap', self.el),
        old_height = photo_wrap.height(),
        el_w = self.$el.width(),
        el_h = self.$el.height(),
        win_h = Math.min(el_h, self.$el.parent().height()),
        window_height = win_h - $('.flagpm_photo_header', self.el).outerHeight(),
        item_w = el_w - parseInt($(photo_wrap).css('padding-left')) - parseInt($(photo_wrap).css('padding-right')),
        photo_show_ratio = self.opts.base_gallery_width / self.opts.base_gallery_height,
        base_photo_show_height = Math.round(item_w / photo_show_ratio),
        photo_show_height = base_photo_show_height;
      if (self.opts.gallery_maximized) {
        var ai = self.swiper_gallery.activeIndex,
          item = self.content.data[ai];
        if ((item_w > item.meta.webview[0]) && ('fit' == self.opts.scale_mode)) {
          item_w = item.meta.webview[0];
        }
        console.log(item);
        var item_h = Math.round(item_w / item.ratio);
        photo_show_height = item_h + parseInt($(photo_wrap).css('padding-top')) + parseInt($(photo_wrap).css('padding-bottom'));
      }
      if ($('body').is('#fullwindow')) {
        if ((!self.opts.gallery_maximized && (photo_show_height != window_height))) {
          photo_show_height = window_height;
        }
      }
      if (photo_show_height < self.opts.gallery_min_height) {
        photo_show_height = self.opts.gallery_min_height;
      }

      if (old_height != photo_show_height) {
        photo_wrap.css({'height': photo_show_height});
        $('.flagpm_gallery_sources_menu', self.el).css({'height': photo_show_height});

        photo_wrap.one('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd', function() {
          self.swiper_gallery.updateSize();
          self.swiper_thumbs.updateSize();
          setTimeout(function() {
            self.galleryWidth();
          }, 1);
        });
      }
      setTimeout(function() {
        $('.flagpm_photo_show', this.el).removeClass('flagpm_prepare');
      }, 10);
    },
    button_state_gallery: function(swiper) {
      var photo_show = $('.flagpm_photo_show', this.el);
      if (swiper.isBeginning) {
        $('.flagpm_photo_wrap', photo_show).removeClass('has_prev_photo');
      }
      else {
        $('.flagpm_photo_wrap', photo_show).addClass('has_prev_photo');
      }
      if (swiper.isEnd) {
        $('.flagpm_photo_wrap', photo_show).removeClass('has_next_photo');
      }
      else {
        $('.flagpm_photo_wrap', photo_show).addClass('has_next_photo');
      }
    },
    button_state_thumbs: function(swiper) {
      var photo_show = $('.flagpm_photo_show', this.el);
      if (swiper.isBeginning) {
        $('.flagpm_carousel', photo_show).removeClass('flagpm_has_previous');
      }
      else {
        $('.flagpm_carousel', photo_show).addClass('flagpm_has_previous');
      }
      if (swiper.isEnd) {
        $('.flagpm_carousel', photo_show).removeClass('flagpm_has_next');
      }
      else {
        $('.flagpm_carousel', photo_show).addClass('flagpm_has_next');
      }
    },
    swiper_onSlideChangeStart: function(swiper) {
      var self = this,
        photo_show = $('.flagpm_photo_show', this.el);

      clearTimeout(self.timeout);

      self.button_state_gallery(swiper);

      var ai = swiper.activeIndex,
        item = self.content.data[ai];

      $('.flagpm_title', photo_show).text(item.title);
      if (self.opts.show_download_button) {
        $('.flagpm_download_button', photo_show).attr({'href': item.download, 'download': item.file});
      }
      if (self.opts.show_link_button) {
        if (item.link) {
          $('.flagpm_link_button', photo_show).attr({'href': item.link, 'target': item.link_target}).removeClass('flagpm_inactive');
        }
        else {
          $('.flagpm_link_button', photo_show).removeAttr('href').removeAttr('target').addClass('flagpm_inactive');
        }
      }
      if (self.opts.show_description) {
        $('.flagpm_slide_description', photo_show).html(item.description);
        if (item.description.length) {
          $('.flagpm_description_wrap', photo_show).removeClass('empty-item-description');
        }
        else {
          $('.flagpm_description_wrap', photo_show).addClass('empty-item-description');
        }
      }

      if (self.opts.show_like_button) {
        if (self.storage[item.id] && self.storage[item.id].status && 'liked' == self.storage[item.id].status) {
          $('.flagpm_like', photo_show).addClass('flagpm_liked');
        }
        else {
          $('.flagpm_like', photo_show).removeClass('flagpm_liked');
        }
      }

      $(self.swiper_thumbs.slides[ai]).addClass('swiper-slide-active').siblings().removeClass('swiper-slide-active');
      self.swiper_thumbs.slideTo(ai);
    },
    swiper_onTransitionEnd: function(swiper) {
      var self = this;

      if (self.opts.gallery_maximized) {
        self.galleryAutoHeight();
      }

      self.timeout = setTimeout(function() {
        self.slideRate();
      }, 900);
    },
    eventsHandler: function() {
      var self = this,
        photo_show = $('.flagpm_photo_show', self.el);

      $(window).on('resize', function() {
        self.galleryWidth();
        clearTimeout(self.timeout);
        self.timeout = setTimeout(function() {
          self.galleryAutoHeight();
        }, 600);
      });

      if (this.opts.gallery_focus) {
        self.focus(true);
      }

      $('img.flagpm_the_photo', photo_show).on('click', function() {
        self.focus();
      });

      $('.flagpm_close', photo_show).on('click', function() {
        self.focus(false);
      });

      $('.flagpm_next_button', photo_show).on('click', function() {
        self.swiper_thumbs.slideNext();
      });
      $('.flagpm_previous_button', photo_show).on('click', function() {
        self.swiper_thumbs.slidePrev();
      });

      $('.flagpm_link_button', photo_show).on('click', function(e) {
        if ($(this).hasClass('flagpm_inactive')) {
          e.preventDefault();
          return false;
        }
      });

      self.swiper_thumbs_el.on('click', function() {
        self.swiper_gallery.slideTo(self.swiper_thumbs.clickedIndex);
        $(self.swiper_thumbs.clickedSlide).addClass('swiper-slide-active').siblings('.swiper-slide-active').removeClass('swiper-slide-active');
      });

      self.swiper_thumbs.on('slideChangeTransitionStart', function() {
        self.button_state_thumbs(self.swiper_thumbs);
      });

      self.swiper_gallery.on('transitionEnd', function() {
        self.swiper_onTransitionEnd(self.swiper_gallery);
      });

      self.swiper_gallery.on('slideChangeTransitionStart', function() {
        self.swiper_onSlideChangeStart(self.swiper_gallery);
      });

      $('.flagpm_share', photo_show).on('click', function() {
        $(this).parent().toggleClass('flagpm_share_opened');
      });
      $(document).on('click', function(e) {
        if (!$(e.target).hasClass('flagpm_share')) {
          $('.flagpm_share').parent().removeClass('flagpm_share_opened');
        }
      });

      $('.flagpm_sharelizer', photo_show).on('click', function() {
        var sharelink,
          ai = self.swiper_gallery.activeIndex,
          item = self.content.data[ai];
        var title = item.title,
          slide_div = self.swiper_gallery.slides[ai],
          imgsrc = $('img', slide_div).attr('src'),
          _url = ('' + window.location.href).split('#'),
          url = _url[0];
        if ($(this).hasClass('flagpm_twitter')) {
          sharelink = 'https://twitter.com/home?status=' + encodeURIComponent(title + ' ' + url);
          window.open(sharelink, '_blank');
        }
        if ($(this).hasClass('flagpm_facebook')) {
          sharelink = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url);
          window.open(sharelink, '_blank');
        }
        if ($(this).hasClass('flagpm_pinterest')) {
          sharelink = 'https://pinterest.com/pin/create/button/?url=' + encodeURIComponent(url) + '&media=' + encodeURIComponent(imgsrc) + '&description=' + encodeURIComponent(title);
          window.open(sharelink, '_blank');
        }
        if ($(this).hasClass('flagpm_stumbleupon')) {
          sharelink = 'http://www.stumbleupon.com/submit?url=' + encodeURIComponent(url) + '&title=' + encodeURIComponent(title);
          window.open(sharelink, '_blank');
        }
      });

      if (self.opts.show_like_button) {
        $('.flagpm_like', photo_show).on('click', function() {
          self.slideRate(true);
          $('.flagpm_like', photo_show).addClass('flagpm_liked');
        });
      }

      $('.flagpm_focus_keyboard_dismiss', photo_show).on('click', function() {
        $('.flagpm_focus_footer', photo_show).fadeOut(400);
        photo_show.addClass('flagpm_diskeys');
        setTimeout(function() {
          self.swiper_gallery.update();
        }, 0);
        self.opts.keyboard_help = false;
      });

      $('.flagpm_full', photo_show).on('click', function() {
        if (self.fsApi.ok) {
          if (self.fsApi.is()) {
            self.fsApi.cancel($('html')[0]);
          }
          else {
            self.fsApi.request($('html')[0]);
          }
        }
        else {
          self.maximize();
        }
        setTimeout(function() {
          self.swiper_gallery.update(true);
        }, 0);
      });

      $(self.el).one('mouseover mousemove', function(e) {
        if ($(e.target).parents(self.el).length) {
          photo_show.addClass('flagpm_mouse_enter');
          self.activate_keyboard();
        }
      });
      $(self.el).mouseenter(function() {
        photo_show.addClass('flagpm_mouse_enter');
        self.activate_keyboard();
      }).mouseleave(function() {
        photo_show.removeClass('flagpm_mouse_enter');
      });
    },
    activate_keyboard: function() {
      var self = this,
        photo_show = $('.flagpm_photo_show', self.el);
      if (!photo_show.hasClass('flagpm_keyboard_active')) {
        $('.flagpm_photo_show').removeClass('flagpm_keyboard_active');
        Mousetrap.reset();
        Mousetrap.bind('esc', function() {
          if (self.opts.gallery_focus_maximized) {
            self.maximize();
            return;
          }
          else if (self.opts.gallery_focus) {
            self.focus(false);
          }
          else {
            if (!photo_show.hasClass('flagpm_mouse_enter')) {
              Mousetrap.reset();
              $('.flagpm_photo_show').removeClass('flagpm_keyboard_active');
            }
          }
        });
        Mousetrap.bind('m', function() {
          self.maximize();
        });
        Mousetrap.bind('s', function() {
          self.slideshow();
        });
        Mousetrap.bind('left', function() {
          self.swiper_gallery.slidePrev();
        });
        Mousetrap.bind('right', function() {
          self.swiper_gallery.slideNext();
        });
        photo_show.addClass('flagpm_keyboard_active');
      }
    },
    slideRate: function(like) {
      var elid = this.$el.attr('id'),
        ai = this.swiper_gallery.activeIndex,
        item = this.content.data[ai];
      if (!this.storage[item.id]) {
        this.storage[item.id] = {};
      }
      if (this.storage[item.id].status) {
        item.viewed = this.content.data[ai].viewed = true;
        if ('liked' == this.storage[item.id].status) {
          item.liked = this.content.data[ai].liked = true;
        }
      }
      if (!item.viewed) {
        this.content.data[ai].viewed = true;
        this.storage[item.id].status = 'viewed';
        item = this.content.data[ai];
        sessionStorage.setItem(elid, JSON.stringify(this.storage));
        if (this.opts.ajaxurl) {
          $.ajax({
            type: 'post',
            dataType: 'json',
            url: this.opts.ajaxurl,
            data: {action: 'flagallery_module_interaction', hit: item.id}
          }).done(function(r) {
            if (r.views) {
              item.meta.views = r.views;
            }
          });
          this.content.data[ai] = item;
        }
      }
      if (like && !item.liked) {
        this.content.data[ai].liked = true;
        this.content.data[ai].meta.likes += 1;
        this.storage[item.id].status = 'liked';
        item = this.content.data[ai];
        sessionStorage.setItem(elid, JSON.stringify(this.storage));
        if (this.opts.ajaxurl) {
          $.ajax({
            type: 'post',
            dataType: 'json',
            url: this.opts.ajaxurl,
            data: {action: 'flagallery_module_interaction', hit: item.id, vote: 1}
          }).done(function(r) {
            if (r.likes) {
              item.meta.likes = r.likes;
            }
          });
          this.content.data[ai] = item;
        }

        $('.flagpm_like_count', this.$el).text(this.content.data[ai].meta.likes);
      }
    },
    focus: function(active) {
      var photo_show = $('.flagpm_photo_show', this.el);
      this.opts.gallery_focus = (undefined === active) ? !this.opts.gallery_focus : active;
      if (this.opts.gallery_focus) {
        //photo_show.addClass('flagpm_focus');
        this.focus_help_items = this.doHideBuster(photo_show);
      }
      else {
        photo_show.addClass('flagpm_no-transition');
        this.undoHideBuster(this.focus_help_items);
        setTimeout(function() {
          photo_show.removeClass('flagpm_no-transition');
        }, 0);
      }
      var self = this;
      setTimeout(function() {
        self.swiper_gallery.update(true);
      }, 0);
    },
    slideshow: function() {
      if (this.swiper_gallery.autoplay.running) {
        this.swiper_gallery.autoplay.stop();
      }
      else {
        this.swiper_gallery.params.autoplay = { delay: this.opts.slideshow_delay };
        this.swiper_gallery.autoplay.start();
      }
    },
    maximize: function() {
      var photo_show = $('.flagpm_photo_show', this.el);
      if (this.opts.gallery_focus) {
        if (this.opts.gallery_focus_maximized) {
          photo_show.removeClass('flagpm_focus_maximized');
          this.opts.gallery_focus_maximized = false;
        }
        else {
          photo_show.addClass('flagpm_focus_maximized');
          this.opts.gallery_focus_maximized = true;
        }
      }
      else {
        if (this.opts.gallery_maximized) {
          photo_show.removeClass('flagpm_maximized');
          this.opts.gallery_maximized = false;
        }
        else {
          photo_show.addClass('flagpm_maximized');
          this.opts.gallery_maximized = true;
        }
        this.galleryAutoHeight();
      }
    },
    hasProperty: function(value, index) {
      if (index instanceof Array) {
        return index.length === 0 ||
          (this.hasProperty(value, index[0])
            && this.hasProperty(value[index[0]], index.slice(1)));
      }
      return value.hasOwnProperty(index);
    },
    doHideBuster: function(item) {// Make all parents & current item visible
      var parent = item.parent(),
        items = [];

      if (typeof (item.prop('tagName')) !== 'undefined' && item.prop('tagName').toLowerCase() != 'body') {
        items = this.doHideBuster(parent);

        item.addClass('flagpm_focus');
        items.push(item);
      }

      return items;
    },
    undoHideBuster: function(items) {// Hide items in the array
      var i;

      for (i = 0; i < items.length; i++) {
        items[i].removeClass('flagpm_focus');
      }
    },
    $_GET: function(variable) {
      var url = window.location.href.split('?')[1];
      if (url) {
        url = url.split('#')[0];
        var variables = (typeof (url) === 'undefined') ? [] : url.split('&'),
          i;

        for (i = 0; i < variables.length; i++) {
          if (variables[i].indexOf(variable) != -1) {
            return variables[i].split('=')[1];
          }
        }
      }

      return false;
    }

  });

  $.fn[pluginName] = function(options, content) {
    options = options || {};
    content = content || {};
    return this.each(function() {
      if (!$.data(this, pluginName)) {
        var pluginInstance = new Plugin(this, options, content);
        $.data(this, pluginName, pluginInstance);
      }
    });
  };

})(jQuery, window, document);
