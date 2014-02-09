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

function upsize_image( img ){
    console.log( img.width );
}

$('document').ready(function(){
    console.log('starting image resize run');
    resize();
});

