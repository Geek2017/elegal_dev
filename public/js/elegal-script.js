$(document).ready(function(){

    //$(document).on('keydown','.numonly',function(event) {
    //    // Allow: backspace, delete, tab, escape, and enter
    //    if( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
    //        // Allow: Num Pad Decimal
    //        ( event.keyCode == 190 ) ||
    //        ( event.keyCode == 110 ) ||
    //        // Allow: Ctrl+A
    //        (event.keyCode == 65 && event.ctrlKey === true) ||
    //        // Allow: home, end, left, right
    //        (event.keyCode >= 35 && event.keyCode <= 39)) {
    //        // let it happen, don't do anything
    //        return;
    //    }else{
    //        // Ensure that it is a number and stop the keypress
    //        if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
    //            event.preventDefault();
    //        }
    //    }
    //});

    //$('.numonly').maskNumber();

    /**
     * Hiding Flash Messages
     */
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
    $('#flash-overlay-modal').modal({backdrop: 'static', keyboard: false});
    // $('#flash-overlay-modal').modal();

    /**
     * Retrieving Case 
     */
    if (window.location.pathname == '/' || window.location.pathname == '/home') {
        
        $.get('case-tracker/alerts', function(data, status) {
            if (!data.length) return;
            var $url = "/case-tracker";
            var $currentCount = $("#total-no-of-notifications").text();
            var $html = '';
            
            $("#no-pending-notifications").hide();

            $("#total-no-of-notifications").text(parseInt($currentCount) + data.length);
            $html = '<li>';
            $html +=    '<a href="' + $url + '">';
            $html +=        '<div> <i class="fa fa-balance-scale"></i>';
            $html +=            " <strong style='color:red'>" +data.length + "</strong> case's with pending actions!";
            $html +=        '</div>';
            $html +=    '</a></li><li class="divider"></li>';
            $(".dropdown-alerts").append($html);

            var isUserCaseWasNotified = $("#is_case_was_notified").val();
            if (!isUserCaseWasNotified) {
                swal({
                    title: 'Pending Case Activity!',
                    text:  data.length + ' case(s) with pending actions!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1c84c6',
                    confirmButtonText: 'View',
                    cancelButtonText: 'Close'
                },
                function (isConfirm) {
                    if (isConfirm) {
                        window.location.href = $url;
                    }
                });
            }
        });
    }

});

// (function ($){
//     $.fn.serializeAssoc = function(check = false, excludeFields = []){
//         let fields = {};
//         let toaster = [];
//         unmarker(this);
//         $.each(this.serializeArray(), function(key,item){
//             if(check){
//                 toaster.push(excludeFields.filter(field => {return field == item.name}).length || item.value || sendError(item));
//             }
//             fields[item.name] = item.value;
//         });
//         return toaster.every(x => {return x && true }) ? fields : nofity();
//
//      };
//     function nofity(){
//         toastr['error']("Please fill up the red marked fields.");
//         return false;
//     }
//     function unmarker(elem){
//         elem.on('click',function(){
//             $(this).closest("div").removeClass('has-error');
//         });
//     }
//     function sendError(item){
//         $(`[name=${item.name}`).closest("div").addClass('has-error');
//         return false;
//     }
// })(jQuery);
//
// jQuery.each( ["patch","put", "delete"], function( i, method ) {
//     jQuery[ method ] = function( url, data, callback, type ) {
//       if ( jQuery.isFunction( data ) ) {
//         type = type || callback;
//         callback = data;
//         data = undefined;
//       }
//
//       return jQuery.ajax({
//         url: url,
//         type: method,
//         dataType: type,
//         data: data,
//         success: callback
//       });
//     };
//   });
