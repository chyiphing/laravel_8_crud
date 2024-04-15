<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Project;
use Illuminate\Http\Request;

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
        $data = Project::find($project);

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);


        // $user = auth()->user();

        $comment = new Comment();
        $comment->content = $request['content'];
        $comment->user_id = $request['user_id'];
        // $comment->user_id = $user->id;
        $comment->project_id = $request['project_id'];

        $comment->save();

        return view('comments.createComment', ['project' => $data]);
    }

    // Retrieve a specific comment
    // public function show(Project $project, Comment $comment)
    // {
    //     return view('comments.show', ['comment' => $comment]);
    // }

    // Update an existing comment
    public function edit($project, $comment)
    {
        $project = Project::findOrFail($project);
        $comment = Comment::findOrFail($comment);

        // Ensure the authenticated user is the owner of the comment
        // if ($comment->user_id !== auth()->user()->id) {
        //     return redirect()->route('projects.show', ['project' => $project->id])
        //         ->withErrors('You do not have permission to edit this comment.');
        // }

        return view('comments.editComment', ['project' => $project, 'comment' => $comment]);
    }

    public function update(Request $request, $project, $comment)
    {
        // echo 123;
        // dd($request);

        $project = Project::findOrFail($project);
        $comment = Comment::findOrFail($comment);
        // dd($project, $comment);
        // Ensure the authenticated user is the owner of the comment
        // if ($comment->user_id !== auth()->user()->id) {
        //     // If the user is not authorized, redirect them back
        //     return redirect()->route('projects.show', ['project' => $project->id])
        //         ->withErrors('You do not have permission to edit this comment.');
        // }

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

        // Ensure the authenticated user is the owner of the comment
        // if ($comment->user_id !== auth()->user()->id) {
        //     return redirect()->route('projects.show', ['project' => $project])
        //         ->withErrors('You do not have permission to delete this comment.');
        // }

        $comment->delete();

        return view('comments.createComment', ['project' => $project]);
    }
}
