@extends('layouts.app')

<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

/* Page headers */
h2 {
    color: #343a40;
}

/* Button styling */
.btn {
    padding: 10px 20px;
    margin: 5px;
    border-radius: 5px;
    text-align: center;
    cursor: pointer;
    color: white;
}

.btn-primary {
    background-color: #007bff;
}

.btn-danger {
    background-color: #dc3545;
}

.btn-info {
    background-color: #17a2b8;
}

/* Card styling for comments */
.card {
    background-color: white;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    margin-bottom: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.card-body {
    padding: 15px;
}

.d-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.actions a, .actions form {
    display: inline-block;
}

.actions a {
    margin-right: 5px;
}

/* Comment form styling */
.input-group {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
}

.input-group input {
    flex-grow: 1;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ced4da;
    margin-right: 10px;
}

.input-group button {
    padding: 10px 20px;
    border-radius: 5px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
}

.input-group button:hover {
    background-color: #0056b3;
}

/* Small text styling */
small {
    color: #6c757d;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> {{ $project->name }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('projects.index') }}" title="Go back"> <i
                        class="fas fa-backward "></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                {{ $project->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Introduction:</strong>
                {{ $project->introduction }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Location:</strong>
                {{ $project->location }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Cost:</strong>
                {{ $project->cost }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Date Created:</strong>
                {{ date_format($project->created_at, 'jS M Y') }}
            </div>
        </div>
    </div>

    <h3 style="margin-top: 10%;">Comments</h3>
    <!-- Comment list -->
    @if ($project->comments->count() > 0)
        @foreach ($project->comments as $comment)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            {{ $comment->content }} - 
                            <small>
                                {{ $comment->created_at->format('jS M Y H:i') }}
                                by {{ $comment->user->name }} 
                                @if($comment->user->role == "admin")
                                    (Admin)
                                @endif
                            </small>
                        </div>
                        <div class="actions">
                            <!-- Edit and delete icons -->
                            @can('update', $comment)
                                <a href="{{ route('comments.edit', ['project' => $project->id, 'comment' => $comment->id]) }}" title="Edit Comment" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endcan

                            @can('delete', $comment)
                            <form action="{{ route('comments.destroy', ['project' => $project->id, 'comment' => $comment->id]) }}" method="delete" class="delete-form" style="display: inline-block;">
                                @csrf
                                <button type="submit" title="Delete Comment" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p>No comments yet.</p>
    @endif

    <!-- Comment form -->
    @auth
        <form action="{{ route('comments.store', ['project' => $project->id]) }}" method="POST">
            @csrf
            <div class="input-group">
                <input type="hidden" name="project_id" class="form-control" value="{{ $project->id }}">
                <input type="text" name="content" class="form-control" placeholder="Add a comment..." required>
                <button class="btn btn-primary" type="submit">
                <i class="fas fa-plus"></i> Add
                </button>
            </div>
        </form>
    @else
        <div style="background-color:#ebeced; padding:20px">
            Login to add your comments on this project.
        </div>
    @endauth
    
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all delete forms with the class 'delete-form'
        var deleteForms = document.querySelectorAll('.delete-form');

        // Add event listener to each form
        deleteForms.forEach(function(form) {
            form.addEventListener('submit', function(event) {
                // Prevent form submission
                event.preventDefault();

                // Show SweetAlert confirmation popup
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this comment!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If the user confirms, submit the form
                        form.submit();
                    }
                });
            });
        });
    });
</script>



