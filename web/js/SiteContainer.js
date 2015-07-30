SiteContainer = {
    triggerFileInputClick: function() {
        $(".form-control.file-caption.kv-fileinput-caption").click(function(){
            $("#user-image").trigger("click");
        });
    },

    serializeObject: function(form) {
        var o = {};
        var a = $(form).serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    },

    submitAjaxForm: function(form, event) {
        event.preventDefault();

        var serializedObject = SiteContainer.serializeObject(form);
        console.log(serializedObject);

        var formData = new FormData($('form')[0]);
        formData.append('data', serializedObject);
        if(document.getElementById("user-image") != null) {
            formData.append('image', document.getElementById("user-image").files[0]);
        }

        $.ajax({
            url: $(form).attr('action'),
            type: 'POST',
            contentType: false,
            processData: false,
            cache: false,
            async: false,
            data: formData,
            success: function (data) {
                //success
                if(data == true) {

                    alert('updated');

                } else {
                    console.log(data);
                    alert(data);
                }
            },
            error: function (error) {
                alert("An error" + error);
            }
        });
    }
}

$(window).load(function() {
    SiteContainer.triggerFileInputClick();

    $('#user-zipcode').mask("00-000", {placeholder: "__-___"});
    $("input[name = 'RestaurantCRUD[zip_code]']").mask("00-000", {placeholder: "__-___"});
    $('#restaurant-zip_code').mask("00-000", {placeholder: "__-___"});
    $("input[name = 'UserCRUD[zipcode]']").mask("00-000", {placeholder: "__-___"});
})