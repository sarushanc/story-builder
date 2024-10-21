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
        body {
            padding-top: 20px;
            background-color: #f8f9fa;
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
    </style>
</head>
<body>
    <!-- Navigation Bar with Logout -->
    @include('frontend.partials.navbar')

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
                            <!-- Display rank -->
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
                        <td colspan="3" class="text-center">No rankings available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS (with Popper.js for tooltips and popovers, CDN) -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
