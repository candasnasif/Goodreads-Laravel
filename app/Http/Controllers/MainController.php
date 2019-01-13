<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Query\Builder;
use Validator;
use Auth;
use Carbon\Carbon;
use App\AuthorViewInProfile;
use App\PrintingHouseInfo;
use App\BookInfo;
use App\MessageInfo;
use App\QuoteInfo;
use App\StoreInfo;
use App\User;
use App\Genre;
use App\Author;
use Illuminate\Support\Facades\Hash;


class MainController extends Controller
{
    function index() {
        return view('login');
    }

    function checklogin(Request $request) {
        $this->validate($request, [
            'email'         =>  'required|email',
            'password'      =>  'required|alphaNum|min:3'
        ]);

        $user_data = array(
            'email'     =>  $request->get('email'),
            'password'  =>  $request->get('password')
        );

        if(Auth::attempt($user_data)) {
            return redirect('main/profile');
        }
        else {
            return back()->with('error', 'You entered the wrong incredentials.');
        }
    }

    function successlogin() {
        return view('successlogin');
    }

    function homePage() {
        $discussions = DB::select('select * from discussion d inner join users u on d.id = u.id inner join book b on d.ISBN = b.ISBN order by d.discussionID');
        $books = DB::select('select * from book');
        return view('homePage',['discussions' => $discussions, 'books' => $books]);
    }

    function deleteDiscussion(Request $request){
        try { 
            $id = $request->id;
            $postID_List = DB::select('select postID from post where discussionID = ?',[$id]);
            $postID_List = array_map(function ($value) {
                return (array)$value;
            }, $postID_List);
            DB::table('postcomment')->whereIn('postID', $postID_List)->delete();
            DB::table('post')->where('discussionID', '=', $id)->delete();
            DB::table('discussion')->where('discussionID', '=', $id )->delete();
            toastr()->success('Transaction completed successfully!');
            return redirect('main/homePage');
        } catch(\Illuminate\Database\QueryException $ex){ 
            error_log($ex);
            toastr()->error('The transaction failed!');
            return redirect('main/homePage');
        }    
    }

    function deleteComment(Request $request){
        $postID = $request->postID;
        try { 
            $id = $request->id;
            DB::table('postcomment')->where('commentID', '=', $id )->delete();
            toastr()->success('Transaction completed successfully!');
            return redirect(url("main/comments/{$postID}"));
        } catch(\Illuminate\Database\QueryException $ex){ 
            toastr()->error('The transaction failed!');
            return redirect(url("main/comments/{$postID}"));
        }    
    }

    function deletePost(Request $request){
        $discussionId = $request->discussionID;
        try { 
            $id = $request->id;
            DB::table('postcomment')->where('postID', '=', $id)->delete();
            DB::table('post')->where('postID', '=', $id )->delete();
            toastr()->success('Transaction completed successfully!');
            return redirect(url("main/discussionDetail/{$discussionId}"));
        } catch(\Illuminate\Database\QueryException $ex){ 
            toastr()->error('The transaction failed!');
            return redirect(url("main/discussionDetail/{$discussionId}"));
        }

    }

    function addDiscussion(Request $request){
        try { 
            $ISBN = $request->get('bookName');
            $title = $request->get('title');
            $userId = Auth::id();
            error_log($userId);
             DB::table('discussion')->insert(
                ['ISBN' => $ISBN, 'discussionTitle' => $title, 'discussionDate' =>Carbon::now('Europe/Istanbul'), 'id' => $userId]
            );
            toastr()->success('Transaction completed successfully!');
            return redirect('main/homePage');
          } catch(\Illuminate\Database\QueryException $ex){ 
            toastr()->error('The transaction failed!');
            return redirect('main/homePage');
          }
    }

    function discussionDetail($id){
        $discussion = DB::select('select u.name,d.discussionTitle,d.discussionID, b.bookName from discussion d inner join users u on d.id = u.id inner join book b on d.ISBN = b.ISBN where d.discussionID = ? limit 1',[$id]);
        $posts = DB::select('select p.id,p.postID, p.postBody, p.postDate, u.name from post p  inner join users u on u.id = p.id where p.discussionID = ?',[$id]);
        return view('discussionDetail', ['posts' => $posts, 'discussion' => $discussion]);
    }

