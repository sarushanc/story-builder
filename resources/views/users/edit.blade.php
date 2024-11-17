<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Edit User</title>

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
            background-image: url('{{ asset('images/welcomeblank.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            flex: 1;
        }

        .container {
            padding-top: 20px;
            background-color: rgba(255, 255, 255, 0.9); /* Slightly less transparent for better readability */
            border-radius: 10px;
            padding: 40px; /* Increased padding for a more spacious layout */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); /* Added shadow for depth */
        }

        h2 {
            margin-bottom: 30px; /* Spacing below the heading */
            color: #343a40; /* Darker text color for better contrast */
        }

        .form-group label {
            font-weight: bold; /* Bold labels for better readability */
        }

        .btn {
            padding: 10px 20px; /* Increased button padding */
        }

        .btn-primary {
            background-color: #007bff; /* Custom primary color */
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Darker shade on hover */
            border-color: #0056b3;
        }

        .error-message {
            font-size: 0.875rem; /* Smaller font size for error messages */
            color: #dc3545; /* Bootstrap danger color */
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
        <h2 class="text-center">{{ __('Edit User') }}</h2>

        <div class="row justify-content-center">
            <div class="col-lg-8"> <!-- Adjusted column width for better focus -->

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-4">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <!-- Edit User Form -->
                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="name" class="block">{{ __('Name') }}</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                                @error('name')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block">{{ __('Email') }}</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                                @error('email')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="block">{{ __('New Password') }}</label>
                                <input type="password" name="password" id="password" class="form-control">
                                @error('password')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">{{ __('Leave blank if you don\'t want to change the password.') }}</small>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="block">{{ __('Confirm New Password') }}</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            </div>

                            <div class="mb-4">
                                <input type="checkbox" name="isAdmin" id="isAdmin" value="1" {{ old('isAdmin', $user->isAdmin) ? 'checked' : '' }} class="form-check-input">
                                <label for="isAdmin" class="form-check-label">{{ __('Check this box if the user is an admin') }}</label>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('frontend.partials.footer')

    <!-- Bootstrap JS (with Popper.js for tooltips and popovers, CDN) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
