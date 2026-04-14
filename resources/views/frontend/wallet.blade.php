@extends('frontend.layouts.app')
<style>
    .table td {
        font-size:11px !important;
    }
    .table th {
        font-size:12px !important
    }
</style>
@section('content')
    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @if(Auth::user()->user_type == 'seller')
                        @include('frontend.inc.seller_side_nav')
                    @elseif(Auth::user()->user_type == 'customer')
                        @include('frontend.inc.customer_side_nav')
                    @endif
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12 d-flex align-items-center">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{__('My ePoints')}}
                                    </h2>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{__('Dashboard')}}</a></li>
                                            <li class="active"><a href="{{ route('wallet.index') }}">{{__('My ePoints')}}</a></li>
                                        </ul>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="dashboard-widget text-center green-widget mt-4 c-pointer">
                                    <a href="javascript:;" class="d-block">
                                        <i class="fa fa-credit-card"></i>
                                        <span class="d-block title">{{ single_price(Auth::user()->balance) }}</span>
                                        <span class="d-block sub-title">in your ePoints</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-4">
                                <button class="btn btn-base-1" onclick="show_wallet_modal()">{{__('Recharge ePoint')}}</button>
                                <button class="btn btn-base-1" onclick="show_wallet_modal2()">{{__('Recharge ePoint via Bank')}}</button>
                            </div>
                        </div>

                        <div class="card no-border mt-4">
                            <table class="table table-sm table-hover table-responsive-md">
                                <thead>
                                    <tr>
                                        <!-- <th>#</th> -->
                                        <th>{{ __('Date') }}</th>
                                        <th>{{__('Amount')}}</th>
                                        <th>{{__('Payment Method')}}</th>

                                        <th>{{__('Bank Name')}}</th>
                                        <th>{{__('Account No')}}</th>
                                        <th>{{__('Branch')}}</th>
                                        <th>{{__('Trans# Id')}}</th>
                                        <th>{{__('Status')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @foreach ($wallets as $key => $wallet)
                                       <tr>
                                           <!-- <td>{{ $key+1 }}</td> -->
                                           <td>{{ date('d-m-Y', strtotime($wallet->created_at)) }}</td>
                                           <td>{{ single_price($wallet->amount) }}</td>
                                           <td>{{ ucfirst(str_replace('_', ' ', $wallet ->payment_method)) }}</td>

                                           <td>{{ @$wallet->bank->bank_name }}</td>
                                           <td>{{ @$wallet->bank->account_no }}</td>
                                           <td>{{ @$wallet->bank->branch_name }}</td>
                                           <td>{{ $wallet->transaction_id }}</td>
                                           <td>
                                               @if($wallet->status=='pending')
                                                    <span class="badge badge-warning">pending</span>
                                               @elseif($wallet->status=='success')
                                                    <span class="badge badge-success">success</span>
                                               @elseif($wallet->status=='cancel')
                                                    <span class="badge badge-danger">cancel</span>
                                               @elseif($wallet->status=='invalid')
                                                    <span class="badge badge-danger">invalid</span>
                                               @endif
                                           </td>
                                       </tr>
                                   @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination-wrapper py-4">
                            <ul class="pagination justify-content-end">
                                {{ $wallets->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="wallet_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title strong-600 heading-5">{{__('Recharge via Payment gateway')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('wallet.recharge') }}" method="post">
                    @csrf
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{__('Amount')}} <span class="required-star">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" class="form-control mb-3" name="amount" placeholder="Amount" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{__('Payment Method')}}</label>
                            </div>
                            <div class="col-md-10">
                                <div class="mb-3">
                                    <select class="form-control selectpicker" data-minimum-results-for-search="Infinity" name="payment_option">
                                        <option value="paypal">{{__('Paypal')}}</option>
                                        <option value="stripe">{{__('Stripe')}}</option>
                                        <option value="sslcommerz">{{__('SSLCommerz')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('cancel')}}</button>
                        <button type="submit" class="btn btn-base-1">{{__('Confirm')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="wallet_modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title strong-600 heading-5">{{__('Recharge via Bank')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('wallet.bank.recharge') }}" method="post">
                    @csrf
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{__('Amount')}} <span class="required-star">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <input type="number" class="form-control mb-3" name="amount" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>{{__('Bank Name')}} <span class="required-star">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <select id="bank_name" class="form-control selectpicker" data-minimum-results-for-search="Infinity" name="bank_id" required>
                                        <option value="">-- select bank --</option>
                                        @foreach($banks as $bank)
                                            <option data-account="{{ $bank->account_no }}" data-branch="{{ $bank->branch_name }}" value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label>{{__('Account No')}}</label>
                            </div>
                            <div class="col-md-8">
                                <input id="account_no" type="text" class="form-control mb-3" name="account_no" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label>{{__('Branch Name')}}</label>
                            </div>
                            <div class="col-md-8">
                                <input id="branch_name" type="text" class="form-control mb-3" name="branch_name" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label>{{__('Transaction ID')}} <span class="required-star">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3" name="transaction_id" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label>{{__('Remarks')}}</label>
                            </div>
                            <div class="col-md-8">
                                <textarea name="remarks" class="form-control mb-3"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('cancel')}}</button>
                        <button type="submit" class="btn btn-base-1">{{__('Confirm')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function show_wallet_modal(){
            $('#wallet_modal').modal('show');
        }
        function show_wallet_modal2(){
            $('#wallet_modal2').modal('show');
        }

        $("#bank_name").on("change", function() {
            var selected = $(this).find('option:selected');
            var account = selected.data('account');
            var branch = selected.data('branch');

            if(account==undefined || branch==undefined) {
                $("#account_no").attr('value', '');
                $("#branch_name").attr('value', '');
                return;
            }

            $("#account_no").attr('value', account);
            $("#branch_name").attr('value', branch);
        });


    </script>
@endsection
