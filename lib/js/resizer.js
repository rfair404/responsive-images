/**
 * Author: Russell Fair
 * Localized using the ResponsiveImageUpsizer object
 */

function resize(){
    var selector = '.' + ResponsiveImageUpsizer.selector;
    $(selector).each(function(){
        upsize_image(this);
    })
}

function getWidth( width ){
    var bestSize = 'base';
    var availableSizes = ResponsiveImageUpsizer.sizes;
    $.each(availableSizes, function(size){
        if(this.width >= width){
            bestSize = size;
        }else{
            return bestSize;
        };
    });
    return bestSize;
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

function upsize_image( img ){
    var size = getWidth( img.width );
    var id = getId( img );
    var imgSrc = getImageSrc( id, size );
    if( imgSrc ){
        $(img).attr('src', imgSrc);
        $(img).addClass('resized-' + size );
    }
}

$(document).ready(function(){
    console.log('setting initial sizes');
    resize();
});
$(window).resize(function(){
    console.log('resizing thumbnails');
    resize();
})

