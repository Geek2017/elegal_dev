<div class="row">
    <div class="col-lg-12">

            
    <div class="modal inmodal" id="myModal5" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content animated fadeInUp">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Client Info</h4>
                        <small class="font-bold">Add/Edit Client Info</small>
                    </div>
                            <div class="tabs-container no-margins">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true">Personal Info</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">Business Info</a></li>
                                </ul>
                                <div class="tab-content" >
                                    <input id="client_id" name="client_id" type="text" hidden>
                                    <div id="tab-1" class="tab-pane active">
                                        <div class="panel-body">
                                            <div class=" form-group">
                                            <div class="col-lg-6">
                                                <div class="input-group m-b"><span class="input-group-addon bg-muted" >First Name</span>
                                                    <input name="fname" type="text" class="form-control" required>
                                                </div>
                                                <div class="input-group m-b"><span class="input-group-addon bg-muted">Middle Name</span>
                                                    <input name="mname" type="text" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="input-group m-b"><span class="input-group-addon bg-muted">Last Name</span>
                                                    <input name="lname" type="text" class="form-control" required>
                                                </div>
                                                <div class="input-group m-b">
                                                    <span class="input-group-addon bg-muted">Email</span>
                                                    <input name="email" type="text" class="form-control" required>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div id="tab-2" class="tab-pane">
                                        <div class="panel-body">
                                        <div class=" col-lg-7">
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Search</span>
                                                <input id="acInput1" placeholder="Enter your address" onfocus="" type="text" class="form-control autocomplete" autocomplete="off">
                                            </div>
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Business Name</span>
                                            <input type="text"  name="name" class="form-control" >
                                            </div>
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Street Address</span>
                                                <div class="row">
                                                <div class="col-lg-6"><input name="street_number" type="text" class="form-control street_number_acInput1" ></div>
                                                    <div class="rmlt col-lg-6"><input name="route" type="text" class="form-control route_acInput1" ></div>
                                                </div>
                                            </div>
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Barangay/Town</span>
                                                <input type="text"  name="neighborhood" class="form-control neighborhood_acInput1 administrative_area_level_5_acInput1" >
                                            </div>
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Municipality/City</span>
                                                <input name="locality" type="text" class="form-control locality_acInput1" >
                                            </div>
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Province/Conurbation</span>
                                                <input name="administrative_area_level_1" type="text" class="form-control administrative_area_level_1_acInput1 administrative_area_level_2_acInput1" >
                                            </div>
                                            
                                            <div class="row">
                                                    <div class="col-lg-7">
                                                        <div class="input-group m-b">
                                                            <span class="input-group-addon bg-muted">Country</span>
                                                            <input name="country"  type="text" class="form-control  country_acInput1" ></div>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <div class="input-group m-b">
                                                            <span class="input-group-addon bg-muted">Zip Code</span>
                                                            <input name="postal_code" type="text" class="form-control  postal_code_acInput1" ></div>
                                                    </div>
                                            </div>
                                            <hr>
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Contact No.</span>
                                                <input name="contact" type="text" class="form-control">
                                            </div>
            
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">Business Type</span>
                                                <select name="business_nature" type="text" class="form-control">
                                                    <option disabled selected></option>
                                                    <option value="1">Corporate</option>
                                                    <option value="2">Infrastructure</option>
                                                    <option value="3">advertising</option>
                                                    <option value="4">Media broadcasting</option>
                                                    <option value="5">Contraction</option>
                                                    <option value="6">Consulting</option>
                                                    <option value="7">IT/Telco</option>
                                                    <option value="8">transportation</option>
                                                    <option value="9">Logistics</option>
                                                    <option value="10">Finance</option>
                                                    <option value="11">entertainment</option>
                                                    <option value="12">Clothing</option>
                                                    <option value="13">Cosmetics</option>
                                                    <option value="14">Agriculture</option>
                                                    <option value="15">Hospitality/Tourism</option>
                                                    <option value="16">NGO</option>
                                                    <option value="17">LGU</option>
                                                    <option value="18">Others</option>
                                                </select>
                                            </div>
                                            <div class="input-group m-b">
                                                <span class="input-group-addon bg-muted">O.I.C.</span>
                                                <input name="oic" type="text" class="form-control" >
                                            </div>
                                            <div class="pull-right">
                                                <button name="id" type="button" class="btn btn-primary" onclick="updateBus('','post')">Add</button>
                                            </div>
                                        </div>
                                        <div class="col-lg-5 no-padding">
                                            <table id="clientBus" class="table table-striped table-hover" >
                                                <thead>
                                                    <tr>
                                                        <th>Bus. Name</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                        <button type="submit" onclick="updateData()"  class="btn btn-primary">Save</button>
                    </div>
               
                </div>
    
            </div>
        </div>
    </div>
</div>