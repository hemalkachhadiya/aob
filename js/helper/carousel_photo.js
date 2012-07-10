$('.CarouselPhoto').live('click',function(e){
    e.preventDefault();
    //var imgSrc = $(this).attr('src');

    //$('#SinglePhotoContainer img').attr('src',imgSrc);
    var photoName = '';
    console.log($(this).attr('photoname'));
    if ($(this).attr('photoname') !== 'null' ){
        photoName = $(this).attr('photoname');
    }
    var imgSrc = '/images/'+$(this).attr('path')+'/big/'+$(this).attr('photolink');
    $('#SinglePhotoContainer img').attr('src',imgSrc);
    $('#SinglePhotoContainerName').text(photoName);
   /* if ($(this).attr('photoname')!= null){
        $('#SinglePhotoContainerName').text($(this).attr('photoname'));
    }*/
})