<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $story->title }}</title>

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .container {
            padding-top: 20px;
        }
        .section-tree {
            margin-left: 20px;
        }
        .section-number {
            font-weight: bold;
        }
        .branch-level {
            color: gray;
            font-size: 0.9em;
        }
        .multimedia {
            margin-top: 10px;
        }
        .toggle-btn {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Story Builder</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                @auth
                    <li class="nav-item">
                        <span class="nav-link">Hello, {{ Auth::user()->name }}</span>
                    </li>
                    <li class="nav-item">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Logout</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1 class="text-center">{{ $story->title }}</h1>
        <p>{{ $story->description }}</p>

        <h3>Sections</h3>

        @if($story->sections->isEmpty())
            <p>No sections available for this story.</p>
        @else
            <ul class="list-group">
                <!-- Display parent sections and recursively call the child sections -->
                @foreach($story->sections->where('parent_id', null) as $section)
                    <li class="list-group-item">
                        <!-- Section header: Only name, section number, and branch level -->
                        <div class="toggle-btn" data-toggle="collapse" data-target="#section-details-{{ $section->id }}">
                            <span class="section-number">{{ $section->branch_level }}</span>.
                            <span class="text-muted">By {{ $section->user->name }}</span>
                            <br>
                        </div>

                        <!-- Collapsible section content -->
                        <div id="section-details-{{ $section->id }}" class="collapse">
                            <div class="section-content mt-2">
                                <p>{{ $section->content }}</p>

                                <!-- Multimedia toggle -->
                                @if($section->multimedia)
                                    <div class="toggle-btn" data-toggle="collapse" data-target="#multimedia-{{ $section->id }}">
                                        View Multimedia
                                    </div>
                                    <div id="multimedia-{{ $section->id }}" class="collapse multimedia">
                                        {!! $section->multimedia !!}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Display child sections recursively -->
                        @if($section->branches->isNotEmpty())
                            <ul class="section-tree">
                                @foreach($section->branches as $child)
                                    @include('frontend.partials.section-tree', ['section' => $child])
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Bootstrap JS (with Popper.js for tooltips and popovers, CDN) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
