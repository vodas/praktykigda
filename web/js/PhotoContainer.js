PhotoContainer = {
    jcrop_api: null,
    updateCoords: function(c) {
        $('#x').val(c.x);
        $('#y').val(c.y);
        $('#w').val(c.w);
        $('#h').val(c.h);
    },

    checkCoords: function() {
        if (parseInt($('#w').val    ())) return true;
        alert('Please select a crop region then press submit.');
        return false;
    },

    showPreview: function(coords) {
        var rx = 200 / coords.w;
        var ry = 200 / coords.h;

        $('#preview').css({
            width: Math.round(rx * 500) + 'px',
            height: Math.round(ry * 370) + 'px',
            marginLeft: '-' + Math.round(rx * coords.x) + 'px',
            marginTop: '-' + Math.round(ry * coords.y) + 'px'
        });
    },

    initJcrop: function(e, loadedPhoto) {
        if ($('.jcrop-holder').length == 0) {
            PhotoContainer.jcrop_api = $.Jcrop('#cropbox');
        }


        var c = {"x":0,"y":0,"w":100,"h":100};
        $('#cropbox').Jcrop({
            onChange: PhotoContainer.showPreview,
            onSelect: PhotoContainer.updateCoords,

            bgFade: true,
            trueSize: [500,370]
        });

        if (e != null || e != undefined) {
            PhotoContainer.jcrop_api.setImage(e.target.result);
            $('#preview').attr('src', e.target.result);
        }

        if (loadedPhoto) {
            PhotoContainer.jcrop_api.setImage($('#jCropImageHelper').val());
            $('#preview').attr('src', $('#jCropImageHelper').val());
        }
    },

    readURL: function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                PhotoContainer.initJcrop(e);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
}

$(document).ready(function() {
    if($('#cropbox').length > 0 ) {
        $("#user-image").change(function() {
            PhotoContainer.readURL(this);
        });

        PhotoContainer.initJcrop(null, true);
    }
})