    function getComments($id){
        $post = DB::select('select u.name,p.postBody,p.postID,p.discussionID from post p inner join users u on p.id = u.id where p.postID = ? limit 1',[$id]);
        $comments = DB::select('select pc.id,pc.commentID, pc.commentBody, pc.postDate, u.name from postcomment pc  inner join users u on u.id = pc.id where pc.postID = ?',[$id]);
        return view('postComment', ['comments' => $comments, 'post' => $post]);
    }
    
    function addPost(Request $request){
        $discussionId = $request->get('discussionID');
        try {
        $userId = Auth::id();
        $post = $request->get('newPost');
        DB::table('post')->insert(
            ['discussionID' => $discussionId, 'postBody' => $post, 'postDate' =>Carbon::now('Europe/Istanbul'), 'id' => $userId]
        );
        toastr()->success('Transaction completed successfully!');
        return redirect(url("main/discussionDetail/{$discussionId}"));
        } catch(\Illuminate\Database\QueryException $ex){ 
            toastr()->error('The transaction failed!');
            return redirect(url("main/discussionDetail/{$discussionId}"));
        }
    }

    
    function addComment(Request $request){
        $postID = $request->get('postID');
        try {
        $userId = Auth::id();
        $comment = $request->get('newComment');
        DB::table('postcomment')->insert(
            ['postID' => $postID, 'commentBody' => $comment, 'postDate' =>Carbon::now('Europe/Istanbul'), 'id' => $userId]
        );
            toastr()->success('Transaction completed successfully!');
        return redirect(url("main/comments/{$postID}"));
        } catch(\Illuminate\Database\QueryException $ex){ 
            toastr()->error('The transaction failed!');
            return redirect(url("main/comments/{$postID}"));
        }
    }

    
    function message(){
        $id = Auth::id();
        $friends = DB::select('select u.id, u.name from users u  inner join (select * from friend  f where f.id = ? or f.friendId = ?) j on (u.id = j.id and j.id != ?) or (u.id = j.friendId and j.friendId != ?)',[$id,$id,$id,$id]);
        return view('message',['friends' => $friends,'selected' => 0]);
    }

