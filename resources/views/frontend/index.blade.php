<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Story Builder</title>

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body, html {
            height: 100%; /* Make sure body and html occupy the full viewport height */
            margin: 0; /* Remove default margin */
            display: flex; /* Use flexbox for layout */
            flex-direction: column;
        }
        .background-image {
            /* Use the background image */
            background-image: url('{{ asset('images/imagination.png') }}');
            background-size: cover; /* Cover the entire container */
            background-position: center; /* Center the image */
            background-repeat: no-repeat;
            height: 100vh; /* Full height of the viewport */
            flex: 1;
        }

        .container {
            padding-top: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* Light background overlay for text */
            border-radius: 10px; /* Optional rounded corners */
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

    <div class="container text-center py-4">
        @include('frontend.partials.alerts')
        <h1>Welcome to Story Builder</h1>

        <div class="row mt-4">
            <!-- New Story Section -->
            <div class="col-md-6 text-left">
                <h2>New Story</h2>
                <p>Combining storytelling and visual aids to enhance your storytelling skills. Strive for excellence.</p>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{ route('story.create') }}" class="btn btn-primary">Create New Story</a>
                </div>
            </div>

            <!-- Existing Stories Section -->
            <div class="col-md-6 text-left">
                <h2>Existing Stories</h2>
                <p>Engage in reading stories to enhance your creativity, or assist others in completing their narratives to showcase your storytelling skills and share your talent with the world.</p>
                <a href="{{ route('story.stories') }}" class="btn btn-info">Browse Stories</a>
            </div>
        </div>
    </div>

    @include('frontend.partials.footer')

    <!-- Bootstrap JS (with Popper.js for tooltips and popovers, CDN) -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
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
