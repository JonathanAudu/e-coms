<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\PostComment;
use Illuminate\Http\Request;
use App\Notifications\StatusNotification;
use Illuminate\Support\Facades\Notification;
class PostCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments=PostComment::getAllComments();
        return view('backend.comment.index')->with('comments',$comments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'comment' => 'required|string|max:2000',
            'post_id' => 'required|exists:posts,id',
        ]);

        $post = Post::findOrFail($request->post_id);

        $parentId = $request->input('parent_id');
        $parentId = ($parentId === 'undefined' || $parentId === '' || !is_numeric($parentId)) ? null : (int)$parentId;


        $data = [
            'post_id'   => $post->id,
            'user_id'   => $request->user()->id,
            'comment'   => $request->comment,
            'parent_id' => $request->input('parent_id') ?? null,
            'status'    => 'active',
        ];

        $status = PostComment::create($data);


        // Notify admin
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new StatusNotification([
            'title' => "New Comment on Post",
            'actionURL' => route('blog.detail', $post->slug),
            'fas' => 'fas fa-comment'
        ]));

        if ($status) {
            session()->flash('success', 'Thank you for your comment');
        } else {
            session()->flash('error', 'Something went wrong! Please try again.');
        }

        return redirect()->back();
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comments=PostComment::find($id);
        if($comments){
            return view('backend.comment.edit')->with('comment',$comments);
        }
        else{
            request()->session()->flash('error','Comment not found');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $comment=PostComment::find($id);
        if($comment){
            $data=$request->all();
            // return $data;
            $status=$comment->fill($data)->update();
            if($status){
                request()->session()->flash('success','Comment successfully updated');
            }
            else{
                request()->session()->flash('error','Something went wrong! Please try again!!');
            }
            return redirect()->route('comment.index');
        }
        else{
            request()->session()->flash('error','Comment not found');
            return redirect()->back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment=PostComment::find($id);
        if($comment){
            $status=$comment->delete();
            if($status){
                request()->session()->flash('success','Post Comment successfully deleted');
            }
            else{
                request()->session()->flash('error','Error occurred please try again');
            }
            return back();
        }
        else{
            request()->session()->flash('error','Post Comment not found');
            return redirect()->back();
        }
    }
}
