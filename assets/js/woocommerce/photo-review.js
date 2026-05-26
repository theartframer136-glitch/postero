(function ($) {
    'use strict';
    function getSelectedImageHtml(src, name, error='') {
        let selectImageHtml;
        let temp =`<img title="${name}" src="${src}" class="selected-image-preview">`;
        if (error){
            selectImageHtml = `<div class="selected-image">${temp}<div class="selected-image-info"><div class="selected-image-name comment-form-error" >${error}</div></div></div>`;
        }else {
            selectImageHtml = `<div class="selected-image">${temp}<div class="selected-image-info"><div class="selected-image-name" title="${name}">${name}</div></div></div>`;
        }
        return selectImageHtml;
    }

    function getGalleryItems($slides) {
        let items   = [];

        if ( $slides.length > 0 ) {
            $slides.each( function( i, el ) {
                var img = $( el ).find( 'img' );

                if ( img.length ) {
                    var large_image_src = img.attr( 'data-large_image' ),
                        large_image_w   = img.attr( 'data-large_image_width' ),
                        large_image_h   = img.attr( 'data-large_image_height' ),
                        alt             = img.attr( 'alt' ),
                        item            = {
                            alt  : alt,
                            src  : large_image_src,
                            w    : large_image_w,
                            h    : large_image_h,
                            title: img.attr( 'data-caption' ) ? img.attr( 'data-caption' ) : img.attr( 'title' )
                        };
                    items.push( item );
                }
            } );
        }
        return items;
    }

    $(document).on('change', '#postero_image_upload', function (e){
        let input = this,
            $this = $(input)

        for (let i = 0; i < input.files.length; i++) {
            var reader = new FileReader();

            reader.onload = function (e) {
                let error = '';
                $this.parent().find('.selected-image-container').append(getSelectedImageHtml(e.target.result, input.files[i].name, error))
            };

            reader.readAsDataURL(input.files[i]); // convert to base64 string
        }
    }).on('click', '.image-review', function(e){
        e.preventDefault();

        var pswpElement = $( '.pswp' )[0],
            items       = getGalleryItems($(this).closest('.postero-images-review').find('.image-review')),
            eventTarget = $( e.target ),
            clicked;

        clicked = eventTarget.closest( '.image-review' );

        var options = $.extend( {
            index: $( clicked ).index(),
            addCaptionHTMLFn: function( item, captionEl ) {
                if ( ! item.title ) {
                    captionEl.children[0].textContent = '';
                    return false;
                }
                captionEl.children[0].textContent = item.title;
                return true;
            }
        }, wc_single_product_params.photoswipe_options );

        // Initializes and opens PhotoSwipe.
        var photoswipe = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options );
        photoswipe.init();
    })

})(jQuery);
