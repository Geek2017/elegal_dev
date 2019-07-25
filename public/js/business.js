  toastr.options = {
    "closeButton": false,
    "debug": false,
    "progressBar": false,
    "preventDuplicates": true,
    "positionClass": "toast-top-right",
    "onclick": null,
    "showDuration": "100",
    "hideDuration": "1000",
    "timeOut": "3000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  }

let clientId, businessId, billing, tblRow;
$.fn.dataTable.ext.errMode = 'throw';

tblBusiness = $('#business').DataTable({
    autoWidth: true,
    order:[1],
    columns: [
        {data: null, orderable: false},
        {data: "name"},
    ],    
    columnDefs: [{
        targets: 0,
        width: "5%",
        render: function(row,type,val,meta){
            
            return `<div class="i-checks">
                        <label><input name="radio1" type="radio" id="${row.id}" ${row.id == billing && "checked"}><i></i></label>
                    </div>`
        }
    },{
        targets: 2,
        width: "40%",
        data: function(row,type,val,meta){
            return `${row.street_number} ${row.route}, ${row.neighborhood}, ${row.locality}, ${row.administrative_area_level_1}, ${row.country} `
        }
    },{
        targets: 3,
        width: "5%",
        data: function(row,type,val,meta){
            return `<button class="btn btn-primary btn-xs" onclick="viewBusiness(this)">View</button>`
        }
    },{
        targets: 4,
        width: "5%",
        data: function(row,type,val,meta){
            return `<div class="checkbox checkbox-danger no-padding">
                        <input id="${row.id}" type="checkbox" onchange="">
                        <label></label>
                    </div>`
        }
    }]
});

function setNload(id){
    clientId = id;
    tblBusiness.ajax.url(`/business?client=${id}`).load();
}

function getBusiness(id){
    id && setNload(id);
}

function setBilling(id){
    billing = id;
}

function confirm(){
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover from this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
        }, function () {
            zapBusiness();
            swal.close();
        });

}

function zapBusiness(){
    if(clientId){
        let businessId = selected().map(function(){return this.id}).toArray();
        $.delete('/business/destroy',{businessId: businessId})
            .done(removeRow());
    }else{
        removeRow();
    }

}

function removeRow(){
    tblBusiness.rows(selected().closest('tr')).remove().draw(false);
}

function constructClient(newClient){
    let client = getClientFields();
    let billing = $('input[type=radio]:checked').attr('id');

    if(newClient && !checkAddress()){
        return;
    }

    if(!billing){
        swal({
            title: "Please Select Billing Address",
            type: "error"
        });
        return;
    }

    if(!client){
        return;
    }

    client['billing'] = billing;
    dispatchClient(newClient, client, billing);
 }

function dispatchClient(newClient, client, billing){
    if(newClient){
        $.post('/clients',{"business":business ,"client":client })
            .done(function(){location = '/clients'});
    }else{
        $.put('/clients/' + clientId,{client:client})
            .done(function(){window.location = from || '/clients'});
    }
}

function getClientFields(){
    return $('.row .col-lg-12:first input').serializeAssoc(true);
}

function checkAddress(){
    return tblBusiness.data().length || swal({
        title: "Business details are empty.",
        text: "Please add a business detail.",
        type: "warning"
    });
}

function saveAddress(){
   let value = $('.modal-body input, .modal-body select').serializeAssoc(true,['route','street_number']);
   if(value){
       if(clientId){
        value['client_id'] = clientId;
        $.post('/business',{"businessId":businessId,"business":value});
        tblBusiness.ajax.reload();
       }else{
        processBusinessStack(value);
       }
        $('#businessModal').modal('hide');
   } 
}

function processBusinessStack(value){
    if(tblRow){
        tblBusiness.row(tblRow).data(value).draw(false);
    }else{
        tblBusiness.row.add(value).draw(false);
    }
    tblRow = null;
}

function clearFields(){
    $('.modal-body input, .modal-body select').val('');
}

function selectAll(checked){
    $('#business input[type=checkbox').prop('checked', checked);
}

function selected(){
    selection = $('#business input[type=checkbox]:checked').not("#selectAll");
    if(selection.length){
        switchBtnHead(true);
        return selection;
    }else{
        switchBtnHead(false);
        return 0;
    }
}

function switchBtnHead(state = true){
    $('#business button.btn-danger').toggle(state);
    $('#business th span').toggle(!state);
}
function viewBusiness(elem){
    tblRow = elem.closest('tr');
    let rowData = tblBusiness.row(tblRow).data();
    $('#businessModal').modal('show');
    businessId = rowData.id;
    for(item in rowData){
        $(`input[name=${item}], select[name=${item}]`).val(rowData[item]);
    }
    console.log(rowData);
}

$('#business').on('change','input[type=checkbox]',function(){
    let pageItems = tblBusiness.page.info().end - tblBusiness.page.info().start;
    selected().length == pageItems ? selectAll(1) : $('#selectAll').prop('checked', false);
});

$('#businessModal').on('hidden.bs.modal', function () {
    clearFields();
    $('.modal-body input, .modal-body select').closest('div').removeClass('has-error');
  });
 
tblBusiness.on('draw',function(){
    selected();
    if(!tblBusiness.data().length){
        switchBtnHead(false);
    }
    $('.i-checks').iCheck({
        radioClass: 'iradio_square-green',
    });
});