    function getMessages($id){
        $userId = Auth::id();
        $messages = DB::select('select * from message where (senderID = ? and recieverID = ?) or (senderID = ? and recieverID = ?) order by sentDate
        ',[$id,$userId,$userId,$id]);
        $friends = DB::select('select u.id, u.name from users u  inner join (select * from friend  f where f.id = ? or f.friendId = ?) j on (u.id = j.id and j.id != ?) or (u.id = j.friendId and j.friendId != ?)',[$userId,$userId,$userId,$userId]);
        return view('message', ['friends' => $friends,'selected' => $id,'messages' => $messages]);

    }

    function sendMessage(Request $request){
        $userId = Auth::id();
        $recieverId = $request->get('recieverId');
        $body = $request->get('body');
        error_log(date('F-d-Y h:m', strtotime(Carbon::now('Europe/Istanbul'))));
        DB::table('message')->insert(
            ['senderID' => $userId, 'recieverID' => $recieverId, 'sentDate' =>Carbon::now('Europe/Istanbul'), 'body' => $body]
        );
        return redirect(url("main/message/{$recieverId}"));
    }

    function profile() {
        $user = Auth::user();
        $books = DB::table('readby')
            ->join('users', 'users.id', '=', 'readby.id')
            ->join('book', 'book.isbn', '=', 'readby.isbn')
            ->where('users.id', '=',  $user->id)
            ->get();
        $authors = AuthorViewInProfile::all()->where('UserID',  $user->id);
        $id = Auth::id();
        $avgBookLength = $books->avg('numOfPages');
        $biggestBook = $books->max('numOfPages');
        $smallestBook = $books->min('numOfPages');
        $lastBook = $books->max('dateStarted');
        $firstBook = $books->min('dateStarted');
        $friends = DB::select('select * from users u  inner join (select * from friend  f where f.id = ? or f.friendId = ?) j on (u.id = j.id and j.id != ?) or (u.id = j.friendId and j.friendId != ?)',[$id,$id,$id,$id]);
        return view('profile', ['books' => $books, 'authors' => $authors, 'friends'=>$friends, 'user'=>$user, 'avgBookLength'=>$avgBookLength, 'biggestBook'=>$biggestBook, 'smallestBook'=>$smallestBook, 'lastBook'=>$lastBook, 'firstBook'=>$firstBook ]);
    }

    function deleteFriend(Request $request) {
        $id = $request->id;
        $friendid = $request->friendid;
        DB::table('friend')
            ->where('friendid', '=', $id)
            ->where('id', '=', $friendid)->delete();
        DB::table('friend')
            ->where('friendid', '=', $friendid)
            ->where('id', '=', $id)->delete();
        return redirect('/main/profile');
    }

    function deleteBookFromProfile(Request $request) {
        $user = Auth::user();
        $ISBN = $request->ISBN;
        DB::table('readby')
            ->where('id', '=', $user->id)
            ->where('ISBN', '=', $ISBN)->delete();
        return redirect('/main/profile');
    }

    function updateBookDate(Request $request) {
        try {
            $ISBN = $request->input('ISBN');
            $editStart = $request->get('dateStarted');
            $editFinish = $request->get('dateFinished');
            error_log($editStart);
            $user = Auth::user();
            DB::table('readby')
                ->where('ISBN', $ISBN)
                ->where('id', $user->id)
                ->update(['dateStarted' => $editStart, 'dateFinished' => $editFinish]);
            return redirect('/main/profile');
        } catch(\Illuminate\Database\QueryException $ex){ 
            toastr()->error('Book couldn"t be updated!');
            return redirect('/main/books');
        }
    }

    function addBookMyProfile(Request $request){
        try {
            $ISBN = $request->ISBN;
            $user = Auth::user();
            DB::table('readBy')->insert(
                ['ISBN' => $ISBN, 'id' => $user->id, 'dateStarted' =>Carbon::now('Europe/Istanbul'), 'readingStatus' => 'Reading']
            );
            return redirect('/main/books');
        } catch(\Illuminate\Database\QueryException $ex){ 
            toastr()->error('New book could not added!');
            return redirect('/main/books');
        }
        
    }
    
    function publisher() {
        $publishers = DB::table('publisher')->get();
        return view('publisher', ['publishers'=>$publishers]);
    }

    function printinghouse() {
        $printinghouses = PrintingHouseInfo::all();
        $publishers = DB::table('publisher')->get();
        return view('printinghouse', ['printinghouses'=>$printinghouses, 'publishers'=>$publishers]);
    }

    function books() {
        $user = Auth::user();
        $books = BookInfo::all();
        $genres = Genre::all();
        $authors = Author::all();
        $myBooks = DB::table('readby')
        ->join('users', 'users.id', '=', 'readby.id')
        ->join('book', 'book.isbn', '=', 'readby.isbn')
        ->where('users.id', '=',  $user->id)
        ->get();
        return view('books', ['books'=>$books, 'myBooks'=> $myBooks,'genres' => $genres, 'authors'=> $authors]);
    }

    function messages() {
        $user = Auth::user();
        $messages = DB::select('select * from message_info where senderid= ? or receiverid = ?',[$user->id,$user->id]);
        return view('messages', ['messages'=>$messages]);
    }

    function quotes() {
        $quotes = QuoteInfo::all();
        return view('quotes', ['quotes'=>$quotes]);
    }

    function stores() {
        $stores = StoreInfo::all();
        $publishers = DB::table('publisher')->get();
        return view('stores', ['stores'=>$stores, 'publishers'=>$publishers]);
    }

    function signUp(){
        return view('signUp');
    }
    function register(Request $request){
        $name = $request->get('name');
        $email = $request->get('email');
        $password = $request->get('password');
        $user = new User();
        $user->name = $name;
        $user->password = Hash::make($password);
        $user->email = $email;
        $user->typeID = 1;
        $user->save();
        return view('login');
    }
    function newBook(Request $request){
        try{
            $ISBN = $request->get('ISBN');
            $author = $request->get('author');
            $dateWritten = $request->get('dateWritten');
            $genres = $request->get('genre');
            $bookName = $request->get('bookName');
            $numOfPages = $request->get('numOfPages');
            $bookLanguage = $request->get('bookLanguage');
            DB::table('book')->insert(
                ['ISBN' => $ISBN, 'bookName' => $bookName, 'numOfPages' =>$numOfPages, 'bookLanguage' => $bookLanguage]
            );
            foreach ($genres as $genreID){ 
                DB::table('genreofbook')->insert(
                    ['ISBN' => $ISBN, 'genreID' => $genreID]
                );
            }
            DB::table('writtenby')->insert(
                ['ISBN' => $ISBN, 'authorID' => $author, 'dateWritten' =>$dateWritten]
            );
            toastr()->success('New book added successfully!');
            return redirect('/main/books');
        } catch(\Illuminate\Database\QueryException $ex){ 
            toastr()->error('New book could not added!');
            return redirect('/main/books');
        }
    }
    
    function addPublisher(Request $request) {
        try { 
            $publisherName = $request->get('publisherName');
            $founder = $request->get('founder');
            $origin = $request->get('origin');
            $dateFounded = $request->get('dateFounded');
             DB::table('publisher')->insert(
                ['publisherName' => $publisherName, 'founder' => $founder, 'origin'=>$origin, 'dateFounded'=>$dateFounded]
            );
            toastr()->success('Transaction completed successfully!');
            return redirect('main/publisher');
          } catch(\Illuminate\Database\QueryException $ex){ 
            toastr()->error('The transaction failed!');
            return redirect('main/publisher');
          }
    }

    function addPrintingHouse(Request $request) {
        try { 
            $publisherID = $request->get('publisherName');
            $phousename = $request->get('pHouseName');
            $address = $request->get('address');
            $userId = Auth::id();
             DB::table('printinghouse')->insert(
                ['publisherID' => $publisherID, 'printingHouseAddress' => $address]
            );
            toastr()->success('Transaction completed successfully!');
            return redirect('main/printinghouse');
          } catch(\Illuminate\Database\QueryException $ex){ 
            toastr()->error('The transaction failed!');
            return redirect('main/printinghouse');
          }
    }

    function addStore(Request $request) {
        try { 
            $publisherID = $request->get('publisherName');
            $storeName = $request->get('storeName');
            $address = $request->get('address');
            $userId = Auth::id();
             DB::table('store')->insert(
                ['publisherID' => $publisherID, 'storeName' => $storeName,'storeAddress' => $address]
            );
            toastr()->success('Transaction completed successfully!');
            return redirect('main/stores');
          } catch(\Illuminate\Database\QueryException $ex){ 
            toastr()->error('The transaction failed!');
            return redirect('main/stores');
          }
    }

    function deleteStore(Request $request) {
        try {
            $storeName = $request->storeName;
            DB::table('store')
                ->where('storeID', '=', $storeName)
                ->delete();
            toastr()->success('Transaction completed successfully!');
            return redirect('/main/stores');
        } catch (\Illuminate\Database\QueryException $ex) {
                toastr()->error('The transaction failed!');
                return redirect('main/stores');        
        }
    }

    function updateStore(Request $request) {
        try {
            $storeID = $request->input('storeID');
            $editStore = $request->get('editStore');
            $editAddress = $request->get('editAddress');
            DB::table('store')
                ->where('storeID', $storeID)
                ->update(['storeName' => $editStore, 'storeAddress' => $editAddress]);
            return redirect('/main/store');
        } catch(\Illuminate\Database\QueryException $ex){ 
            toastr()->error('Book couldn"t be updated!');
            return redirect('/main/store');
        }
    }

    function deletePrintingHouse(Request $request) {
        try {
            $PHouseID = $request->PHouseID;
            DB::table('printinghouse')
                ->where('printingHouseID', '=', $PHouseID)
                ->delete();
            toastr()->success('Transaction completed successfully!');
            return redirect('/main/printinghouse');
        } catch (\Illuminate\Database\QueryException $ex) {
                toastr()->error('The transaction failed!');
                return redirect('main/printinghouse');        
        }
    }

    function updatePrintingHouse(Request $request) {
        try {
            $printingHouseID = $request->input('printingHouseID');
            $editAddress = $request->get('editAddress');
            DB::table('printinghouse')
                ->where('printingHouseID', $printingHouseID)
                ->update(['printingHouseAddress' => $editAddress]);
            toastr()->success('Transaction completed successfully!');
            return redirect('/main/printinghouse');
        } catch(\Illuminate\Database\QueryException $ex){ 
            toastr()->error('Book couldn"t be updated!');
            return redirect('/main/printinghouse');
        }
    }

    function logout() {
        Auth::logout();
        return redirect('main');
    }
}
