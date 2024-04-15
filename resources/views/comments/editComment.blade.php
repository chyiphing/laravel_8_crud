@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Comment</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('projects.show', ['project' => $project->id]) }}" title="Go back">
                    <i class="fas fa-backward"></i> Go Back
                </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('comments.update', ['project' => $project->id, 'comment' => $comment->id]) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="content"><strong>Comment</strong></label>
            <textarea name="content" class="form-control" rows="3" required>{{ old('content', $comment->content) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
@endsection
