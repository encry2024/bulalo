@extends ('backend.layouts.app')

@section ('title', 'Inventory Management | Add Inventory')

@section('after-styles')
    {{ Html::style('https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css') }}
    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.css') }}
    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css') }}
@endsection

@section('page-header')
    <h1>
        Dry Goods Inventory Management <small>Add Inventory</small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => 'admin.dry_good.inventory.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post']) }}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Add Inventory</h3>

                <div class="box-tools pull-right">
                    @include('backend.dry_good.inventory.includes.partials.inventory-header-buttons')
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">
                    {{ Form::label('name', 'Item Name', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('name', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Item Name']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('reorder_level', 'Critical Level', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-4">
                        {{ Form::text('reorder_level', 0, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required']) }}
                    </div>

                    {{ Form::label('category_id', 'Category', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-4">
                        {{ Form::select('category_id', $categories, old('category_id'), ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required']) }}
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('physical_quantity', 'Physical Quantity', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-4">
                        <select class="form-control" name="physical_quantity" id="physical_quantity">
                            <option>Mass</option>
                            <option>Volume</option>
                            <option>Other</option>
                        </select>
                    </div>

                    {{ Form::label('unit_type', 'Unit Type', ['class' => 'col-lg-2 control-label']) }}

                   <div class="col-lg-4">
                        <select class="form-control" name="unit_type" id="unit_type">
                            <option value="g">Gram</option>
                            <option value="kg">Kilogram</option>
                            <option value="ton">Ton</option>
                            <option value="lbs">Pound</option>
                            <option value="oz">Ounce</option>
                        </select>
                    </div>
                </div>


            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-info">
            <div class="box-body">
                <div class="pull-left">
                    {{ link_to_route('admin.inventory.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
                </div><!--pull-left-->

                <div class="pull-right">
                    {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-xs']) }}
                </div><!--pull-right-->

                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->

    {{ Form::close() }}
@endsection

@section('after-scripts')
    {{ Html::script('https://code.jquery.com/ui/1.11.3/jquery-ui.min.js') }}
    {{ Html::script('js/timepicker.js') }}
    {{ Html::script('js/backend/access/users/script.js') }}
    <script type="text/javascript">
        $('.date').datepicker({ 'dateFormat' : 'yy-mm-dd' });
        $('.time').timepicker({ 'timeFormat': 'HH:mm:ss' });

        $('#physical_quantity').on('change', function(){
            var options = '';
            $('#unit_type').find('option').remove();

            if($(this).val() == 'Mass')
            {
                options += '<option value="g">Gram</option>';
                options += '<option value="kg">Kilogram</option>';
                options += '<option value="ton">Ton</option>';
                options += '<option value="lbs">Pound</option>';
                options += '<option value="oz">Ounce</option>';
            }
            else if($(this).val() == 'Volume')
            {
                options += '<option value="ml">Milliliter</option>';
                options += '<option value="cl">Centiliter</option>';
                options += '<option value="dl">Deciliter</option>';
                options += '<option value="l">Liter</option>';
                options += '<option value="cup">Cup</option>';
                options += '<option value="tsp">Tea Spoon</option>';
                options += '<option value="tbsp">Table Spoon</option>';
                options += '<option value="gal">Gallon</option>';
            }
            else
            {
                options += '<option value="pc">Piece</option>';
                options += '<option value="bottle">Bottle</option>';
                options += '<option value="can">Can</option>';
            }

            $('#unit_type').append(options);
        });
    </script>
@endsection
