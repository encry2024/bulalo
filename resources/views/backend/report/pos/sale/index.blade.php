@extends ('backend.layouts.app')

@section ('title', 'Sales Report')

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
    {{ Html::style('https://cdn.datatables.net/buttons/1.4.0/css/buttons.dataTables.min.css') }}
@endsection

@section('page-header')
    <h1>Sales Report</h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Report List</h3>

            <div class="box-tools pull-right">
                <button class="btn btn-warning btn-sm" onClick="$('#daily_log_table').tableExport({type:'excel',escape:'false'});"><i class="fa fa-bars"></i> Export Table Data</button>
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="row">
                {{ Form::open(['route' => 'admin.report.pos.sale.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post']) }}
                    <div class="col-lg-12">
                        <div class="form-group col-lg-2">
                            <label>From</label>
                            <input class="form-control" type="text" name="from" id="from" readonly required value="<?php echo $from; ?>">
                            <input class="form-control" type="text" name="time_from" id="time_from" readonly required value="<?php echo $time_from; ?>">                          
                        </div>

                        <div class="form-group col-lg-2">
                            <label>To</label>
                            <input class="form-control" type="text" name="to" id="to" readonly required value="<?php echo $to; ?>">
                            <input class="form-control" type="text" name="time_to" id="time_to" readonly required value="<?php echo $time_to; ?>">
                        </div>

                        <div class="form-group col-lg-2">
                            <label>&nbsp;</label>

                            <div class="input-group">
                                <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                                <span>
                                    <i class="fa fa-calendar"></i> Select Date
                                </span>
                                <i class="fa fa-caret-down"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary" type="submit" style="margin-top: 25px"><i class="fa fa-calendar"></i> Search Date</button>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
            
            <div class="table-responsive">
                <table id="datatable" class="table table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>TRANSACTION NO</th>
                        <th>QUANTITY/SIZE</th>
                        <th>TOTAL PRICE</th>
                        <th>SOLD DATE</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->transaction_no }}</td>
                                <td>
                                    <?php
                                        $lists  = $order->order_list;
                                        $size_sm= 0;
                                        $size_md= 0;
                                        $size_lg= 0;
                                        $size   = '';

                                        foreach ($lists as $list) {
                                            $product = $list->product_size;

                                            if($product->size == 'Small')
                                                $size_sm += $list->quantity;
                                            elseif($product->size == 'Medium')
                                                $size_md += $list->quantity;
                                            else
                                                $size_lg += $list->quantity;
                                        }

                                        if($size_sm > 0){
                                            $size = $size_sm.' Small';
                                        }

                                        if($size_md > 0){
                                            $size .= $size_sm > 0 ? ' / ': '';
                                            $size .= $size_md.' Medium';
                                        }

                                        if($size_lg > 0){
                                            $size .= $size_md > 0 ? ' / ': '';
                                            $size .= $size_lg.' Large';
                                        }
                                        
                                        echo $size;
                                    ?> 
                                </td>
                                <td>
                                    <?php

                                        $lists  = $order->order_list;
                                        $total  = $lists->sum('price');

                                        echo number_format($total, 2);

                                    ?>
                                </td>
                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box-->


@endsection

@section('after-scripts')
    {{ Html::script('js/tableExport.js')}}
    {{ Html::script('js/jquery.base64.js')}}
    {{ Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") }}
    {{ Html::script("https://cdn.datatables.net/buttons/1.4.0/js/dataTables.buttons.min.js") }}
    {{ Html::script("https://cdn.datatables.net/plug-ins/1.10.16/filtering/row-based/range_dates.js") }}
    {{ Html::script("https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js") }}
    {{ Html::script("https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js")}}
    {{ Html::script("https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js") }}
    {{ Html::script("https://cdn.datatables.net/buttons/1.4.0/js/buttons.html5.min.js") }}
    <script>
        $(document).ready(function() {

            $('#datepicker_from').datepicker({'format' : 'yyyy-mm-dd' });
            $('#datepicker_to').datepicker({'format' : 'yyyy-mm-dd' });

        });

        

        // $(function() {
        //     table = $('#datatable').DataTable({
        //         dom: 'Bfrtip',
        //         buttons: [
        //             'copyHtml5',
        //             'excelHtml5',
        //             'csvHtml5',
        //             'pdfHtml5'
        //         ],
        //         displayLength:100,
        //         order: [1, 'asc']
        //     });
        // });
            

    </script>
@endsection
