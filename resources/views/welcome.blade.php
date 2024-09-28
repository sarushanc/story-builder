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
        .welcome-container {
            padding-top: 60px;
            text-align: center;
        }
        .welcome-image {
            margin-top: 30px;
        }
        .welcome-btn-group {
            margin-top: 20px;
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

        <!-- Welcome Image -->
        <div class="welcome-image mt-4">
            <img src="{{ asset('images/storymobile.png') }}" alt="Story Builder" class="img-fluid rounded">
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
