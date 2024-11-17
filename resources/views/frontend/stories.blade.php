<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Browse Stories</title>

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">

    <style>
        body, html {
            height: 100%; /* Make sure body and html occupy the full viewport height */
            margin: 0; /* Remove default margin */
            display: flex; /* Use flexbox for layout */
            flex-direction: column;
        }
        .background-image {
            background-image: url('{{ asset('images/welcomeblank.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: repeat;
            height: 100vh;
            flex: 1;
        }

        .container {
            padding-top: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 20px;
        }
        footer {
            margin-top: auto; /* Push the footer to the bottom */
        }
    </style>
</head>
<body class="background-image">
    <!-- Navigation Bar with Logout -->
    @include('frontend.partials.navbar')

    <div class="container">
        @include('frontend.partials.alerts')
        <h1 class="text-center">Browse Stories</h1>

        <div class="text-right mb-4">
            <a href="{{ route('story.create') }}" class="btn btn-success">Create New Story</a>
        </div>

        <form method="GET" action="{{ route('story.stories') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search stories..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="user_filter" class="form-control">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_filter') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="filter" class="form-control">
                        <option value="">All Stories</option>
                        <option value="my" {{ request('filter') == 'my' ? 'selected' : '' }}>My Stories</option>
                    </select>
                </div>
            </div>
            <div class="mt-2">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('story.stories') }}" class="btn btn-secondary">Clear Filters</a> <!-- Clear filters button -->
            </div>
        </form>

        <div class="row">
            @foreach($stories as $story)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $story->title }}</h5>
                            <p class="card-text">{{ Str::limit($story->content, 100) }}</p>
                            <a href="{{ route('story.show', $story->id) }}" class="btn btn-info">View</a>
                            @if(auth()->user()->isAdmin)
                                <form action="{{ route('story.destroy', $story->id) }}" method="POST" class="delete-story-form d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm delete-story">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $stories->links() }} <!-- Pagination links -->

        @if($stories->isEmpty())
            <p class="text-center">No stories found.</p>
        @endif
    </div>

    @include('frontend.partials.footer')

    <!-- Bootstrap JS (with Popper.js for tooltips and popovers, CDN) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.delete-story').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                let form = this.closest('.delete-story-form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</body>
</html>
