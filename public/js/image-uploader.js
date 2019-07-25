$(document).ready(function() {
    $(document).on('click','#photo_file_trigger',function(){
        $( "#photo_file_input" ).trigger( "click" );
    });
    var options = {
        beforeSubmit:  showRequest,
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            $('.progress-bar').width(percentVal).attr('aria-valuenow',percentComplete);
            $('.percentage').html(percentVal);
        },
        success: showResponse,
        dataType: 'json'
    };

    $('#photo_file_input').on('change',function(){
        if($(this).val().length != 0){
            $('#image_uploader').ajaxForm(options).submit();
        }
    });
});


/*
 * Image Uploader - AJAX
 */

function showRequest(formData, jqForm, options) {
    $("#validation-errors").hide().empty();
    $('.upload-progress').show();
    return true;
}
function showResponse(response, statusText, xhr, $form)  {
    if(response.success == false)
    {
        var arr = response.errors;
        $.each(arr, function(index, value)
        {
            if (value.length != 0)
            {
                $("#validation-errors").append('<span class="text-danger"><i class="fa fa-exclamation-circle"></i> '+ value +'</span>');
                $('.upload-progress').hide();
            }
        });
        $("#validation-errors").show();
    } else {
        $(".photo_holder").html('<img src="/temp/image/'+response.file+'?v='+response.version+'" alt="Image" class="img-responsive">');
        $('#image_path').val(response.file);
        $('.upload-progress').hide();
    }
}

 