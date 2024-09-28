<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Story Builder</title>

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
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
                <!-- Check if the user is authenticated -->
                @auth
                    <li class="nav-item">
                        <span class="nav-link">Hello, {{ Auth::user()->name }}</span>
                    </li>
                    <li class="nav-item">
                        <!-- Logout button -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Logout</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>

    <div class="container text-center py-4">
        <h1>Welcome to Story Builder</h1>

        <div class="row mt-4">
            <!-- New Story Section -->
            <div class="col-md-6 text-left">
                <h2>New Story</h2>
                <p>Combining storytelling and visual aids to enhance your storytelling skills. Strive for excellence.</p>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{ route('stories.create') }}" class="btn btn-primary">Create New Story</a>
                </div>
            </div>

            <!-- Existing Stories Section -->
            <div class="col-md-6 text-left">
                <h2>Existing Stories</h2>
                <p>Engage in reading stories to enhance your creativity, or assist others in completing their narratives to showcase your storytelling skills and share your talent with the world.</p>
                <a href="{{ route('stories.index') }}" class="btn btn-info">Browse Stories</a>
            </div>

            <!-- Image Section -->
            <div class="col-md-12 mt-4">
                <img src="{{ asset('images/storymobile.png') }}" alt="Example" class="img-fluid rounded">
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (with Popper.js for tooltips and popovers, CDN) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
