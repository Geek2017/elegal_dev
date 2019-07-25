$.fn.dataTable.ext.errMode = 'throw';

toastr.options = {
    "closeButton": true,
    "debug": false,
    "progressBar": false,
    "preventDuplicates": true,
    "positionClass": "toast-top-center",
    "onclick": null,
    "showDuration": "400",
    "hideDuration": "1000",
    "timeOut": "2000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  }

$('.slicker').slick({
    accessibility:false,
    draggable:false,
    cssEase:'ease',
    easing:'linear',
    arrows:false
});


tblCase = $('#case').DataTable({
        autoWidth: false,
        ajax:{
            url:"create",
            data: {
                request:"case",
            }
        },
        columns: [
            {data: "number"},
            {data: "title"},
        ],
        columnDefs: [{
            targets: 2,
            data: null,
            render:function(row){
                return `<a class="btn-success btn btn-xs" onclick="getFee(${row.id})">View</a>` 
            }
        }]
    });

tblFee = $('#fee').DataTable({
    autoWidth: false,
    columns: [
        {data: "name"},
        {data: "charge_type"},
        {data: "amount"},
    ],
    columnDefs: [{
        targets:0,
        data:null,
        render:function(a,b,row){
        return `<div class="i-checks">
                    <label><input name="radio1" type="radio" value="transaction_fee_detail_id=${row.id}"><i></i> ${row.fee.name}</label>
                </div>`
        }
    },{
        targets: 3,
        data: null,
        render:function(a,b,row){
            if(row.chargeables.length){
                return `<a class="btn-success btn btn-xs" onclick="getChargeable(${row.id})">View</a>`;
            }else{
                return `<a  class="btn-primary btn btn-xs" onclick="addChargeableId(${row.id})" data-toggle="modal" data-target="#chargeableModal">Add</a>`;
            }
        }
    }]
});

tblChargeable = $('#chargeable').DataTable({
    autoWidth: false,
    columns: [
        {data: null},
        {data: "name"},
        {data: "description"},
        {data: "amount"}
    ],
    columnDefs: [{
        targets: 0,
        data: null,
        render: function(row){
            return `<div class="checkbox checkbox-info">
                        <input id="checkbox${row.id}" value="${row.id}" onchange="selectedHandler(this)" type="checkbox">
                        <label></label>
                    </div>`
        }
    }]
});

$('#fee, #chargeable').on('xhr.dt',function(e, settings, json, xhr){
    let slicker = $('.slicker');
    if(xhr.status === 200){
        slicker.slick('slickCurrentSlide') < 2 ? slicker.slick('slickNext'): null ;
    }else{
        toastr['error']("No records found!");
    }
});

let chargeableData;

function clearChargebles(){
    chargeableData = {'transaction_fee_detail_id':'','_token':$("#_token").attr('value')}
}

function addChargeableId(id){
    clearChargebles();
    chargeableData.transaction_fee_detail_id = id;
}

function sendChargeable(){
    $.post('/service-report', $.param(chargeableData) + '&' + $('.modal-content input').serialize()).fail(function(){
        toastr['error'](`Oops! something went wrong, call CHARLIE PUTH!`);}).done(function(){
            getChargeable(chargeableData.transaction_fee_detail_id);
            tblFee.ajax.reload();
            });
}

function getFee(id){
    tblFee.ajax.url(`create?request=fee&id=${id}`).load();
}

function getChargeable(id){
    tblChargeable.ajax.url(`create?request=chargeable&id=${id}`).load();
    addChargeableId(id);
}

function prev(){
    $('.slicker').slick('slickPrev');
}

function saveSR(){
    $.ajax({
        headers: {'X-CSRF-TOKEN': $("#_token").attr('value')},
        method: "PUT",
        url: "/service-report/test",
        data:$('input[name=radio1]:checked').val()
    }).done(response=>toastr['info'](`Service report has been saved`)).fail(response=>toastr['error'](`Please Select Fee Details`))
}

tblFee.on('draw',function(){
    $('.i-checks').iCheck({
        radioClass: 'iradio_square-green',
    });
});

let chargeables = [];

function selectedHandler(elem){
    if(elem.id == 'checkboxMain'){
        $('#chargeable .checkbox-info input').prop('checked', elem.checked);
        if(elem.checked){
            chargeables = [];
            for (i = 0; i < tblChargeable.data().length;i++){
                chargeables.push(tblChargeable.data()[i].id);
            }
        }else{
            chargeables = [];
        }
    }else if(elem.checked){
        chargeables.push(elem.value);
    }else {
        chargeables = chargeables.filter(item=>item != elem.value);
    }
    
    if (chargeables.length){
        $('#chargeable a.btn-danger.btn.btn-xs').show();
    }else{
        $('#chargeable a.btn-danger.btn.btn-xs').hide();
    }

    if(chargeables.length == tblChargeable.data().length){
        $('#checkboxMain').prop('checked', true);
    }else{
        $('#checkboxMain').prop('checked', false);
    }

}

function delChargeables(){
    swal({
        title: "Delete selected?",
        text: "This process is irreversible!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    }, function () {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $("#_token").attr('value')},
              method: "DELETE",
              url: "/service-report/destroy",
            data: {"id":chargeables}
            }).done(()=>{
                swal("Deleted!", "Data has been removed.", "success");
                tblChargeable.ajax.reload(()=>{
                    if(!tblChargeable.data().length){
                        tblFee.ajax.reload();
                        prev();
                    }
                });
            })
        
    });
}