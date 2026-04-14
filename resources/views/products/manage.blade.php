@extends('layouts.app')

@section('content')


<div class="row">
    <div class="col-lg-12">
        <!-- <a href="{{ route('products.create')}}" class="btn btn-rounded btn-info pull-right">{{__('Add New Code')}}</a> -->
        <a
            class="btn btn-rounded btn-info pull-right"
            data-toggle="modal"
            data-target="#codeModal"
        >{{__('Add New Code')}}</a>
    </div>
</div>

<br>

<div class="col-lg-12">
    <div class="panel">
        <!--Panel heading-->
        <!-- <div class="panel-heading">
            <h3 class="panel-title"><b>{{ __($type) }}: </b></h3>
        </div> -->
        <?php $variations = json_decode($products->variations); ?>
        <div class="panel-body">
            <div class="panel-data" style="background-color:#f3f3f3;padding:10px;margin-bottom:20px;border-radius:5px">
                <p>Product Name: {{ $products->name }} </p>
                <p>Brand Name: {{ $products->brand->name }} </p>
                <p>Category Name: {{ $products->category->name }} </p>
            </div>
            <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <!-- <th>{{__('Product Name')}}</th> -->
                        <!-- <th>{{__('Category Name')}}</th> -->
                        <!-- <th>{{__('Brand Name')}}</th> -->
                        <th>{{__('Domination')}}</th>
                        <th width="20%">{{__('Code')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Change Status')}}</th>
                        <th>{{__('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($codes as $key => $val)
                        <tr>
                            <td>{{$key+1}}</td>
                            <!-- <td>{{ __($val->product->name) }}</td> -->
                            <!-- <td>{{ __($val->category->name) }}</td> -->
                            <!-- <td>{{ __($val->brand->name) }}</td> -->
                            <td>{{ __($val->domination) }}</td>
                            <td>{{ __($val->p_code) }}</td>
                            <td id="span-{{ $val->id }}">
                                <?php
                                    if($val->status=='ACTIVE') {
                                        echo '<label class="label label-info">Active</label>';
                                    }
                                    else if($val->status=='INACTIVE') {
                                        echo '<label class="label label-danger">Inactive</label>';
                                    }
                                    else if($val->status=='SOLD') {
                                        echo '<label class="label label-success">Sold</label>';
                                    }
                                    else if($val->status=='CANCELLED') {
                                        echo '<label class="label label-warning">Cancelled</label>';
                                    }
                                    else if($val->status=='EXPIRED') {
                                        echo '<label class="label label-warning">Expired</label>';
                                    }
                                    else if($val->status=='PROCESSING') {
                                        echo '<label class="label label-warning">PROCESSING</label>';
                                    }
                                ?>
                            </td>
                            <td>
                                @if($val->status=='ACTIVE' || $val->status=='INACTIVE')
                                <label class="switch">
                                <input onchange="updateCodeStatus(this, '{{$val->domination}}')" value="{{ $val->id }}" type="checkbox" <?php if($val->status == 'ACTIVE') echo "checked";?> >
                                <span class="slider round"></span></label>
                                @endif
                            </td>
                            <td>

                                <a
                                class="edit-modal btn btn-info btn-xs"
                                data-id="<?php echo $val->id; ?>"
                                data-domination="<?php echo $val->domination; ?>"
                                data-code="<?php echo $val->p_code; ?>"
                                data-status="<?php echo $val->status; ?>"
                                data-toggle="modal"
                                data-target="#editCodeModal">
                                    Edit
                                </a>

                                <a
                                class="btn btn-xs btn-danger"
                                onclick="confirm_modal('{{route('products.destroycode', $val->id)}}');"
                                >
                                    {{__('Delete')}}
                                </a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>

<div id="codeModal" class="modal fade" tabindex="-1" data-width="400">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add Product Code</h4>
            </div>
            <div class="modal-body" style="padding:20px !important;">
                <div class="row">
                    <div class="col-md-12">

                        <form action="{{route('products.addcode')}}" method="POST">

                            @csrf
                            <input type="hidden" name="product_id" value="{{ $products->id }}">
                            <input type="hidden" name="category_id" value="{{ $products->category_id }}">
                            <input type="hidden" name="brand_id" value="{{ $products->brand_id }}">
                            <div class="form-group">
                                <label for="domination" class="control-label mb-1">Domination</label>
                                <select name="domination" class="form-control control-label mb-1" required>
                                    <option value="">-- Select --</option>
                                    @foreach($variations as $key=>$val)
                                        <option value="{{ $key }}">{{ $key }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="p_code" class="control-label mb-1">Code</label>
                                <input autocomplete="off" type="text" name="p_code" class="form-control" required>
                            </div>

                            <div>
                                <input type="submit" class="btn btn-sm btn-info btn-block" name="submit" value="Submit">
                            </div>
                        </form>

                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn dark btn-danger btn-outline">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="editCodeModal" class="modal fade" tabindex="-1" data-width="400">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Edit Product Code</h4>
            </div>
            <div class="modal-body" style="padding:20px !important;">
                <div class="row">
                    <div class="col-md-12">

                        <form action="{{route('products.editcode')}}" method="POST">

                            @csrf
                            <input id="code_id" type="hidden" name="id">
                            <input type="hidden" name="product_id" value="{{ $products->id }}">
                            <input type="hidden" name="category_id" value="{{ $products->category_id }}">
                            <input type="hidden" name="brand_id" value="{{ $products->brand_id }}">
                            <div class="form-group">
                                <label for="domination" class="control-label mb-1">Domination</label>
                                <select id="domination" name="domination" class="form-control control-label mb-1" required>
                                    <option value="">-- Select --</option>
                                    @foreach($variations as $key=>$val)
                                        <option value="{{ $key }}">{{ $key }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="p_code" class="control-label mb-1">Code</label>
                                <input id="p_code" autocomplete="off" type="text" name="p_code" class="form-control" required>
                            </div>

                            <div>
                                <input type="submit" class="btn btn-sm btn-info btn-block" name="submit" value="Submit">
                            </div>
                        </form>

                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn dark btn-danger btn-outline">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection


@section('script')
    <script type="text/javascript">

        $(document).ready(function(){
            //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
        });

        function updateCodeStatus(el, domination){
            var code_id = el.value;
            var product_id = "{{ $products->id }}";
            if(el.checked){
                var status = 'ACTIVE';
            }
            else{
                var status = 'INACTIVE';
            }
            $.post('{{ route('products.changecodestatus') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status, domination:domination, product_id:product_id}, function(data){
                if(data == 1){
                    showAlert('success', 'Code status updated successfully');
                    if(status=='ACTIVE') {
                        $("td#span-"+code_id).html('<label class="label label-info">Active</label>');
                    }
                    else if(status = 'INACTIVE') {
                        $("td#span-"+code_id).html('<label class="label label-danger">Inactive</label>');
                    }
                }
                else{
                    showAlert('danger', 'Something went wrong');
                }
            });
        }

        $('.edit-modal').on('click', function() {
            var data_id = $(this).attr('data-id');
            var data_domination = $(this).attr('data-domination');
            var data_status = $(this).attr('data-status');
            var data_code = $(this).attr('data-code');

            $('#code_id').attr('value', data_id);
            $('#p_code').attr('value', data_code);
            $('#domination').val(data_domination);
        });

    </script>
@endsection
