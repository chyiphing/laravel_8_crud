@extends('layouts.app')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')
    <div class="row" style="margin-bottom:20px">
        <div class="col-lg-12 margin-tb ">
            <div class="pull-left">
                <h2>Project List </h2>
                @auth
                    <span>
                        Current User: {{ auth()->user()->name }} 
                    @endauth

                    @can('isAdmin')
                        (Admin Panel)
                    @elsecan('isManager')
                        (Manager Panel)
                    @endcan
                </span>

            </div>

            <div class="pull-right">
                @if (Auth::check())
                    <a class="btn btn-success" href="{{ route('projects.create') }}" title="Create a project"> <i
                            class="fas fa-plus-circle"></i> Create Project
                    </a>
                    <a class="btn btn-warning" href="{{ route('logout') }}" title="Logout"> <i class="fas fa-sign-out-alt"
                            style="color:#ffffff"></i> Logout
                    </a>
                @else
                    <a class="btn btn-primary" href="{{ route('login') }}" title="Login"> <i class="fas fa-sign-in-alt"
                            style="color:#ffffff"></i> Login
                    </a>
                    <a class="btn btn-primary" href="{{ route('register') }}" title="Sign Up"> <i class="fas fa-user-plus"
                            style="color:#ffffff"></i> Sign Up
                    </a>
                @endif
            </div>
        </div>
    </div>



    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered table-responsive-lg">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Introduction</th>
            <th>Location</th>
            <th>Cost</th>
            <th>Date Created</th>
            <th>Manager</th>
            <th width="280px">Action</th>
        </tr>

        @foreach ($projects as $project)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $project->name }}</td>
                <td>{{ $project->introduction }}</td>
                <td>{{ $project->location }}</td>
                <td>{{ $project->cost }}</td>
                <td>{{ date_format($project->created_at, 'jS M Y') }}</td>
                <td>
                    {{ $project->user->name }}
                </td>
                <td>
                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="delete-form">
                        <div style="display:flex;">
                            <div style="padding:0px 10px">
                                <a href="{{ route('projects.show', $project->id) }}" title="show">
                                    <i class="fas fa-eye text-success fa-lg"></i>
                                </a>
                            </div>

                            <!-- Add a comment button -->
                            <div style="padding:0px 10px">
                                <a href="{{ route('comments.show', ['project' => $project->id]) }}" title="Create Comment">
                                    <i class="fas fa-comment-alt fa-lg"></i>
                                </a>
                            </div>

                            @can('update', $project)
                                <div style="padding:0px 10px">
                                    <a href="{{ route('projects.edit', $project->id) }}">
                                        <i class="fas fa-edit  fa-lg"></i>
                                    </a>
                                </div>
                            @endcan

                            @can('delete', $project)
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="delete" style="border: none; background-color:transparent;">
                                    <i class="fas fa-trash fa-lg text-danger"></i>
                                </button>
                            @endcan
                        </div>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    {!! $projects->links() !!}
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all forms with the class 'delete-form'
        var deleteForms = document.querySelectorAll('.delete-form');

        // Add an event listener to each form
        deleteForms.forEach(function(form) {
            form.addEventListener('submit', function(event) {
                // Prevent form submission
                event.preventDefault();

                // Show SweetAlert confirmation popup
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\'t be able to recover this project!',
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
