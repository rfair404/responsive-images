/**
 * Author: Russell Fair
 * Localized using the ResponsiveImageUpsizer object
 */

function resize(){
    var selector = '.' + ResponsiveImageUpsizer['selector'];
    $(selector).each(function(){
        if (ResponsiveImageUpsizer['selector_crop_mask'] != '') {
            mask = $(this).closest('.' + ResponsiveImageUpsizer['selector_crop_mask']);
        } else {
            mask = this;
        }
        upsize_image(this, mask);
    })
}

function getWidth( width ) {
    var useSize = 'full';
    $.each(ResponsiveImageUpsizer.sizes, function (size) {
        if (this.width >= width) {
            useSize = size;
            return false;
        }
    });
    return useSize;
}
function getHeight ( height ) {
    var useSize = 'full';
    $.each(ResponsiveImageUpsizer.sizes, function (size){
        if (this.width >= height) {
            useSize = size;
            return false;
        }
    });
    console.log(useSize);
    return useSize;
}

function getId( img ){
    var className = img.className.match(/responsive-image-upsizer-id-\d+/);
    var imageId = className[0].replace("responsive-image-upsizer-id-", "");
    return imageId;
}

function getImageSrc( id, size ){
    //this is ugly, I know, but this is version 1 right?
    var images = ResponsiveImageUpsizer.images;
    var imgSrc = false;
    $.each(images, function( image ){
        if( this.id == id ){
            imgSrc = this[size];
        }
    });
    return imgSrc;
}

function upsize_image( img, parent ){
    // console.log(parent);
    if (ResponsiveImageUpsizer['selector_orientation']) {
        var size = getHeight(parent.height());
    } else {
        var size = getWidth(parent.width());
    }
    var id = getId( img );
    var imgSrc = getImageSrc( id, size );
    if( imgSrc ){
        $(img).attr('src', imgSrc).attr('getWidth', img.width).attr('height', 'auto');
    }
}

// $(document).ready(function(){
//     // $(document).on('load', function {
//     //     resize();
//     // });
//     resize();
// });
$(window).load(function (){
    resize();
});
$(window).resize(function(){
    resize();
});

