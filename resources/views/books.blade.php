<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link href="{{ asset('css/homePage.css') }}" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>  
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
    </head>
    <body>
    @toastr_css
    @toastr_js
    @toastr_render
    <div class="topnav">
        <a href="{{action('MainController@homePage')}}">Discussion</a>
        <a href="{{ url('/main/quotes') }}">Quotes</a>
        <a class="active" href="{{ url('/main/books') }}">Books</a>
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
                <th scope="col">ISBN</th>
                <th scope="col">Book Name</th>
                <th scope="col">Author Name</th>
                <th scope="col">Book Genre(s)</th>
                <th scope="col">Book Length</th>
                <th scope="col">Book Language</th>
            </tr>
            </thead>
            <tbody>
            @php ($i=1)
            @foreach ($books as $book)
            <tr>
                <th scope="row">{{$i}}</th>
                <td>{{ $book->ISBN }}</td>
                <td>{{ $book->bookName }}</td>
                <td>{{ $book->AuthorName }}</td>
                <td>{{ $book->genreName }}</td>
                <td>{{ $book->numOfPages }}</td>
                <td>{{ $book->bookLanguage }}</td>
                @php($readed = 0)
                @foreach($myBooks as $myBook)
                    @if($myBook->ISBN == $book->ISBN)
                        @php($readed = 1)
                    @endif
                @endforeach
                @if($readed == 0)
                <td ><a href="{{action('MainController@addBookMyProfile',['ISBN' =>$book -> ISBN])}}"><span class="glyphicon glyphicon-plus" style="color: green"></span></a></td>
                @endif
            </tr>
            @php ($i= $i + 1)
            @endforeach
            </tbody>
        </table>
        <br />
    </div>
    <div class = "newRecord">
    <div class = "new">
    <span class="label label-default">New Book</span>
    </div>
    <form method="POST" action="{{ url('/main/newBook')}}">
        {{ csrf_field() }}
        <div class="flex form-group" >
            <label for="usr">ISBN:</label>
            <input type="number" class="form-control" name="ISBN" >
        </div>
        <div class="flex form-group" >
            <label for="usr">Name of Book:</label>
            <input type="text" class="form-control" name="bookName" >
        </div>
        <div class="form-group">
            <label for="sel1">Select Author:</label>
            <select class="form-control"  name = "author">
            @foreach ($authors as $author)
                <option value ="{{$author->authorID}}">{{$author -> authorName}}</option>
            @endforeach
            </select>
        </div>
        <div class="flex form-group" >
            <label for="usr">Date Written:</label>
            <input type="date" class="form-control" name="dateWritten" >
        </div>
        <div class="form-group">
            <label for="usr">Genre(s):</label>
            <select id="genre" name="genre[]" multiple class="form-control" >
            @foreach($genres as $genre)
            <option value="{{$genre->genreID}}">{{$genre->genreName}}</option>
            @endforeach
            </select>
        </div>
        <div class="flex form-group" >
            <label for="usr">Number Of Pages:</label>
            <input type="number" class="form-control" name="numOfPages" >
        </div>
        <div class="flex form-group" >
            <label for="usr">Language:</label>
            <input type="text" class="form-control" name="bookLanguage" >
        </div>
        <button type="submit" class="btn btn-success">Save</button>
    </form>
    </div>
    </body>
</html>

<script>
$(document).ready(function(){
 $('#genre').multiselect({
  nonSelectedText: 'Select Genre(s)',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  buttonWidth:'740px'
 });
});
</script>