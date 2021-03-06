@extends('frontend.layouts.app')

@section('after-styles')

{{ Html::style('css/dashboard.css') }}

<style type="text/css">
    ul.list-group{
        list-style: none;
        padding: 0;
    }
    li.list-group-item a{
        display: block;
        width: 100%;
        height: 100%;
        text-decoration: none;
    }
</style>

@endsection

@section('content')
    <div class="row">

        <div class="col-xs-12">

            <div class="row">
                <div class="col-md-4 col-md-push-8">
                    <div class="row">
                        <div class="panel panel-default">
                            
                            <div class="panel-heading">
                                <h5>Order List</h5>
                            </div><!--panel-heading-->

                            <div class="panel-body">
                                <input type="hidden" id="prod_id">
                                <input type="hidden" id="prod_name">
                                <input type="hidden" id="prod_price">
                                <input type="hidden" id="prod_size">

                                <div class="row">
                                    <div id="table-wrapper">
                                        <div id="table-scroll">
                                            <table class="table table-responsive" id="order_list">
                                                <thead>
                                                    <th style="width:55%;text-align:left"><span class="text">ITEM</span></th>
                                                    <th style="width:15%;text-align:left"><span class="text">QTY</span></th>
                                                    <th style="width:30%;text-align:left"><span class="text">PRICE</span></th>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                

                                <div class="col-md-12">
                                    <h4 class="col-md-8">TOTAL</h4>
                                    <h4 class="col-md-4" id="total_amount">0.00</h4>
                                </div>

                                <div id="options">
                                    <button class="btn btn-default" id="btn-clear"><i class="fa fa-refresh"></i> CLEAR</button>
                                    <button class="btn btn-danger" id="btn-remove"><i class="fa fa-trash-o"></i> REMOVE</button>
                                    <button class="btn btn-success" id="btn-save"><i class="fa fa-save"></i> SAVE</button>
                                </div>

                            </div><!--panel-body-->

                        </div><!--panel-->

                        <div class="panel panel-default">
                            <div class="panel-heading">&nbsp;</div>
                            <div class="panel-body">
                                <button class="btn btn-default" id="btn-tables"><i class="fa fa-table"></i> TABLES</button>
                            </div>
                        </div>
                    </div>

                </div><!--col-md-4-->

                <div class="col-md-8 col-md-pull-4">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4>PRODUCT LIST</h4>
                                </div><!--panel-heading-->

                                <div class="panel-body">
                                    @if(count($products))
                                    @foreach($products as $product)

                                        <a class="product-box" id="{{ $product->id }}" data-code="{{ $product->code }}" onclick="product_click(this)">
                                            <div class="product-title">{{ $product->name }}</div>

                                            <div class="product-body">
                                                <img src="{{ url('img/product').'/'.$product->image }}">
                                            </div>
                                        </a>
                                        
                                    @endforeach
                                    @else
                                    <p>No Product.</p>
                                    @endif
                                </div><!--panel-body-->
                            </div><!--panel-->
                        </div><!--col-xs-12-->
                    </div><!--row-->
                </div>
            </div><!--row-->


        </div><!-- col-md-10 -->

    </div><!-- row -->

    <!-- Modal -->
    <div id="productModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="ingredient-title"></h4>
                </div>
                <div class="modal-body">
                    <table class="table" id="ingredient_list">
                        <thead>
                            <th>INGREDIENT NAME</th>
                            <th>STOCKS</th>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-md-8">
                            <h5>DRINK SIZE</h5>
                            <ul class="list-group col-lg-8" id="cup_sizes">
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <label>Quantity</label>
                            <input type="number" class="form-control" id="qty" value="1">
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btn_addOrder">Add Order</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->

    <!-- Modal -->
    <div id="saveModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12" id="payment">
                            <div class="form-group col-lg-6">
                                <label for="order_type">Order Type</label>
                                <select class="form-control" id="order_type">
                                    <option>Dine-in</option>
                                    <option>Take Out</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-6" id="panel_table">
                                <label for="table_no">Table</label>
                                <select class="form-control" id="table">
                                    
                                </select>
                            </div>                         
                            <div class="form-group col-lg-12" id="panel_discount_type" hidden>
                                <label for="discount">Discount Type</label>
                                <select class="form-control" id="discount_type" onchange="discount_change(this)">
                                    <option value="0">None</option>
                                    @foreach($settings as $setting)
                                    <option value="{{ $setting->discount }}">{{ $setting->name }}</option>
                                    @endforeach
                                </select> 
                            </div>
                            <div class="form-group col-lg-6" id="panel_payable" hidden>
                                <label for="payable">Total Payable</label>
                                <input type="input" class="form-control" id="payable" value='0.00' readonly>
                            </div>
                            <div class="form-group col-lg-6" id="panel_discount" hidden>
                                <label for="discount">Discount</label>
                                <input type="input" class="form-control" id="discount" value='0.00' readonly>
                            </div>
                            <div class="form-group col-lg-12" id="panel_cash" hidden>
                                <label for="cash">Cash</label>
                                <input type="input" class="form-control" id="cash" value='0.00' onkeyup="change()" onfocus="this.value = ''" onblur="isFocus()" pattern="[0-9]">
                            </div>
                            <div class="form-group col-lg-12" id="panel_change" hidden>
                                <label for="change">Change</label>
                                <input type="input" class="form-control" id="change" value='0.00' readonly>
                            </div>  
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="notify" style="color:red" class="pull-left"></span>
                    <button type="button" class="btn btn-success" id="btn_submit">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->

     <!-- Modal -->
    <div id="tablesModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">TABLES</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="table_list">Select Table</label>
                        <select class="form-control" id="table_list">
                            
                        </select>
                    </div>
                    <hr>
                    <h4>Order List</h4>
                    <p>TRANSACTION NO#: <span id="table_order_transact"></p></span>

                    <table class="table" id="table_order_list">
                        <thead>
                            <th>PRODUCT</th>
                            <th>QTY/SIZE</th>
                            <th>PRICE</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3">No records found.</td>
                            </tr>
                        </tbody>
                    </table>
                    
                </div>
                <div class="modal-footer">
                    <p class="pull-left" style="font-size: 18px; font-weight: bold">TOTAL: <span id="table_order_total"></span></p>
                    <button class="btn btn-default" id="btn-charge">CHARGE BILL</button>
                    <button type="button" class="btn btn-success" id="btn_additional">Additional Order</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->

     <!-- Modal -->
    <div id="chargeModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Transaction No: <b id="charge_transaction_no"></b>&nbsp;</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="table-wrapper">
                                <div id="table-scroll">
                                    <table class="table table-responsive" id="charge_table">
                                        <thead>
                                            <th style="width:55%;text-align:left"><span class="text">ITEM</span></th>
                                            <th style="width:15%;text-align:left"><span class="text">QTY</span></th>
                                            <th style="width:30%;text-align:left"><span class="text">PRICE</span></th>
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
                    <h4 class="pull-left">TOTAL: <span id="charge_total">0.00</span></h4>
                    <button type="button" class="btn btn-success" id="btn_charge_payment">Proceed To Payment</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->

    <!-- Modal -->
    <div id="chargeSaveModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Transaction No#: <b class="modal-title"></b></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12" id="payment">                     
                            <div class="form-group col-lg-12">
                                <label for="discount">Discount Type</label>
                                <select class="form-control" id="discount_type" onchange="charge_discount_change(this)">
                                    <option value="0">None</option>
                                    @foreach($settings as $setting)
                                    <option value="{{ $setting->discount }}">{{ $setting->name }}</option>
                                    @endforeach
                                </select> 
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="payable">Total Payable</label>
                                <input type="input" class="form-control" id="payable" value='0.00' readonly>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="discount">Discount</label>
                                <input type="input" class="form-control" id="discount" value='0.00' readonly>
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="cash">Cash</label>
                                <input type="input" class="form-control" id="cash" value='0.00' onkeyup="charge_change()" onfocus="this.value = ''" onblur="isFocus()" pattern="[0-9]">
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="change">Change</label>
                                <input type="input" class="form-control" id="change" value='0.00' readonly>
                            </div>  
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="notify" style="color:red" class="pull-left"></span>
                    <button type="button" class="btn btn-success" id="btn_charge_submit">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->

     <!-- Modal -->
    <div id="printModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div id="official_receipt">
                        <h5>OFFICIAL RECEIPT</h5>
                        <div id="printable">
                            <p>Transaction No: <span class="pull-right" id="transaction_no">#00000000</span></p>
                            <p>Date: <span class="pull-right">{{ date('m-d-Y') }}</span></p>
                            <p>Cashier: <span class="pull-right">{{ Auth::user()->name }}</span></p>
                            <p>Order Type: <span class="pull-right" id="print_type"></span></p>
                            <p>Table #: <span class="pull-right" id="print_table"></span></p>
                            <hr>

                            <div style="min-height:150px">
                                <table id="items">
                                    <thead>
                                        <th style="width:25%">Qty</th>
                                        <th>Item(s)</th>
                                        <th style="width:25%">Total</th>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                            
                            <hr>
                            <p>Total Amount Due <span class="pull-right" id="print_total">0.00</span></p>
                            <p>Cash <span class="pull-right" id="print_cash">0.00</span></p>
                            <p>Change <span class="pull-right" id="print_change">0.00</span></p>
                            <p>Discount <span class="pull-right" id="print_discount">0.00</span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->
