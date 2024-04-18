<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function view($project)
    {
        $data = Project::find($project);
        return view('comments.createComment', ['project' => $data]);
    }

    public function store(Request $request, $project)
    {
        // dd($request);
        $this->authorize('create', Comment::class);
        $data = Project::find($project);

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = new Comment();
        $comment->content = $request['content'];
        $comment->user_id = Auth::id();
        $comment->project_id = $request['project_id'];

        $comment->save();

        return view('comments.createComment', ['project' => $data]);
    }

    // Update an existing comment
    public function edit($project, $comment)
    {

        $project = Project::findOrFail($project);
        $comment = Comment::findOrFail($comment);
        $this->authorize('update', $comment);

        return view('comments.editComment', ['project' => $project, 'comment' => $comment]);
    }

    public function update(Request $request, $project, $comment)
    {
        // echo 123;
        // dd($request);

        $project = Project::findOrFail($project);
        $comment = Comment::findOrFail($comment);
        $this->authorize('update', $comment);

        // Validate the input data
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->content = $request->input('content');
        $comment->save();

        return view('comments.createComment', ['project' => $project]);
    }

    // // Delete a specific comment
    public function destroy($project, $comment)
    {

        $project = Project::findOrFail($project);
        $comment = Comment::findOrFail($comment);
        $this->authorize('delete', $comment);

        $comment->delete();

        return view('comments.createComment', ['project' => $project]);
    }
}
