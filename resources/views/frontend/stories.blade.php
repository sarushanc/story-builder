<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Browse Stories</title>

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .container {
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar with Logout -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Story Builder</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                @auth
                    <li class="nav-item">
                        <span class="nav-link">Hello, {{ Auth::user()->name }}</span>
                    </li>
                    <li class="nav-item">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Logout</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1 class="text-center">Browse Stories</h1>

        <div class="text-right mb-4">
            <a href="{{ route('story.create') }}" class="btn btn-success">Create New Story</a>
        </div>

        <form method="GET" action="{{ route('stories.index') }}" class="mb-4">
            <div class="row">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="Search stories..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="filter" class="form-control">
                        <option value="">All Stories</option>
                        <option value="my" {{ request('filter') == 'my' ? 'selected' : '' }}>My Stories</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Search</button>
        </form>

        <div class="row">
            @foreach($stories as $story)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">{{ $story->title }}</h5>
                            <p class="card-text">{{ Str::limit($story->content, 100) }}</p>
                            <a href="{{ route('story.show', $story->id) }}" class="btn btn-info">View</a>
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

    <!-- Bootstrap JS (with Popper.js for tooltips and popovers, CDN) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