@endsection

@section('after-scripts')
    <script type="text/javascript">
        var order           = [];
        var order_list      = [];
        var total_amt       = 0;
        var flag            = false;
        var transaction_no  = '';
        var global_table    = 0;
        var dine_in         = false;

        function getIngredients(id){
            $.ajax({
                type: 'get',
                url : '{{ url("dashboard") }}/' + id + '/product',
                success: function(data){
                    var hasNoStock  = 0;
                    var product     = data['product'];
                    var prod_sizes  = data['product_size'];
                    var body        = $('#ingredient_list tbody');

                    $(body).find('tr').remove();
                    $('#cup_sizes').find('li').remove();        

                    for(var i = 0; i < prod_sizes.length; i++){
                        var size        = prod_sizes[i]['size'];
                        var price       = prod_sizes[i]['price'];
                        var ingredients = prod_sizes[i]['ingredients'];
                        var size_list   =  '<li class="list-group-item" onclick="product_size(this)">';
                            size_list   += '<a href="#" data-size="' + size + '">' + size + '<span class="pull-right">';
                            size_list   += price;
                            size_list   += '</span></a></li>';

                        for(var j = 0; j < ingredients.length; j++){
                            var name     = '';
                            var stock    = ingredients[j]['stock'];
                            var crit     = ingredients[j]['reorder_level'];
                            var quantity = ingredients[j]['quanity'];

                            if(ingredients[j]['supplier'] == 'Other')
                            {
                                name = ingredients[j]['other']['name'];
                            }
                            else if(ingredients[j]['supplier'] == 'Commissary Product')
                            {
                                name = ingredients[j]['commissary_product']['name'];
                            }
                            else if(ingredients[j]['supplier'] == 'DryGoods Material')
                            {
                                name = ingredients[j]['dry_good_inventory']['name'];
                            }
                            else
                            {
                                if(ingredients[j]['commissary_inventory']['supplier'] == 'Other')
                                    name = ingredients[j]['commissary_inventory']['other_inventory']['name'];
                                else
                                    name = ingredients[j]['commissary_inventory']['drygood_inventory']['name'];
                            }

                            var row      =  '<tr' + (stock == 0 || crit > stock ? ' style="background:#b10303;color:white"': '') + ' id="' + name + '">';
                                row      += '<td>' + name + '</td>';
                                row      += '<td>' + stock + '</td>';
                                row      += '</tr>';

                            if(stock == 0)
                                hasNoStock++;

                            var exist = $(body).find('tr#' + name);

                            //check for same ingredient name
                            if(exist.length == 0)
                                $(body).append(row);
                        }

                        $('#cup_sizes').append(size_list);
                        product_size($('#cup_sizes').find('li')[0]);
                    }

                    if(hasNoStock){
                        $('#btn_addOrder').attr('disabled', 'disabled');
                    } else {
                        $('#btn_addOrder').removeAttr('disabled');
                    }
                    
                    $('#productModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                },
                error: function(data){
                    // console.log('error');
                }
            });
        }        

        function product_click(e){
            var id   = $(e).attr('id');
            var code = $(e).attr('data-code');
            var name = $(e).find('.product-title').text();
            
            order[0] = id;
            order[1] = code;

            $('#ingredient-title').html(code);
            getIngredients(id);
        }

        function product_size(e){
            $('li.list-group-item').removeClass('product-active');
            $(e).addClass('product-active');
        }

        $('a').on('dragstart', function(){
            return false;
        });

        $('#btn_addOrder').on('click', function(){
            var product = {};
            var html    = '';
            var code_sz = '';
            var qty     = '';

            order[2] = $('.product-active').find('span').text();
            order[3] = $('#qty').val();
            order[4] = $('.product-active').find('a').attr('data-size');
            
            product['id']   = order[0];
            product['code'] = order[1];
            product['price']= order[2];
            product['qty']  = order[3];
            product['size'] = order[4];

            if(searchItem(product.id, product.size))
            {
                var row = $('#order_list').find('tr[data-id="' + product.id + '"], tr[data-size="'+ product.size +'"]');
                var cols = $(row).find('td');
                var temp = parseInt($($(cols)[1]).text()) + parseInt(product.qty);

                $($(cols)[1]).text(temp);
                $($(cols)[2]).text(temp * product.price);

                updateItem(product.id, product.size, temp);
            }
            else
            {
                //
                //append product to orderlist
                //
                order_list.push(product);

                //
                // check product size and increase price
               //
                if(product.size == 'Large' || product.size == 'Medium')
                {
                    product.price   = (parseFloat(product.price)).toFixed(2);
                    code_sz         = product.code  + ' ' + product.size;
                    qty             = product.qty;

                    product.price   = parseFloat(product.qty * product.price).toFixed(2);
                }
                else 
                {
                    code_sz         = product.code;
                    qty             = product.qty;
                    product.price   = (product.qty * product.price).toFixed(2);
                }
                //
                // add table row
                //
                html  = '<tr data-id="' + product.id + '" data-size="' + product.size + '" onclick="toggleActive(this)">';
                html  = html + '<td>' + code_sz + '</td><td>' + qty + '</td><td>' + product.price + '</td></tr>';
            }


            $('#total_amount').text(recompute());
            $('#qty').val(1);
            $('#order_list tbody').append(html);
            $('#productModal').modal('hide');
        });

        function searchItem(id, size)
        {
            index = order_list.findIndex(x => x.id == id && x.size == size);
            return index >= 0 ? true: false;
        }

        function updateItem(id, size, quantity) {
            index = order_list.findIndex(x => x.id == id && x.size == size);
            if(index != -1)
            {
                order_list[index].qty = quantity;
                order_list[index].price = quantity * order_list[index].price;
            }
        }

        function removeItem(id, size){
            index = order_list.findIndex(x => x.id == id && x.size == size);
            if(index != -1)
            {
                order_list.splice(index, 1);
                $('#total_amount').text(recompute());
                $('tr.selected').remove();
           }
        }

        function toggleActive(e){
            var has = $(e).hasClass('selected');
            if(has){
                $(e).removeClass('selected');
            } else {
                $(e).addClass('selected');
            }
        }

        $('#btn-remove').on('click', function(){
            if($('#order_list tbody tr.selected').length > 0)
            {
                // 
                // alert
                // 
                swal(
                    {
                      title: "Are you sure?",
                      text: "You want to remove item from order list?",
                      type: "warning",
                      showCancelButton: true,
                      confirmButtonColor: "#DD6B55",
                      confirmButtonText: "Remove Item",
                      closeOnConfirm: false
                    },
                    function(){
                        var rows = $('tr.selected');
                        for(var i = 0; i < rows.length; i++)
                        {
                            var id   = parseInt($(rows[i]).attr('data-id'));
                            var size = $(rows[i]).attr('data-size');
                            var fix  = $(rows[i]).attr('data-fixed');
                            /* if not fixed remove */
                            if(!fix)
                            {
                                removeItem(id, size);
                                swal("Removed!", "Item has been removed!", "success");
                            }
                            else
                            {
                                swal("Removed!", "Item can't be remove!", "warning");
                            }
                        }
                    }
                );
                // 
                // end alert
                // 
            }
        });

        $('#btn-clear').on('click', function(){
            if($('#order_list tbody tr').length > 0)
            {
                // 
                // alert
                // 
                swal(
                    {
                      title: "Are you sure?",
                      text: "You want to remove all item from order list?",
                      type: "warning",
                      showCancelButton: true,
                      confirmButtonColor: "#DD6B55",
                      confirmButtonText: "Remove All",
                      closeOnConfirm: false
                    },
                    function(){
                        clearAll();
                        swal("Removed!", "All item has been removed!", "success");
                    }
                );
                // 
                // end alert
                // 
            }
        });

        $('#btn-save').on('click', function() {
            $('#payable').val(recompute());
            if($('#order_list tbody tr').length > 0){
                get_available_table();

                if(global_table != 0)
                    $('#btn_submit').trigger('click');

                $('#saveModal').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                // $('#saveModal').find('.modal-dialog').css({'width': '80%','height':'80%'});
                // $('#saveModal').find('.modal-dialog').find('.modal-content').css('height','80%');
            }
        });

        $('#btn_submit').on('click', function(){
            var cash        = parseFloat($('#cash').val());
            var change      = parseFloat($('#change').val());
            var payable     = parseFloat($('#payable').val());
            var discount    = parseFloat($('#discount').val());
            var order_type  = $('#order_type').val();
            var table_no    = order_type == 'Take Out' ? 0 : $('#table').val();

            if(cash >= (payable - discount) && order_type == 'Take Out')
            {
               $('#btn_submit').attr('readonly','');

                $.ajax({
                    url: '{{  url("sale/save") }}',
                    type: 'POST',
                    data: {
                        orders      : JSON.stringify(order_list),
                        cash        : cash,
                        change      : change,
                        payable     : payable,
                        discount    : discount,
                        table       : table_no,
                        order_type  : order_type
                    },
                    success: function(data){
                        data = JSON.parse(data);

                        if(data.status == 'success')
                        {
                            $('#printModal').modal({
                                backdrop: 'static',
                                keyboard: false
                            });

                            $('#printModal').on('shown.bs.modal', function() {
                                $('#saveModal').modal('hide');

                                print_receipt(data);
                                window.print($('#official_receipt'));
                            });
                        }
                        else
                        {
                            swal('Order not save!','', 'error');
                            $('#btn_print').css('visibility', 'hidden');
                        }

                        $('#btn_submit').removeAttr('readonly');
                    },
                    error: function(data){
                        swal("Error Saving!", '', 'error');
                    }
                }); 
            }
            else if(order_type == 'Dine-in')
            {
                $('#btn_submit').attr('readonly','');
                if(global_table != 0)
                {
                    table_no   = global_table;
                    order_type = dine_in ? 'Dine-in' : order_type;
                }

                $.ajax({
                    url: '{{  url("sale/save") }}',
                    type: 'POST',
                    data: {
                        orders      : JSON.stringify(order_list),
                        cash        : cash,
                        change      : change,
                        payable     : payable,
                        discount    : discount,
                        table       : table_no,
                        order_type  : order_type,
                        transaction_no: transaction_no
                    },
                    success: function(data){
                        data = JSON.parse(data);

                        if(data.status == 'success')
                        {
                            flag = true;
                            $('#saveModal').modal('hide');
                            swal("Order has been saved!", '', 'success');
                        }
                        else
                        {
                            swal('Order not save!','', 'error');
                            $('#btn_print').css('visibility', 'hidden');
                        }

                        $('#btn_submit').removeAttr('readonly');
                    },
                    error: function(data){
                        swal("Error Saving!", '', 'error');
                    }
                });
            }
            else 
            {
                $('#notify').text('Input Cash Amount.')
            }//end statement            
        });        

        $('#saveModal').on('hidden.bs.modal', function(){
            if(flag)
            {
                $('#order_type').val('Dine-in');
                $('#panel_discount_type').hide();
                $('#panel_payable').hide();
                $('#panel_cash').hide();
                $('#panel_change').hide();
                $('#panel_discount').hide();
                $('#panel_discount_type').hide();
                $('#panel_table').show();
                $('#btn_submit').css('visibility', 'visible');
                $('#btn_submit').text('Submit');
                clearAll();
            }

            $('#payable').val('0.00');
            $('#cash').val('0.00');
            $('#change').val('0.00');
            // $('#official_receipt').hide();
            // $('#payment').show();
        });

        $('#printModal').on('hidden.bs.modal', function() {
            swal('Order has been saved!','', 'success');
        });

        $('#chargeModal').on('hidden.bs.modal', function() {
            order_list.splice(0, order_list.length);
        });

        function recompute(){
            total_amt = 0;
            for(var i = 0; i < order_list.length; i++)
            {
                total_amt += parseFloat(order_list[i].price);
            }
            return total_amt.toFixed(2);
        }

        function clearAll(){
            var count = 0;
            flag = false;

            if(transaction_no.length == 0)
            {
                $('#total_amount').text('0.00');
                total_amt =  0;
            }
            
            $('#customer_table').val('1');
            var rows = $('#order_list tbody').find('tr');

            for(var i = 0; i < rows.length; i++)
            {
                var id   = $(rows[i]).attr('data-id');
                var fix  = $(rows[i]).attr('data-fixed');
                var size = $(rows[i]).attr('data-size');

                if(typeof(fix) == 'undefined')
                {
                    $(rows[i]).remove();
                    removeItem(id, size);
                }
                else
                    count++;
            }
            /* set transaction no value */
            if(count == 0)
            {
                transaction_no = '';
                global_table   = 0;
                dine_in        = false;
            }
        }

        function change(){
            var payable = recompute();
            var discount = $('#discount').val();
            var cash    = $('#cash').val();
            var change  = cash - (payable - discount);

            if(change < 0 || change == undefined || isNaN(change)){
                change = 0;
            }

            $('#change').val(change.toFixed(2));
        }

        function charge_change(){
            var charge = $('#chargeSaveModal');
            var payable = $(charge).find('#payable').val();
            var discount = $(charge).find('#discount').val();
            var cash    = $(charge).find('#cash').val();
            var change  = cash - (payable - discount);

            if(change < 0 || change == undefined || isNaN(change)){
                change = 0;
            }

            $(charge).find('#change').val(change.toFixed(2));
        }

        function isFocus(){
            var cash = parseInt($('#cash').val());
            var val  = 0;

            if(cash > 0){
                val = cash;
            }

            $('#cash').val(val.toFixed(2));
        }

        function discount_change(e){
            if($(e).text() != 'None')
            {
                var percent = $(e).val();
                var payable  = $('#payable').val();
                var total    = payable * (percent / 100);

                $('#discount').val(total.toFixed(2));

                change();
            }
            else
            {
                $('#discount').val('0.00');
            }
        }

        function charge_discount_change(e){
            if($(e).text() != 'None')
            {
                var charge = $('#chargeSaveModal');
                var percent = $(e).val();
                var payable  = $(charge).find('#payable').val();
                var total    = payable * (percent / 100);

                $(charge).find('#discount').val(total.toFixed(2));

                charge_change();
            }
            else
            {
                $(charge).find('#discount').val('0.00');
            }
        }

        $('#order_type').on('change', function(){
            if(this.value == 'Dine-in')
            {
                $('#panel_discount_type').hide();
                $('#panel_payable').hide();
                $('#panel_cash').hide();
                $('#panel_change').hide();
                $('#panel_discount').hide();
                $('#panel_discount_type').hide();
                $('#panel_table').show();
                $('#btn_submit').text('Submit');
            }
            else
            {
                $('#panel_discount_type').show();
                $('#panel_payable').show();
                $('#panel_cash').show();
                $('#panel_change').show();
                $('#panel_discount').show();
                $('#panel_discount_type').show();
                $('#panel_table').hide();
                $('#btn_submit').text('Charge');
            }
        });

        $('#btn-tables').on('click', function() {
            $.ajax({
                type: 'GET',
                url: '{{ url("sale/unpaid") }}',
                success: function(data) {
                    $('#table_list').find('option').remove();
                    var options = '';

                    for(i = 0; i < data.length; i++)
                    {
                        options += '<option>' + data[i].table_no + '</option>';
                    }

                    $('#table_list').append(options);

                    if(data.length > 0)
                    {
                        table_order($('#table_list').val());
                    }
                }
            });

            $('#tablesModal').modal({
                backdrop: 'static',
                keyboard: false
            })
        });

        $('#btn_additional').on('click', function() {
            transaction_no  = $('#table_order_transact').text();
            global_table    = $('#table_list').val();
            dine_in         = true;

            $.ajax({
                type: 'GET',
                url : '{{ url("sale/get_order_list") }}/' + transaction_no,
                success: function(data) {
                    var html = '';
                    $('#order_list tbody').find('tr').remove();

                    for(var i = 0; i < Object.keys(data.order_list).length; i++)
                    {
                        var product = {};
                        var order   = data.order_list[i];

                        product['id']   = order.product.id;
                        product['code'] = order.product.code;
                        product['price']= order.product_size.price;
                        product['qty']  = order.quantity;
                        product['size'] = order.product_size.size;

                        //
                        //append product to orderlist
                        //
                        order_list.push(product);
                        
                        //
                        // check product size and increase price
                       //
                        if(product.size == 'Large' || product.size == 'Medium')
                        {
                            product.price   = (parseFloat(product.price)).toFixed(2);
                            code_sz         = product.code  + ' ' + product.size;
                            qty             = product.qty;
                            product.price   = parseFloat(product.qty * product.price).toFixed(2);
                        }
                        else 
                        {
                            code_sz         = product.code;
                            qty             = product.qty;
                            product.price   = (product.qty * product.price).toFixed(2);
                        }

                        //
                        // add table row
                        //
                        html  = '<tr data-id="' + product.id + '" data-size="' + product.size + '" onclick="toggleActive(this)" data-fixed="1">';
                        html  = html + '<td>' + code_sz + '</td><td>' + qty + '</td><td>' + product.price + '</td></tr>';
                    }
                    
                    $('#total_amount').text(recompute());
                    $('#order_list tbody').append(html);
                    $('#tablesModal').modal('hide');
                }
            });
        });

        $('#btn-charge').on('click', function() {
            var transact_no = $('#table_order_transact').text();
            order_list.splice(0, order_list.length);
            clearAll();

            $.ajax({
                type: 'GET',
                url : '{{ url("sale/get_order_list") }}/' + transact_no,
                success: function(data) {
                    var html = '';
                    $('#charge_table tbody').find('tr').remove();
                    $('#charge_transaction_no').text(data.transaction_no);
                    $('#charge_total').text(parseFloat(data.payable).toFixed(2));

                    for(var i = 0; i < Object.keys(data.order_list).length; i++)
                    {
                        var product = {};
                        var order   = data.order_list[i];

                        product['id']   = order.product.id;
                        product['code'] = order.product.code;
                        product['price']= order.product_size.price;
                        product['qty']  = order.quantity;
                        product['size'] = order.product_size.size;

                        //
                        //append product to orderlist
                        //
                        order_list.push(product);
                        
                        //
                        // check product size and increase price
                       //
                        if(product.size == 'Large' || product.size == 'Medium')
                        {
                            product.price   = (parseFloat(product.price)).toFixed(2);
                            code_sz         = product.code  + ' ' + product.size;
                            qty             = product.qty;
                            product.price   = parseFloat(product.qty * product.price).toFixed(2);
                        }
                        else 
                        {
                            code_sz         = product.code;
                            qty             = product.qty;
                            product.price   = (product.qty * product.price).toFixed(2);
                        }

                        //
                        // add table row
                        //
                        html  = '<tr data-id="' + product.id + '" data-size="' + product.size + '" onclick="toggleActive(this)" data-fixed="1">';
                        html  = html + '<td>' + code_sz + '</td><td>' + qty + '</td><td>' + product.price + '</td></tr>';
                    }
                    
                    $('#charge_table tbody').append(html);
                    $('#tablesModal').modal('hide');
                }
            });

            $('#chargeModal').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        $('#btn_charge_submit').on('click', function() {
            var charge      = $('#chargeSaveModal');
            var cash        = parseFloat($(charge).find('#cash').val());
            var change      = parseFloat($(charge).find('#change').val());
            var payable     = parseFloat($(charge).find('#payable').val());
            var discount    = parseFloat($(charge).find('#discount').val());
            var transact    = $(charge).find('.modal-title').text();

            if(cash >= (payable - discount))
            {
               $('#btn_charge_submit').attr('readonly','');

                $.ajax({
                    url: '{{  url("sale/charge_save") }}',
                    type: 'POST',
                    data: {
                        transaction_no: transact,
                        cash        : cash,
                        change      : change,
                        payable     : payable,
                        discount    : discount
                    },
                    success: function(data){
                        data = JSON.parse(data);

                        if(data.status == 'success')
                        {
                            $('#printModal').modal({
                                backdrop: 'static',
                                keyboard: false
                            });

                            $('#printModal').on('shown.bs.modal', function() {
                                $('#saveModal').modal('hide');
                                $('#chargeSaveModal').modal('hide');

                                print_receipt(data);
                                window.print($('#official_receipt'));
                            });
                        }
                        else
                        {
                            swal('Order not save!','', 'error');
                            $('#btn_print').css('visibility', 'hidden');
                        }

                        $('#btn_charge_submit').removeAttr('readonly');
                    },
                    error: function(data){
                        swal("Error Saving!", '', 'error');
                    }
                }); 
            }
        });

        $('#table_list').on('change', function() {
            table_order($('#table_list').val());
        });

        function table_order(val)
        {
            $.ajax({
                type: 'GET',
                url: '{{ url("sale/order") }}/' + val,
                success: function(data) {
                    var rows = '';
                    data = JSON.parse(data);
                    var _order  =  data.order;
                    var _order_list = _order.order_list;

                    $('#table_order_transact').text(_order.transaction_no);
                    $('#table_order_total').text(_order.payable);
                    $('#table_order_list tbody').find('tr').remove();

                    for(var i = 0; i < _order_list.length; i++)
                    {
                        rows += '<tr>';
                        rows += '<td>' + _order_list[0].product.name + '</td>';
                        rows += '<td>' + _order_list[0].quantity + '/' + _order_list[0].product_size.size + '</td>';
                        rows += '<td>' + _order_list[0].product_size.price + '</td>';
                        rows += '</tr>';
                    }

                    $('#table_order_list tbody').append(rows);
                }
            });
        }

        $('#btn_charge_payment').on('click', function() {
            var charge_total = parseFloat($('#charge_total').text()).toFixed(2);
            var transact     = $('#charge_transaction_no').text();

            $('#chargeSaveModal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#chargeSaveModal').on('shown.bs.modal', function() {
                $('#chargeModal').modal('hide');
                $(this).find('.modal-title').text(transact);
                $(this).find('#payable').val(charge_total);
            });
        });

        function get_available_table()
        {
            $.ajax({
                type: 'GET',
                url: '{{ url("sale/available_table") }}',
                success: function(data) {
                    var options = '';
                    $('#table').find('option').remove();

                    for(var i = 0; i < data.length; i++)
                    {
                        options += '<option>' + data[i] + '</option>';
                    }

                    $('#table').append(options);
                }
            });
        }

        function print_receipt(data)
        {
            var list = '';
            var _order_list = data.order.order_list;
            flag = true;

            $('#notify').text('')
            $('#items tbody').find('tr').remove();


            for(var i = 0; i < Object.keys(_order_list).length; i++)
            {
                var code  = _order_list[i].product.code;
                var qty   = _order_list[i].quantity;
                var price = _order_list[i].price;
                var size  = _order_list[i].product_size.size;
                list += '<tr>';


                if(qty > 1)
                {
                    list += '<td>' + qty + '</td>';
                    list += '<td>' + code + ' ' + (size == 'Small' ? '': size) + ' @ ' + (price / qty) + '</td>';
                }
                else
                {
                    list += '<td>' + qty + '</td>';
                    list += '<td>' + code + ' ' + (size == 'Small' ? '': size) + '</td>';
                }

                list += '<td>' + price + '</td>';
                list += '</tr>';
            }


            $('#transaction_no').text('#' + data.order.transaction_no);
            $('#print_total').text(parseFloat(data.order.payable).toFixed(2));
            $('#print_cash').text(parseFloat(data.order.cash).toFixed(2));
            $('#print_change').text(parseFloat(data.order.change).toFixed(2));
            $('#print_discount').text(parseFloat(data.order.change).toFixed(2));
            $('#print_type').text(data.order.type);
            $('#print_table').text(data.order.type == 'Take Out' ? 'N/A' : data.order.table_no);
            $('#items tbody').append(list);
            // $('#payment').hide();
            // $('#official_receipt').show();
            $('#btn_print').css('visibility', 'visible');
            $('#btn_submit').css('visibility', 'hidden');
        }
    </script>
@endsection