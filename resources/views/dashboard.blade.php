<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard</title>

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        .background-image {
            background-image: url('{{ asset('images/imaginationup.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
        }

        .container {
            padding-top: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="background-image">
    <!-- Navigation Bar with Logout -->
    @include('frontend.partials.navbar')

    <div class="container text-center py-4">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <h1>{{ __('Dashboard') }}</h1>
        <h3 class="text-lg font-bold">{{ __('Welcome, ') . Auth::user()->name }}!</h3>
        <p>{{ __("You're logged in!") }}</p>

        <hr class="my-4">

        <h4 class="font-semibold text-lg">{{ __('Recent Stories') }}</h4>

        <div class="table-responsive mt-4">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">{{ __('Story Title') }}</th>
                        <th scope="col">{{ __('Created At') }}</th>
                        <th scope="col">{{ __('Created By') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentStories as $story)
                        <tr>
                            <td>{{ $story->title }}</td>
                            <td>{{ $story->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $story->user->name }}</td>
                            <td>
                                <a href="{{ route('story.show', $story->id) }}" class="btn btn-info btn-sm">{{ __('View') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">{{ __('No stories found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('frontend.partials.footer')

    <!-- Bootstrap JS (with Popper.js for tooltips and popovers, CDN) -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
