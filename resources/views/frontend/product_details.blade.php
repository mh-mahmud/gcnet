@extends('frontend.layouts.app')

@section('meta_title'){{ $product->meta_title }}@stop

@section('meta_description'){{ $product->meta_description }}@stop

@section('meta_keywords'){{ $product->tags }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $product->meta_title }}">
    <meta itemprop="description" content="{{ $product->meta_description }}">
    <meta itemprop="image" content="{{ asset($product->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $product->meta_title }}">
    <meta name="twitter:description" content="{{ $product->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ asset($product->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($product->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $product->meta_title }}" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ route('product', $product->slug) }}" />
    <meta property="og:image" content="{{ asset($product->meta_img) }}" />
    <meta property="og:description" content="{{ $product->meta_description }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
    <meta property="og:price:amount" content="{{ single_price($product->unit_price) }}" />
@endsection

@section('content')
    <!-- SHOP GRID WRAPPER -->
    <section class="product-details-area">
        <div class="container">

            <div class="bg-white">

                <!-- Product gallery and Description -->
                <div class="row no-gutters cols-xs-space cols-sm-space cols-md-space">
                    <div class="col-lg-6">
                        <div class="product-gal sticky-top d-flex flex-row-reverse">
                            @if(is_array(json_decode($product->photos)) && count(json_decode($product->photos)) > 0)
                                <div class="product-gal-img">
                                    <img class="xzoom img-fluid" src="{{ asset(json_decode($product->photos)[0]) }}" xoriginal="{{ asset(json_decode($product->photos)[0]) }}" />
                                </div>
                                <div class="product-gal-thumb">
                                    <div class="xzoom-thumbs">
                                        @foreach (json_decode($product->photos) as $key => $photo)
                                            <a href="{{ asset($photo) }}">
                                                <img class="xzoom-gallery" width="80" src="{{ asset($photo) }}"  @if($key == 0) xpreview="{{ asset($photo) }}" @endif>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <!-- Product description -->
                        <div class="product-description-wrapper">
                            <!-- Product title -->
                            <h2 class="product-title">
                                {{ __($product->name) }}
                            </h2>
                            <ul class="breadcrumb">
                                <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                                <li><a href="{{ route('categories.all') }}">{{__('All Categories')}}</a></li>
                                <li><a href="{{ route('products.category', $product->category_id) }}">{{ $product->category->name }}</a></li>
                                
                            </ul>


                            <div class="row">
                                <div class="col-6">
                                    <!-- Rating stars -->
                                    <div class="rating mb-1">
                                        @php
                                            $total = 0;
                                            $total += $product->reviews->count();
                                        @endphp
                                        <span class="star-rating">
                                            {{ renderStarRating($product->rating) }}
                                        </span>
                                        <span class="rating-count">({{ $total }} {{__('customer reviews')}})</span>
                                    </div>
                                    <div class="sold-by">
                                        <small class="mr-2">{{__('Sold by')}}: </small>
                                        @if ($product->added_by == 'seller' && \App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
                                            <a href="{{ route('shop.visit', $product->user->shop->slug) }}">{{ $product->user->shop->name }}</a>
                                        @else
                                            {{ __('Inhouse product') }}
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6 text-right">
                                    <ul class="inline-links inline-links--style-1">
                                        @php
                                            $qty = 0;
                                            foreach (json_decode($product->variations) as $key => $variation) {
                                                $qty += $variation->qty;
                                            }
                                        @endphp
                                        @if(count(json_decode($product->variations, true)) >= 1)
                                            @if ($qty > 0)
                                                <li>
                                                    <span class="badge badge-md badge-pill bg-green">{{__('In stock')}}</span>
                                                </li>
                                            @else
                                                <li>
                                                    <span class="badge badge-md badge-pill bg-red">{{__('Out of stock')}}</span>
                                                </li>
                                            @endif
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            @if(home_price($product->id) != home_discounted_price($product->id))

                                <div class="row no-gutters mt-4">
                                    <div class="col-2">
                                        <div class="product-description-label">{{__('Price')}}:</div>
                                    </div>
                                    <div class="col-10">
                                        <div class="product-price-old">
                                            <del>
                                                {{ home_price($product->id) }}
                                                <span>/{{ $product->unit }}</span>
                                            </del>
                                        </div>
                                    </div>
                                </div>

                                <div class="row no-gutters mt-3">
                                    <div class="col-2">
                                        <div class="product-description-label mt-1">{{__('Discount Price')}}:</div>
                                    </div>
                                    <div class="col-10">
                                        <div class="product-price">
                                            <strong>
                                                {{ home_discounted_price($product->id) }}
                                            </strong>
                                            <span class="piece">/{{ $product->unit }}</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row no-gutters mt-3">
                                    <div class="col-2">
                                        <div class="product-description-label">{{__('Price')}}:</div>
                                    </div>
                                    <div class="col-10">
                                        <div class="product-price">
                                            <strong>
                                                {{ home_discounted_price($product->id) }}
                                            </strong>
                                            <span class="piece">/{{ $product->unit }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <hr>

                            <form id="option-choice-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">

                                @foreach (json_decode($product->choice_options) as $key => $choice)

                                <div class="row no-gutters">
                                    <div class="col-2">
                                        <div class="product-description-label mt-2 ">{{ $choice->title }}:</div>
                                    </div>
                                    <div class="col-10">
                                        <ul class="list-inline checkbox-alphanumeric checkbox-alphanumeric--style-1 mb-2">
                                            @foreach ($choice->options as $key => $option)
                                                <li>
                                                    <input type="radio" id="{{ $choice->name }}-{{ $option }}" name="{{ $choice->name }}" value="{{ $option }}" @if($key == 0) checked @endif>
                                                    <label for="{{ $choice->name }}-{{ $option }}">{{ $option }}</label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                @endforeach

                                @if (count(json_decode($product->colors)) > 0)
                                    <div class="row no-gutters">
                                        <div class="col-2">
                                            <div class="product-description-label mt-2">{{__('Color')}}:</div>
                                        </div>
                                        <div class="col-10">
                                            <ul class="list-inline checkbox-color mb-1">
                                                @foreach (json_decode($product->colors) as $key => $color)
                                                    <li>
                                                        <input type="radio" id="{{ $product->id }}-color-{{ $key }}" name="color" value="{{ $color }}" @if($key == 0) checked @endif>
                                                        <label style="background: {{ $color }};" for="{{ $product->id }}-color-{{ $key }}" data-toggle="tooltip"></label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                    <hr>
                                @endif

                                <!-- Quantity + Add to cart -->
                                <div class="row no-gutters">
                                    <div class="col-2">
                                        <div class="product-description-label mt-2">{{__('Quantity')}}:</div>
                                    </div>
                                    <div class="col-10">
                                        <div class="product-quantity d-flex align-items-center">
                                            <div class="input-group input-group--style-2 pr-3" style="width: 160px;">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-number" type="button" data-type="minus" data-field="quantity" disabled="disabled">
                                                        <i class="la la-minus"></i>
                                                    </button>
                                                </span>
                                                <input type="text" name="quantity" class="form-control input-number text-center" placeholder="1" value="1" min="1" max="10">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-number" type="button" data-type="plus" data-field="quantity">
                                                        <i class="la la-plus"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            @if(count(json_decode($product->variations, true)) >= 1)
                                                <div class="avialable-amount">({{ $qty }} {{__('available')}})</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row no-gutters pb-3 d-none" id="chosen_price_div">
                                    <div class="col-2">
                                        <div class="product-description-label">{{__('Total Price')}}:</div>
                                    </div>
                                    <div class="col-10">
                                        <div class="product-price">
                                            <strong id="chosen_price">

                                            </strong>
                                        </div>
                                    </div>
                                </div>

                            </form>

                            <div class="d-table width-100 mt-3">
                                <div class="d-table-cell">
                                    <!-- Buy Now button -->
                                    @if(count(json_decode($product->variations, true)) >= 1)
                                        @if ($qty > 0)
                                            <button type="button" class="btn btn-styled btn-base-1 btn-icon-left strong-700 hov-bounce hov-shaddow" onclick="buyNow()">
                                                <i class="la la-shopping-cart"></i> {{__('Buy Now')}}
                                            </button>
                                        @endif
                                    @endif
                                    <!-- Add to cart button -->
                                    <button type="button" class="btn btn-styled btn-alt-base-1 c-white btn-icon-left strong-700 hov-bounce hov-shaddow ml-2" onclick="addToCart()">
                                        <i class="la la-shopping-cart"></i>
                                        <span class="d-none d-md-inline-block"> {{__('Add to cart')}}</span>
                                    </button>
                                </div>
                            </div>


                            <hr class="mt-3 mb-0">

                            <div class="d-table width-100 mt-2">
                                <div class="d-table-cell">
                                    <!-- Add to wishlist button -->
                                    <button type="button" class="btn pl-0 btn-link strong-700" onclick="addToWishList({{ $product->id }})">
                                        {{__('Add to wishlist')}}
                                    </button>
                                    <!-- Add to compare button -->
                                    <button type="button" class="btn btn-link btn-icon-left strong-700" onclick="addToCompare({{ $product->id }})">
                                        {{__('Add to compare')}}
                                    </button>
                                </div>
                            </div>

                            <hr class="mt-2">

                            <div class="row no-gutters mt-3">
                                <div class="col-2">
                                    <div class="product-description-label alpha-6">{{__('Return Policy')}}:</div>
                                </div>
                                <div class="col-10">
                                    {{__('Returns accepted if product not as described, buyer pays return shipping fee; or keep the product & agree refund with seller.')}} <a href="{{ route('returnpolicy') }}" class="ml-2">View details</a>
                                </div>
                            </div>
                            @if ($product->added_by == 'seller')
                                <div class="row no-gutters mt-3">
                                    <div class="col-2">
                                        <div class="product-description-label alpha-6">{{__('Seller Guarantees')}}:</div>
                                    </div>
                                    <div class="col-10">
                                        @if ($product->user->seller->verification_status == 1)
                                            {{__('Verified seller')}}
                                        @else
                                            {{__('Non verified seller')}}
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="row no-gutters mt-3">
                                <div class="col-2">
                                    <div class="product-description-label alpha-6">{{__('Payment')}}:</div>
                                </div>
                                <div class="col-10">
                                    <ul class="inline-links">
                                        <li>
                                            <img src="{{ asset('frontend/images/icons/cards/visa.png') }}" width="30" class="">
                                        </li>
                                        <li>
                                            <img src="{{ asset('frontend/images/icons/cards/mastercard.png') }}" width="30" class="">
                                        </li>
                                        <li>
                                            <img src="{{ asset('frontend/images/icons/cards/maestro.png') }}" width="30" class="">
                                        </li>
                                        <li>
                                            <img src="{{ asset('frontend/images/icons/cards/paypal.png') }}" width="30" class="">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            {{-- <div class="row no-gutters mt-3">
                                <div class="col-2">
                                    <img src="{{ asset('frontend/images/icons/buyer-protection.png') }}" width="40" class="">
                                </div>
                                <div class="col-10">
                                    <div class="heading-6 strong-700 text-info d-inline-block">Buyer protection</div><a href="" class="ml-2">View details</a>
                                    <ul class="list-symbol--1 pl-4 mb-0 mt-2">
                                        <li><strong>Full Refund</strong> if you don't receive your order</li>
                                        <li><strong>Full or Partial Refund</strong>, if the item is not as described</li>
                                    </ul>
                                </div>
                            </div> --}}
                            <hr class="mt-4">
                            <div class="row no-gutters mt-4">
                                <div class="col-2">
                                    <div class="product-description-label mt-2">{{__('Share')}}:</div>
                                </div>
                                <div class="col-10">
                                    <div id="share"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="gry-bg">
        <div class="container">
            <div class="row">
                
                <div class="col-xl-12">
                    <div class="product-desc-tab bg-white">
                        <div class="tabs tabs--style-2">
                            <ul class="nav nav-tabs justify-content-center sticky-top bg-white">
                                <li class="nav-item">
                                    <a href="#tab_default_1" data-toggle="tab" class="nav-link text-uppercase strong-600 active show">{{__('Description')}}</a>
                                </li>
                                @if($product->video_link != null)
                                    <li class="nav-item">
                                        <a href="#tab_default_2" data-toggle="tab" class="nav-link text-uppercase strong-600">{{__('Video')}}</a>
                                    </li>
                                @endif
                                @if($product->pdf != null)
                                    <li class="nav-item">
                                        <a href="#tab_default_3" data-toggle="tab" class="nav-link text-uppercase strong-600">{{__('Downloads')}}</a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a href="#tab_default_4" data-toggle="tab" class="nav-link text-uppercase strong-600">{{__('Reviews')}}</a>
                                </li>
                            </ul>

                            <div class="tab-content pt-0">
                                <div class="tab-pane active show" id="tab_default_1">
                                    <div class="py-2 px-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php echo $product->description; ?>
                                            </div>
                                        </div>
                                        <span class="space-md-md"></span>
                                    </div>
                                </div>

                                <div class="tab-pane" id="tab_default_2">
                                    <div class="fluid-paragraph py-2">
                                        <!-- 16:9 aspect ratio -->
                                        <div class="embed-responsive embed-responsive-16by9 mb-5">
                                            @if ($product->video_provider == 'youtube' && $product->video_link != null)
                                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ explode('=', $product->video_link)[1] }}"></iframe>
                                            @elseif ($product->video_provider == 'dailymotion' && $product->video_link != null)
                                                <iframe class="embed-responsive-item" src="https://www.dailymotion.com/embed/video/{{ explode('video/', $product->video_link)[1] }}"></iframe>
                                            @elseif ($product->video_provider == 'vimeo' && $product->video_link != null)
                                                <iframe src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $product->video_link)[1] }}" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_default_3">
                                    <div class="py-2 px-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a href="{{ asset($product->pdf) }}">{{ __('Download') }}</a>
                                            </div>
                                        </div>
                                        <span class="space-md-md"></span>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_default_4">
                                    <div class="fluid-paragraph py-4">
                                        @foreach ($product->reviews as $key => $review)
                                            <div class="block block-comment">
                                                <div class="block-image">
                                                    <img src="{{ asset($review->user->avatar_original) }}" class="rounded-circle">
                                                </div>
                                                <div class="block-body">
                                                    <div class="block-body-inner">
                                                        <div class="row no-gutters">
                                                            <div class="col">
                                                                <h3 class="heading heading-6">
                                                                    <a href="javascript:;">{{ $review->user->name }}</a>
                                                                </h3>
                                                                <span class="comment-date">
                                                                    {{ date('d-m-Y', strtotime($review->created_at)) }}
                                                                </span>
                                                            </div>
                                                            <div class="col">
                                                                <div class="rating text-right clearfix d-block">
                                                                    <span class="star-rating star-rating-sm float-right">
                                                                        @for ($i=0; $i < $review->rating; $i++)
                                                                            <i class="fa fa-star active"></i>
                                                                        @endfor
                                                                        @for ($i=0; $i < 5-$review->rating; $i++)
                                                                            <i class="fa fa-star"></i>
                                                                        @endfor
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="comment-text">
                                                            {{ $review->comment }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        @if(count($product->reviews) <= 0)
                                            <div class="text-center">
                                                {{ __('There have been no reviews for this product yet.') }}
                                            </div>
                                        @endif

                                        @if(Auth::check())
                                            @php
                                                $commentable = false;
                                            @endphp
                                            @foreach ($product->orderDetails as $key => $orderDetail)
                                                @if($orderDetail->order->user_id == Auth::user()->id && $orderDetail->delivery_status == 'delivered' && \App\Review::where('user_id', Auth::user()->id)->where('product_id', $product->id)->first() == null)
                                                    @php
                                                        $commentable = true;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            @if ($commentable)
                                                <div class="leave-review">
                                                    <div class="section-title section-title--style-1">
                                                        <h3 class="section-title-inner heading-6 strong-600 text-uppercase">
                                                            {{__('Write a review')}}
                                                        </h3>
                                                    </div>
                                                    <form class="form-default" role="form" action="{{ route('reviews.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="" class="text-uppercase c-gray-light">{{__('Your name')}}</label>
                                                                    <input type="text" name="name" value="{{ Auth::user()->name }}" class="form-control" disabled required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="" class="text-uppercase c-gray-light">{{__('Email')}}</label>
                                                                    <input type="text" name="email" value="{{ Auth::user()->email }}" class="form-control" required disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="c-rating mt-1 mb-1 clearfix d-inline-block">
                                                                    <input type="radio" id="star5" name="rating" value="5" required/>
                                                                    <label class="star" for="star5" title="Awesome" aria-hidden="true"></label>
                                                                    <input type="radio" id="star4" name="rating" value="4" required/>
                                                                    <label class="star" for="star4" title="Great" aria-hidden="true"></label>
                                                                    <input type="radio" id="star3" name="rating" value="3" required/>
                                                                    <label class="star" for="star3" title="Very good" aria-hidden="true"></label>
                                                                    <input type="radio" id="star2" name="rating" value="2" required/>
                                                                    <label class="star" for="star2" title="Good" aria-hidden="true"></label>
                                                                    <input type="radio" id="star1" name="rating" value="1" required/>
                                                                    <label class="star" for="star1" title="Bad" aria-hidden="true"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-sm-12">
                                                                <textarea class="form-control" rows="4" name="comment" placeholder="{{__('Your review')}}" required></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="text-right">
                                                            <button type="submit" class="btn btn-styled btn-base-1 btn-circle mt-4">
                                                                {{__('Send review')}}
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <br><br>
                </div>

            </div>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {

            getVariantPrice();

    		$('#share').share({
    			networks: ['facebook','twitter','linkedin','tumblr','in1','stumbleupon','digg'],
    			theme: 'square'
    		});
    	});

    </script>
@endsection
