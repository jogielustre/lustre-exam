<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Response;

class CommentsController extends Controller
{
    public function index($id)
    {
        $comment = Comment::where('parentcommentid',$id)->orderBy('created_at','desc')->get();
        return response()->json(['message' => 'OK', 'data'=> $comment]);
    }

    public function create($name, $message, $parentId)
    {
      $comment = new Comment();
      $comment->name =$name;
      $comment->message = $message;
      $comment->parentcommentid = $parentId;
      $comment->save();
    
      return response($crud->jsonSerialize(), Response::HTTP_CREATED);
    }

    public function store(CommentRequest $req, Comment $data){
        $comment = $data::create($req->all());
        return response()->json(['message' => 'Comment is posted.', 'data'=> $comment]);
    }
}

?>