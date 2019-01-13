<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link href="{{ asset('css/homePage.css') }}" rel="stylesheet">
    </head>
    <body>
    @toastr_css
    @toastr_js
    @toastr_render
    <div class="topnav">
        <a href="{{action('MainController@homePage')}}">Discussion</a>
        <a href="{{ url('/main/quotes') }}">Quotes</a>
        <a href="{{ url('/main/books') }}">Books</a>
        <a href="{{ url('/main/printinghouse') }}">Printing Houses</a>
        <a href="{{ url('/main/publisher') }}">Publishers</a>
        <a class="active" href="{{ url('/main/stores') }}">Stores</a>
        <div class="topnav-right">
            <a href="{{ url('/main/profile') }}"><i class="fa fa-fw fa-user" style = "color:white; margin : 3px;" ></i>{{Auth::user()->name}}</a>
            <a href="{{action('MainController@message')}}"><span class="glyphicon glyphicon-envelope" style = "color:white;margin : 3px;" ></span>Messages</a>
            <a href="{{ url('/main/logout')}}"><i class="fa fa-sign-out" style = "color:white;margin : 3px;"></i>Logout</a>
        </div>
    </div>
    
    <div class="table-wrapper-scroll-y">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Store Name</th>
                <th scope="col">Publisher Name</th>
                <th scope="col">Store Address</th>
            </tr>
            </thead>
            <tbody>
            @php ($i=1)
            @foreach ($stores as $store)
            <script>
            $(document).ready(function(){
                $("#editButton{{$i}}").click(function() {
                    $(".{{$i}}").show();
                    $("#okButton{{$i}}").show();
                });
            });
            </script>
            <tr>
                <th scope="row">{{$i}}</th>
                <form  method="POST" action="{{ url('/main/updateStore')}}">
                {{ csrf_field() }}
                <td>{{ $store->Store }}<input class="{{$i}}" type="text"  name="editStore" hidden/></td>
                <td>{{ $store->Publisher }}<input class="storeID" value="{{ $store-> storeID}}"  name="storeID" hidden/></td>
                <td>{{ $store->StoreAddress }}<input class="{{$i}}" type="text" name="editAddress" hidden/></td>
                <td ><button id="editButton{{$i}}" type="button" ><span class="glyphicon glyphicon-edit"></span></button></td>
                <td ><button id="okButton{{$i}}" type="submit"  hidden><span class="glyphicon glyphicon-ok"></span></button></td>
                <td ><a href="{{action('MainController@deleteStore', ['storeName' =>$store -> storeID])}}"><span class="glyphicon glyphicon-trash"></span></a></td>
                </form>
            </tr>
            @php ($i= $i + 1)
            @endforeach
            </tbody>
        </table>
        <br />
    </div>

    <div class = "newRecord">
            <div class = "new">
            <span class="label label-default">New Store</span>
        </div>
            <form method="POST" action="{{ url('/main/addStore')}}">
                {{ csrf_field() }}

                <div class="form-group">
                <label for="sel1">Select Publisher:</label>
                <select class="form-control"  name="publisherName">
                    @foreach ($publishers as $publisher)
                        <option value ="{{$publisher->publisherID}}">{{$publisher -> publisherName }}</option>
                    @endforeach
                </select>
                </div>
                <div class="flex form-group" >
                    <label for="usr">Store Name:</label>
                    <input type="text" class="form-control" name="storeName" >
                </div>
                <div class="flex form-group" >
                    <label for="usr">Address:</label>
                    <input type="text" class="form-control" name="address" >
                </div>
                <button type="submit" class="btn btn-success">Create</button>
            </form>
        </div>
    </body>
</html>