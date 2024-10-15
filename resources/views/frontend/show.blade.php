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
    @include('frontend.partials.navbar')

    <div class="container">
        @include('frontend.partials.alerts')

        <div class="d-flex justify-content-end">
            <a href="{{ route('story.downloadEbook', $story->id) }}" class="btn btn-success mt-3">Download Ebook</a>
        </div>
        
        <h1 class="text-center">{{ $story->title }}</h1>
        <p class="text-muted text-center">Created by: {{ $story->user->name }}</p>
        <p>{{ $story->description }}</p>

        <!-- Branch and Section Limits -->
        <h4>Branch and Section Limits</h4>
        <ul>
            <li><strong>Branch Limit:</strong> {{ $story->branch_count }}</li>
            <li><strong>Section Limit:</strong> {{ $story->section_count }}</li>
        </ul>

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
                            <span class="section-number">{{ $section->branch_level }}</span>.<span class="text-muted"> By {{ $section->user->name }}</span><br>
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

                            <!-- Add Branch Form -->
                            @if($section->branch_level < $story->branch_count && $story->section_count > $section->section_number)
                                <div class="add-branch mt-3">
                                    @if($section->section_number == $story->section_count - 1)
                                        <div class="alert alert-info mt-2">
                                            You have reached the end of the sections for this story! Consider adding a conclusion.
                                        </div>
                                    @endif
                                    <button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#add-branch-form-{{ $section->id }}">
                                        Add Branch
                                    </button>

                                    <!-- Collapsible form -->
                                    <div id="add-branch-form-{{ $section->id }}" class="collapse mt-2">
                                        <form action="{{ route('section.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="story_id" value="{{ $story->id }}">
                                            <input type="hidden" name="parent_id" value="{{ $section->id }}">
                                            <input type="hidden" name="branch_level" value="{{ $section->branch_level + 1 }}">
                                            <div class="form-group">
                                                <label for="content">Branch Content</label>
                                                <textarea name="content" class="form-control" required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="multimedia">Multimedia</label>
                                                <input type="text" name="multimedia" class="form-control" placeholder="Multimedia URL (optional)">
                                            </div>
                                            <button type="submit" class="btn btn-success">Add Branch</button>
                                        </form>
                                    </div>
                                </div>
                                @elseif($section->section_number == $story->section_count)
                                    <div class="alert alert-info mt-2">
                                        <strong>Congratulations!</strong> You have reached the end of the story!
                                    </div>

                                    <!-- Like Button -->
                                    @if(!$section->likes->where('user_id', Auth::id())->count())
                                        <form action="{{ route('section.like', $section->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                Like this section
                                            </button>
                                        </form>
                                    @else
                                        <p>You have already liked this story branch.</p>
                                    @endif

                                    <!-- Display like count -->
                                    <p>{{ $section->likes->count() }} people liked this section.</p>
                                @endif
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
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    {{-- <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').alert('close');
            }, 60000);
        });
    </script> --}}
</body>
</html>
