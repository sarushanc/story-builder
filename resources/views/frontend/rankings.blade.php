<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>User Rankings</title>

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
            background-image: url('{{ asset('images/imagination.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh; /* Ensure the background covers at least full viewport height */
        }

        .container {
            background-color: rgba(255, 255, 255, 0.85);
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .ranking-table {
            margin-top: 30px;
        }

        .ranking-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .ranking-badge {
            font-size: 1.5rem;
            color: #fff;
            background-color: #007bff;
            padding: 10px;
            border-radius: 5px;
        }

        .no-rankings {
            text-align: center;
            font-style: italic;
            color: #999;
        }
    </style>
</head>
<body class="background-image">

    <!-- Navigation Bar with Logout -->
    @include('frontend.partials.navbar')

    <!-- Rankings Container -->
    <div class="container">
        <h1 class="ranking-header">User Rankings</h1>

        <table class="table table-striped ranking-table">
            <thead>
                <tr>
                    <th scope="col">Rank</th>
                    <th scope="col">User</th>
                    <th scope="col">Points</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rankedUsers as $index => $achievement)
                    <tr>
                        <td>
                            <!-- Display rank with a badge -->
                            <span class="ranking-badge">{{ $index + 1 }}</span>
                        </td>
                        <td>
                            <!-- Display the user name -->
                            {{ $achievement->user->name }}
                        </td>
                        <td>
                            <!-- Display the points -->
                            {{ $achievement->points }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="no-rankings">No rankings available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS and Popper.js (for tooltips and popovers, CDN) -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
