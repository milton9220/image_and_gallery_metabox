var frame;
;(function($){
        var image_url=$("#omb_image_url").val();
        console.log(image_url);
        if(image_url){
            $("#preview_image").html(`<img src='${image_url}'/>`);
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
})(jQuery);