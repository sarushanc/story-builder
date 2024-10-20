<!-- resources/views/storybuilder.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Story Builder</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        /* Set the background image */
        body {
            background: url('{{ asset('images/welcome.png') }}') no-repeat center center fixed;
            background-size: cover;
            color: white; /* To make the text stand out on a background */
        }

        /* Container for welcome content */
        .welcome-container {
            padding-top: 60px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.5); /* Add semi-transparent background */
            padding: 40px;
            border-radius: 10px;
        }

        /* Button group styles */
        .welcome-btn-group {
            margin-top: 20px;
        }

        /* Image styles */
        .welcome-image img {
            max-width: 300px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container welcome-container">
        <h1>Welcome to Story Builder</h1>
        <p>Collaboratively create, edit, and share your stories with the world. Let your creativity shine!</p>

        <!-- Button Group for Sign-Up and Log-In -->
        <div class="welcome-btn-group">
            <a href="{{ route('register') }}" class="btn btn-primary">Join Now</a>
            <a href="{{ route('login') }}" class="btn btn-secondary">Log In</a>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
