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
    @include('frontend.partials.navbar')

    <div class="container">
        @include('frontend.partials.alerts')
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
    {{-- <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000); // 5 seconds
        });
    </script> --}}
</body>
</html>
