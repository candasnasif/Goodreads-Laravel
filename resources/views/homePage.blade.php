<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css" rel="stylesheet"/> -->
        <link href="{{ asset('css/homePage.css') }}" rel="stylesheet">
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> -->
    </head>
    <body>
    @toastr_css
    @toastr_js
    @toastr_render
    <div class="topnav">
        <a class="active" href="{{action('MainController@homePage')}}">Discussion</a>
        <a href="{{ url('/main/quotes') }}">Quotes</a>
        <a href="{{ url('/main/books') }}">Books</a>
        <a href="{{ url('/main/printinghouse') }}">Printing Houses</a>
        <a href="{{ url('/main/publisher') }}">Publishers</a>
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
        <th scope="col">Discussion Owner</th>
        <th scope="col">Book Name</th>
        <th scope="col">Title</th>
        <th scope="col">Date</th>
      </tr>
    </thead>
    <tbody>
    @php ($i=1)
    @foreach ($discussions as $discussion)
      <tr class = "satir" ondblclick = "window.location='{{ url("main/discussionDetail/{$discussion -> discussionID}") }}'">
        <th scope="row">{{$i}}</th>
        <td>{{$discussion ->name}}</td>
        <td>{{$discussion ->bookName}}</td>
        <td>{{$discussion ->discussionTitle}}</td>
        <td>{{$discussion ->discussionDate}}</td>
        @if(Auth::id()  == $discussion->id )
        <td ><a href="{{action('MainController@deleteDiscussion',['id' =>$discussion -> discussionID])}}"><span class="glyphicon glyphicon-trash"></span></a></td>
        @endif
      </tr>
      @php ($i = $i+1)
      @endforeach
    </tbody>
    </table>
    </div>
  <div class = "newRecord">
    <div class = "new">
    <span class="label label-default">New Discussion</span>
    </div>
  <form method="POST" action="{{ url('/main/addDiscussion')}}">
    {{ csrf_field() }}
    <div class="form-group">
    <label for="sel1">Select book:</label>
      <select class="form-control"  name = "bookName">
      @foreach ($books as $book)
        <option value ="{{$book->ISBN}}">{{$book -> bookName}}</option>
      @endforeach
      </select>
    </div>
    <div class="flex form-group" >
      <label for="usr">Title:</label>
      <input type="text" class="form-control" name="title" >
    </div>
    <button type="submit" class="btn btn-success">Save</button>
  </form>
  </div>
    </body>
    
</html>

