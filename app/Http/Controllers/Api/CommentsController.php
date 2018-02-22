<?php

namespace App\Http\Controllers\Api;

use App\Base\Service\Mention;
use App\Models\Discussion;
use App\Models\SpecialPage;
use App\Models\User;
use App\Notifications\ArticleWasUpdated;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;
use App\Models\Comment;
use App\Transformers\CommentTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource as CommentCollection;

class CommentsController extends ApiController
{

     public function __construct()
    {
        $this->middleware('auth:api')->except(['index','pageComments']);

    }

    public function index($category,Article $article)
    {
        $comments = $article->comments()->paginate(20);

        return CommentCollection::collection($comments);
    }

    public function discussionComments(Discussion $discussion){
        $comments = $discussion->comments()->paginate(20);
        return CommentCollection::collection($comments);

    }
    public function storeDiscussionComments(Discussion $discussion,Request $request){
        $this->validate($request,[
            'body' => 'required'
        ]);

        $mention = new Mention();
        $parsed_body = $mention->parse($request->body);

        $comment = $discussion->comments()->create([
            'content' => $parsed_body,
            'user_id' => Auth::user()->id
        ]);
        return new CommentCollection($comment);

    }

    public function pageComments($name){

        $page = SpecialPage::where('route',$name)->firstOrFail();

        $comments = $page->comments()->paginate(20);
        return CommentCollection::collection($comments);

    }

    public function storePageComment(Request $request,$name){
        $this->validate($request,[
            'body' => 'required'
        ]);

        $mention = new Mention();
        $parsed_body = $mention->parse($request->body);

        $page = $page = SpecialPage::where('route',$name)->firstOrFail();

        $comment = $page->comments()->create([
            'content' => $parsed_body,
            'user_id' => Auth::user()->id
        ]);
        return new CommentCollection($comment);

    }


    public function store($catrgory_id,Article $article,Request $request)
    {
        $this->validate($request,[
            'body' => 'required'
        ]);

        $parsed_body = app(Mention::class)->parse($request->body);

        $comment = $article->comments()->create([
            'content' => $parsed_body,
            'user_id' => Auth::user()->id
        ]);

        $article->subscriptions->filter(function ($subscribe) use ($comment){
            return $subscribe->user->id != $comment->user->id;
        })->each->notify($comment);


        return new CommentCollection($comment);
    }

    public function destroy(Comment $comment){

         $this->authorize('update',$comment);

         $comment->delete();

         return $this->message('删除成功');

    }
}
