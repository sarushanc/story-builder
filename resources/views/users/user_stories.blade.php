<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Stories of {{ $user->name }}</title>

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .background-image {
            background-image: url('{{ asset('images/welcomeblank.png') }}');
            background-size: cover;
            background-position: center;
            height: 100vh;
        }

        .container {
            padding-top: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        table {
            margin-top: 20px;
        }
    </style>
</head>
<body class="background-image">
    <!-- Navigation Bar with Logout -->
    @include('frontend.partials.navbar')

    <div class="container">
        @include('frontend.partials.alerts')

        <h2 class="text-center">{{ __('Stories of ') . $user->name }}</h2>

        <div class="text-right mb-4">
            <a href="{{ route('story.create') }}" class="btn btn-success">Create New Story</a>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Branch Count') }}</th>
                    <th>{{ __('Section Count') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stories as $story)
                    <tr>
                        <td>{{ $story->title }}</td>
                        <td>{{ $story->branch_count }}</td>
                        <td>{{ $story->section_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">{{ __('No stories found for this user.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $stories->links() }} <!-- Pagination links -->

    </div>

    <!-- Bootstrap JS (with Popper.js for tooltips and popovers, CDN) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
