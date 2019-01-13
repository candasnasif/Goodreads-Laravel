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
        <a href="{{ url('/main/homePage') }}">Discussions</a>
        <a href="{{ url('/main/quotes') }}">Quotes</a>
        <a href="{{ url('/main/books') }}">Books</a>
        <a class="active">Messages</a>
        <a href="{{ url('/main/printinghouse') }}">Printing Houses</a>
        <a href="{{ url('/main/publisher') }}">Publishers</a>
        <a href="{{ url('/main/stores') }}">Stores</a>
        <div class="topnav-right">
            <a href="{{ url('/main/profile') }}"><i class="fa fa-fw fa-user"></i>Profile</a>
            <a href="{{ url('/main/logout') }}"><i class="fa fa-sign-out" style = "color:white;margin : 3px;"></i>Logout</a>
        </div>
    </div>
    
    <div class="table-wrapper-scroll-y">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Sender Name</th>
                <th scope="col">Receiver Name</th>
                <th scope="col">Message</th>
                <th scope="col">Date</th>
            </tr>
            </thead>
            <tbody>
            @php ($i=1)
            @foreach ($messages as $message)
            <tr>
                <th scope="row">{{$i}}</th>
                <td>{{ $message->SenderName }}</td>
                <td>{{ $message->ReceiverName }}</td>
                <td>{{ $message->Message }}</td>
                <td>{{ $message->Date }}</td>
            </tr>
            @php ($i= $i + 1)
            @endforeach
            </tbody>
        </table>
        <br />
    </div>
    </body>
</html>