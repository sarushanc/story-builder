<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Create New Story</title>

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
            background-image: url('{{ asset('images/welcomeblank.png') }}');
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

    <div class="container">
        @include('frontend.partials.alerts')
        <h1 class="text-center">Create New Story</h1>

        <form method="POST" action="{{ route('story.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{ Auth::id() }}">

            <div class="form-group">
                <label for="title">Story Title</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="branch_count">Branch Count</label>
                <input type="number" name="branch_count" id="branch_count" class="form-control" min="1" required>
            </div>

            <div class="form-group">
                <label for="section_count">Section Count</label>
                <input type="number" name="section_count" id="section_count" class="form-control" min="1" required>
            </div>

            <!-- Multimedia inputs in a row -->
            <div class="form-group">
                <label>Multimedia (optional)</label>
                <div class="row">
                    <div class="col-md-4">
                        <input type="file" name="multimedia[]" id="multimedia_1" class="form-control-file">
                    </div>
                    <div class="col-md-4">
                        <input type="file" name="multimedia[]" id="multimedia_2" class="form-control-file">
                    </div>
                    <div class="col-md-4">
                        <input type="file" name="multimedia[]" id="multimedia_3" class="form-control-file">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Story</button>
            <a href="{{ route('story.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    @include('frontend.partials.footer')

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
