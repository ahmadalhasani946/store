@extends('layouts.home')

@section('title','Product')

@section('banner')
    <div class="page-heading products-heading header-text">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-content">
                        <h2>{{ $product->name }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <style>

        #reviews-comments {
            text-transform: uppercase;
            background: linear-gradient(to right, #f33f3f 0%, black 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        font: {
            size: 20vw;
            family: $font;
        };
        }


        .user_name{
            font-size:14px;
            font-weight: bold;
        }
        .comments-list .media{
            border-bottom: 1px dotted #ccc;
        }

        #basket{
            background: #f33f3f;
            border: black;
            color: white;
        }

        h3{
            font-size: large;
        }

        #Main-Image:hover {
            opacity: 1;
            transform: scale(1);
        }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-md-1 col-1" >
                <div>
                    <img onclick="document.getElementById('Main-Image').src=this.src" style="width:100%;cursor: pointer;margin-bottom: 10px;" src="{{ $product->ImagePath }}" />
                    @foreach($images as $image)
                        <img onclick="document.getElementById('Main-Image').src=this.src" style="width:100%;cursor: pointer;margin-bottom: 10px;" src="{{ asset("storage/images/" . \App\Models\Product::$folderName . '/' . $image->saved_name) }}" />
                    @endforeach
                </div>
            </div>
            <div class="col-md-4 col-4">
                <img id="Main-Image" style="width:100%;" src="{{ $product->ImagePath }}" />
            </div>
            <div class="col-md-6 col-6" style="width:100%;display: inline-flex">
                <div class="col-md-6  col-6">
                    <div id="VP" name="VP">
                        {!! $OptionVariantsList !!}
                    </div>
                </div>
                <div class="col-md-6  col-6">
                    <label>Product Description</label>
                    <p>
                        {!! $product->description !!}
                    </p>
                    <hr>
                    <label>Product Price</label>
                    <p>
                        {!! $product->price !!}₺
                    </p>
                    <hr>
                    <div class="form-group">
                        <label class="form-label" for="quantity">The Amount</label>
                        <div style="margin: 10px 0;">
                            <input  class=' form-control' value="1" min="1" type="number" name="quantity" id="quantity">
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="container">
                @if(auth()->user() != null)
                    <form action="{{route('reviews.store')}}" method="post">
                        @csrf
                        <input hidden type="text" value="{{ auth()->user()->id }}" name="customer_id" id="customer_id">
                        <input hidden type="text" value="{{ $product->id }}" name="product_id" id="product_id">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="form-label @error('comment') error @enderror" for="comment">The Comment</label>
                                    <textarea class="form-control @error('comment') error @enderror" rows="5" id="comment" name="comment" placeholder="Enter The Comment Here">{{ old('comment') }}</textarea>
                                </div>
                                @error('comment')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label @error('stars') error @enderror" for="stars">The Rating</label>
                                <div class="form-group" style="margin:auto;">
                                    <div class="starrating" style="direction:rtl;float:left;">
                                        @for($i = 5; $i > 0; $i--)
                                            <input @if(old('stars') == $i) checked @endif type="radio" id="star{{$i}}" name="stars" value="{{$i}}" /><label for="star{{$i}}" title="{{$i}} star"></label>
                                        @endfor
                                    </div>
                                </div><br><br>
                                @error('stars')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <br>
                                <input class="btn btn-info" type="submit" value="Add Comment">
                                <br>
                            </div>
                        </div>

                    </form>
                @else
                    <div class="alert-danger" style="margin-top: 5px;text-align: center;color: #f33f3f;height: 70px;">
                        <h3 style="padding-top:10px;">Login To Be Able to Add A Comment</h3><a href="{{ route('login') }}">Login</a>
                    </div>
                @endif

                <hr>
            </div>

            <div class="container">
                <h2 id="reviews-comments" style="margin-bottom: 20px;text-align: center">Reviews & Comments</h2>
                @foreach($reviews as $review)
                    <div class="media">
                        <div class="media-left">
                            <img src="{{ $review->user->ImagePath }}" class="media-object" style="width:50px;height:50px;border-radius: 50%;">
                        </div>
                        <div style="margin-left: 10px;" class="media-body">
                            <h4 class="media-heading title" style="color: #f33f3f">{{ $review->user->name }}</h4>
                            <div class="row">
                                <div class="komen col-md-8">
                                    {{ $review->comment }}
                                </div>
                                <div class="starrating col-md-2" style="float:left;">
                                    @for($i = 0; $i < 5; $i++)
                                        <label  @if($i < $review->stars) style="color:Gold;" @endif title="star"></label>
                                    @endfor
                                </div>
                                @if(auth()->user() != null && $review->customer_id == auth()->user()->id )
                                    <div class="col-md-2 flex" style="margin-top: 5px">
                                        <i style="float:left;margin-top: 5px;cursor:pointer;color:green;" title="Edit" class="fa fa-edit" onclick="editReview({{ $review->id }})"></i>
                                        <button style="border:none;background:none;cursor:pointer;color:red;" title="Delete" data-toggle="modal" data-target="#DeleteModal" onclick="forceDelete({{ $review->id }})"><i class="fa fa-times-circle"></i></button>
                                    </div>
                                @endif
                            </div>

                        </div>

                    </div>
                    <div style="width: 50%;margin-left: 300px">
                        <hr>
                    </div>
                @endforeach
            </div>
        </div>

        @if(auth()->user() != null)
            <div class="modal modal-danger" id="DeleteModal" style="margin-top:300px;" name="DeleteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body" style="text-align:center;">
                            <p style="font-size:20px;">Are You Sure You Want To Delete The Review.</p>
                        </div>
                        <div class="modal-footer">
                            <form id="DeletionForm" method="post" action="">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger">Confirm Deletion</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal modal-danger" id="EditModal" style="margin-top:300px;" name="EditModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-footer">
                            <form id="EditionForm" method="post" action="">
                                @csrf
                                @method('Patch')

                                <div style="margin-right: 50px;">
                                    <input hidden type="text" value="{{ auth()->user()->id }}" name="customer_id" id="customer_id">
                                    <input hidden type="text" value="{{ $product->id }}" name="product_id" id="product_id">

                                    <div class="form-group col-md-12">
                                        <label class="form-label @error('comment') error @enderror" for="comment2">The Comment</label>
                                        <textarea class="col-md-12" class="form-control @error('comment') error @enderror" rows="5" id="comment2" name="comment" placeholder="Enter The Comment Here"></textarea>
                                    </div>
                                    @error('comment')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    <hr>
                                    <div class="form-group col-md-12" style="margin:auto;">
                                        <label class="form-label @error('stars') error @enderror" for="stars">The Rating</label>
                                        <div class="starrating col-md-12" id="edit-stars" style="direction:rtl;float:left;">
                                        </div>
                                    </div><br><br>
                                    @error('stars')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    <hr>
                                </div>

                                <button type="submit" class="btn btn-danger">Update Comment</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        <div class="modal modal-danger" id="LoginModal" style="margin-top:300px;" name="DeleteModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body" style="text-align:center;">
                        <p style="font-size:20px;">To Be able to add to the Basket you need to be logged in.</p>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('login') }}" class="btn btn-success">Login</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function refreshTheQuantity(){
            document.getElementById('quantity').value = 1;
        }

        function addToBasket(id){
            if(document.getElementById("CurrentUser").innerHTML == ''){
                $('#LoginModal').modal('toggle');
            }
            else{
                var children = document.getElementById("VP").children;
                var idArr = [];
                var variants = new Array();
                var options = new Array();
                if(children.length > 1){
                    for (var i = 1; i < children.length;i = i + 2 ) {
                        idArr.push(children[i].id);
                    }

                    for(var i = 0; i < idArr.length;i++ ){
                        variants.push(idArr[i]);
                        options.push(document.getElementById(idArr[i]).value);
                    }
                }
                $.ajax({
                    url: "{{route('baskets.store')}}",
                    type: 'POST',
                    data: {
                        _token: $("input[name=_token]").val(),
                        product: id,
                        options: options,
                        variants: variants,
                        quantity: document.getElementById('quantity').value,
                        customer: document.getElementById('CurrentUser').innerHTML,
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        if(response){
                            var cart_count = document.getElementById('cart_count').innerHTML;
                            document.getElementById('cart_count').innerHTML = response.contents;
                        }
                    }
                });
            }
        }

        function forceDelete(id){
            document.getElementById("DeletionForm").action = "{{route('reviews.forceDelete', '')}}"+"/"+id;
        }

        function editReview(id){
            url = "{{ route('reviews.edit' , ['review' => ':id']) }}";
            url = url.replace(':id', id);
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'JSON',
                success: function (response) {
                    if(response){
                        var stars = '';
                        var review = response.review;
                        for(var i = 5; i > 0; i--){
                            if( review.stars == i){
                                stars += '<input checked type="radio" id="edit-star' + i + '" name="stars" value="'+ i +'" /><label for="edit-star'+ i +'" title="'+ i +' star"></label>';
                            }
                            else{
                                stars += '<input type="radio" id="edit-star' + i + '" name="stars" value="'+ i +'" /><label for="edit-star'+ i +'" title="'+ i +' star"></label>';
                            }
                        }
                        document.forms['EditionForm']['comment'].value = review.comment;
                        document.getElementById("edit-stars").innerHTML = stars;
                        url = "{{ route('reviews.update' , ['review' => ':id']) }}";
                        url = url.replace(':id', id);
                        EditionForm.action = url
                        $('#EditModal').modal('toggle');
                    }
                }
            });
        }
    </script>
@endsection
