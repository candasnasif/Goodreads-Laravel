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
    <div class="topnav">
        <a href="{{action('MainController@homePage')}}">Discussion</a>
        <a href="{{ url('/main/quotes') }}">Quotes</a>
        <a href="{{ url('/main/books') }}">Books</a>
        <a href="{{ url('/main/printinghouse') }}">Printing Houses</a>
        <a class="active" href="{{ url('/main/publisher') }}">Publishers</a>
        <a href="{{ url('/main/stores') }}">Stores</a>
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
                <th scope="col">Publisher Name</th>
                <th scope="col">Founder</th>
                <th scope="col">Country of origin</th>
                <th scope="col">Date Founded</th>
            </tr>
            </thead>
            <tbody>
            @php ($i=1)
            @foreach ($publishers as $publisher)
            <tr>
                <th scope="row">{{$i}}</th>
                <td>{{ $publisher->publisherName }}</td>
                <td>{{ $publisher->founder }}</td>
                <td>{{ $publisher->origin }}</td>
                <td>{{ $publisher->dateFounded }}</td>
            </tr>
            @php ($i= $i + 1)
            @endforeach
            </tbody>
        </table>
        <br />
    </div>

    <div class = "newRecord">
    <div class = "new">
    <span class="label label-default">New Publisher</span>
    </div>
    <form method="POST" action="{{ url('/main/addPublisher')}}">
        {{ csrf_field() }}
        <div class="flex form-group" >
            <label for="usr">Publisher Name:</label>
            <input type="text" class="form-control" name="publisherName" >
        </div>
        <div class="flex form-group" >
            <label for="usr">Founder:</label>
            <input type="text" class="form-control" name="founder" >
        </div>
        <div class="form-group">
            <label for="usr">Country of origin:</label>
            <input type="text" class="form-control" name="origin" >
        </div>
        <div class="flex form-group" >
            <label for="usr">Date Founded(Year):</label>
            <input type="text" class="form-control" name="dateFounded" >
        </div>
        <button type="submit" class="btn btn-success">Save</button>
    </form>
    </div>

    </body>
</html>