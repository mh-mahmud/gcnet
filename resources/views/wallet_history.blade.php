@extends('layouts.app')

@section('content')

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">{{__('Wallet History')}}</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <!-- <th>#</th> -->
                    <th>Customer</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Payment Details</th>
                    <th>Bank Name</th>
                    <th>Transaction Id</th>
                    <th>Status</th>
                    <th>Created at</th>
                    <th>{{__('options')}}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($history as $key => $val)
                    <tr>
                        <!-- <td>
                            {{ $key+1 }}
                        </td> -->
                        <td>{{ $val->user->name }}</td>
                        <td>{{ $val->payment_method }}</td>
                        <td>{{ $val->amount }}</td>
                        <td>{{ $val->payment_details }}</td>
                        <td>{{ $val->bank->bank_name }}</td>
                        <td>{{ $val->transaction_id }}</td>
                        <td>
                            @if ($val->status == 'success')
                                <span class="badge badge-success">success</span>
                            @elseif($val->status == 'pending')
                                <span class="badge badge-warning">pending</span>
                            @elseif($val->status == 'cancel')
                                <span class="badge badge-danger">cancel</span>
                            @elseif($val->status == 'invalid')
                                <span class="badge badge-danger">invalid</span>
                            @endif
                        </td>
                        <td>{{ $val->created_at }}</td>

                        <td>
                            @if($val->status == 'pending')
                            <a
                            class="status-modal btn btn-info btn-xs"
                            data-id="<?php echo $val->id; ?>"
                            data-status="<?php echo $val->status; ?>"
                            data-toggle="modal"
                            data-target="#editStatusModal">
                                Change Status
                            </a>
                            @endif
                        </td>

                        <td>
                            @if($val->status != 'success')
                            <a
                            class="btn btn-xs btn-danger"
                            onclick="confirm_modal('{{route('admin.wallet.delete', $val->id)}}');"
                            >
                                {{__('Delete')}}
                            </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

<div id="editStatusModal" class="modal fade" tabindex="-1" data-width="400">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Change Status</h4>
            </div>
            <div class="modal-body" style="padding:20px !important;">
                <div class="row">
                    <div class="col-md-12">

                        <form action="{{route('wallet.change.status')}}" method="POST">

                            @csrf
                            <input id="wallet_id" type="hidden" name="wallet_id">
                            <div class="form-group">
                                <label for="status" class="control-label mb-1">Status</label>
                                <select id="status" name="status" class="form-control control-label mb-1" required>
                                    <option value="">-- Select --</option>
                                    <option value="success">success</option>
                                    <option value="cancel">cancel</option>
                                    <option value="invalid">invalid</option>
                                </select>
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
        $('.status-modal').on('click', function() {
            var data_id = $(this).attr('data-id');
            $('#wallet_id').attr('value', data_id);
        });
    </script>
@endsection
