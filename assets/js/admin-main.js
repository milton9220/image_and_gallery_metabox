var frame,gframe;
;(function($){
        var image_url=$("#omb_image_url").val();
        if(image_url){
            $("#preview_image").html(`<img src='${image_url}'/>`);
        }
        var images_url=$("#omb_images_url").val();
        var _images_url=images_url ? images_url.split(','):[];
        for(let j in _images_url){
            var _image_url=_images_url[j];
            $("#preview_images").append(`<img src='${_image_url}'/>`);
        }
        //removing remove images button
        if(_images_url.length==0){
            $("#remove_images").hide();
        }
    $("#upload_image").on('click',function(){
        if(frame){
            frame.open();
            return false;
        }
        frame=wp.media({
            title:'Select Image',
            button:{
                text:'Insert Image'
            },
            multiple:false
        });

        frame.on('select',function(){
            var attachment=frame.state().get('selection').first().toJSON();
            $("#omb_image_id").val(attachment.id);
            $("#omb_image_url").val(attachment.sizes.thumbnail.url);
            $("#preview_image").html(`<img src='${attachment.sizes.thumbnail.url}'/>`);
        });

        frame.open();
        return false;
    });
   
    $("body").on('click', '#remove_images', function(event) {
        event.preventDefault();
        $("#omb_images_id").val("");
        $("#omb_images_url").val("");
        $("#preview_images").html('');
        $("#remove_images").hide();
        return false;
    });
    $("#upload_images").on('click',function(){
        if(gframe){
            gframe.open();
            return false;
        }
        gframe=wp.media({
            title:'Select Images',
            button:{
                text:'Insert Images'
            },
            multiple:true
        });

        gframe.on('select',function(){
            var attachments=gframe.state().get('selection').toJSON();
            var image_ids=[];
            var image_urls=[];
            
            $("#preview_images").html('');
            for(let i in attachments){
                var attachment=attachments[i];
                image_ids.push(attachment.id);
                image_urls.push(attachment.sizes.thumbnail.url);
                $("#preview_images").append(`<img src='${attachment.sizes.thumbnail.url}'/>`);
            }
            $("#omb_images_id").val(image_ids.join(','));
            $("#omb_images_url").val(image_urls.join(','));
            $("#remove_images").show();
        });

        gframe.open();
        return false;
    });
    
})(jQuery);