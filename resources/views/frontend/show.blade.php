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
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        .background-image {
            /* Use the background image */
            background-image: url('{{ asset('images/welcomeblank.png') }}');
            background-size: cover; /* Cover the entire container */
            background-position: center; /* Center the image */
            background-repeat: repeat;
            height: 100vh; /* Full height of the viewport */
        }

        .container {
            padding-top: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* Light background overlay for text */
            border-radius: 10px; /* Optional rounded corners */
            padding: 20px;
        }
    </style>
</head>
<body class="background-image">
    <!-- Navigation Bar -->
    @include('frontend.partials.navbar')

    <div class="container">
        @include('frontend.partials.alerts')

        <div class="d-flex">
            @if($hasLikedSections)
                <a href="{{ route('story.downloadEbook', $story->id) }}" class="btn btn-success">Download Ebook</a>
            @endif
        </div>

        <h1 class="text-center">{{ $story->title }}</h1>
        <p class="text-muted text-center">Created by: {{ $story->user->name }}</p>
        <p>{{ $story->description }}</p>

        @if($story->multimedias->isNotEmpty())
            <div class="story-multimedia mt-3">
                <h4>Story Multimedia</h4>
                @foreach($story->multimedias as $media)
                    @if(strpos($media->file_type, 'image') !== false)
                        <!-- Display thumbnail image -->
                        <img src="{{ Storage::url($media->file_path) }}" alt="Story Image" class="img-thumbnail mb-3 multimedia-thumbnail" style="width: 100%; max-width: 100px; cursor: pointer;" data-toggle="modal" data-target="#multimediaModal-{{ $media->id }}">
                    @elseif(strpos($media->file_type, 'video') !== false)
                        <!-- Display video thumbnail -->
                        <button class="btn btn-info mb-3 multimedia-thumbnail" data-toggle="modal" data-target="#multimediaModal-{{ $media->id }}">
                            View Video
                        </button>
                    @elseif(strpos($media->file_type, 'audio') !== false)
                        <!-- Display audio thumbnail -->
                        <button class="btn btn-info mb-3 multimedia-thumbnail" data-toggle="modal" data-target="#multimediaModal-{{ $media->id }}">
                            Listen to Audio
                        </button>
                    @endif
                @endforeach
            </div>
        @endif

        @if(Auth::id() === $story->user_id || Auth::user()->isAdmin)
            @if($story->multimedias->count() < 3)
                <div class="add-multimedia mt-3">
                    <h4>Add Multimedia</h4>
                    <form action="{{ route('multimedia.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="story_id" value="{{ $story->id }}">
                        <div class="form-group">
                            <label for="multimedia">Select Multimedia (Image, Video, Audio)</label>
                            <input type="file" name="multimedia[]" id="multimedia" class="form-control-file" required>
                            <small class="form-text text-muted">You can add a maximum of 3 multimedia items.</small>
                        </div>
                        <button type="submit" class="btn btn-success">Add Multimedia</button>
                    </form>
                </div>
            @else
                <div class="alert alert-info mt-3">
                    <strong>Maximum multimedia limit reached!</strong> You can only add up to 3 multimedia items.
                </div>
            @endif
        @endif

        <!-- Modal Structure for Multimedia -->
        @foreach($story->multimedias as $media)
            <div class="modal fade" id="multimediaModal-{{ $media->id }}" tabindex="-1" role="dialog" aria-labelledby="multimediaModalLabel-{{ $media->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="multimediaModalLabel-{{ $media->id }}">
                                @if(strpos($media->file_type, 'image') !== false)
                                    Image
                                @elseif(strpos($media->file_type, 'video') !== false)
                                    Video
                                @elseif(strpos($media->file_type, 'audio') !== false)
                                    Audio
                                @endif
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @if(strpos($media->file_type, 'image') !== false)
                                <img src="{{ Storage::url($media->file_path) }}" class="img-fluid" alt="Story Image">
                            @elseif(strpos($media->file_type, 'video') !== false)
                                <video controls class="w-100">
                                    <source src="{{ Storage::url($media->file_path) }}" type="{{ $media->file_type }}">
                                    Your browser does not support the video tag.
                                </video>
                            @elseif(strpos($media->file_type, 'audio') !== false)
                                <audio controls class="w-100">
                                    <source src="{{ Storage::url($media->file_path) }}" type="{{ $media->file_type }}">
                                    Your browser does not support the audio tag.
                                </audio>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Branch and Section Limits -->
        <h4>Branch and Section Limits</h4>
        <ul>
            <li><strong>Branch Limit:</strong> {{ $story->branch_count }}</li>
            <li><strong>Section Limit:</strong> {{ $story->section_count }}</li>
        </ul>

        <h3>Sections</h3>

        @if($story->sections->isEmpty())
            <p>No sections available for this story.</p>

            @if($story->sections->count() < $story->branch_count)
                <!-- Display form to create the first section -->
                <div class="add-section mt-3">
                    <button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#add-section-form">
                        Add First Section
                    </button>

                    <!-- Collapsible form -->
                    <div id="add-section-form" class="collapse mt-2">
                        <form action="{{ route('section.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="story_id" value="{{ $story->id }}">
                            <input type="hidden" name="parent_id" value="">
                            <input type="hidden" name="branch_level" value="1"> <!-- Starting at level 1 for first section -->
                            <div class="form-group">
                                <label for="content">Section Content</label>
                                <textarea name="content" class="form-control" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="multimedia_1">Multimedia (optional)</label>
                                <input type="file" name="multimedia[]" id="multimedia_1" class="form-control-file">
                            </div>

                            <div class="form-group">
                                <label for="multimedia_2">Multimedia (optional)</label>
                                <input type="file" name="multimedia[]" id="multimedia_2" class="form-control-file">
                            </div>

                            <div class="form-group">
                                <label for="multimedia_3">Multimedia (optional)</label>
                                <input type="file" name="multimedia[]" id="multimedia_3" class="form-control-file">
                            </div>
                            <button type="submit" class="btn btn-success">Create Section</button>
                        </form>
                    </div>
                </div>
            @endif

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

                                <!-- Button to trigger modal -->
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#multimediaModal-{{ $section->id }}">
                                    View Multimedia
                                </button>

                                <!-- Modal structure -->
                                <div class="modal fade" id="multimediaModal-{{ $section->id }}" tabindex="-1" role="dialog" aria-labelledby="multimediaModalLabel-{{ $section->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="multimediaModalLabel-{{ $section->id }}">Multimedia Content</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Display Existing Multimedia -->
                                                @foreach($section->multimedias as $media)
                                                    @if(strpos($media->file_type, 'image') !== false)
                                                        <!-- Image -->
                                                        <img src="{{ Storage::url($media->file_path) }}" class="img-fluid mb-3" style="width: 100%; border: 2px solid #ddd; padding: 5px;">
                                                    @elseif(strpos($media->file_type, 'video') !== false)
                                                        <!-- Video -->
                                                        <video controls class="mb-3" style="width: 100%; border: 2px solid #ddd; padding: 5px;">
                                                            <source src="{{ Storage::url($media->file_path) }}" type="{{ $media->file_type }}">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    @elseif(strpos($media->file_type, 'audio') !== false)
                                                        <!-- Audio -->
                                                        <audio controls class="mb-3" style="width: 100%; border: 2px solid #ddd; padding: 5px;">
                                                            <source src="{{ Storage::url($media->file_path) }}" type="{{ $media->file_type }}">
                                                            Your browser does not support the audio tag.
                                                        </audio>
                                                    @endif
                                                @endforeach

                                                <!-- Add Multimedia Form: Only for Section Creator or Admin -->
                                                @if(Auth::id() === $section->user_id || Auth::user()->isAdmin)
                                                    <!-- Ensure the section has fewer than 3 multimedia files -->
                                                    @if($section->multimedias->count() < 3)
                                                        <div class="add-multimedia mt-4">
                                                            <h5>Add Multimedia</h5>
                                                            <form action="{{ route('section.addMultimedia', $section->id) }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label for="multimedia">Select Multimedia (Image, Video, Audio)</label>
                                                                    <input type="file" name="multimedia[]" id="multimedia" class="form-control-file" required multiple>
                                                                    <small class="form-text text-muted">You can add a maximum of 3 multimedia items per section.</small>
                                                                </div>
                                                                <button type="submit" class="btn btn-success">Upload Multimedia</button>
                                                            </form>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-info mt-3">
                                                            <strong>Maximum multimedia limit reached!</strong> You can only add up to 3 multimedia items.
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Branch Form -->
                            @if($section->branches()->count() < $story->branch_count && $story->section_count > $section->section_number)
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
                                        <form action="{{ route('section.store') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="story_id" value="{{ $story->id }}">
                                            <input type="hidden" name="parent_id" value="{{ $section->id }}">
                                            <input type="hidden" name="branch_level" value="{{ $section->branch_level + 1 }}">
                                            <div class="form-group">
                                                <label for="content">Branch Content</label>
                                                <textarea name="content" class="form-control" required></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="multimedia_1">Multimedia (optional)</label>
                                                <input type="file" name="multimedia[]" id="multimedia_1" class="form-control-file">
                                            </div>

                                            <div class="form-group">
                                                <label for="multimedia_2">Multimedia (optional)</label>
                                                <input type="file" name="multimedia[]" id="multimedia_2" class="form-control-file">
                                            </div>

                                            <div class="form-group">
                                                <label for="multimedia_3">Multimedia (optional)</label>
                                                <input type="file" name="multimedia[]" id="multimedia_3" class="form-control-file">
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

            <!-- Option to add new main section if below branch count -->
            @if($story->sections->count() < $story->branch_count)
                <div class="add-section mt-3">
                    <button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#add-section-form">
                        Add New Main Section
                    </button>

                    <!-- Collapsible form -->
                    <div id="add-section-form" class="collapse mt-2">
                        <form action="{{ route('section.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="story_id" value="{{ $story->id }}">
                            <input type="hidden" name="parent_id" value="">
                            <input type="hidden" name="branch_level" value="1"> <!-- Main section starts at level 1 -->
                            <div class="form-group">
                                <label for="content">Section Content</label>
                                <textarea name="content" class="form-control" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="multimedia_1">Multimedia (optional)</label>
                                <input type="file" name="multimedia[]" id="multimedia_1" class="form-control-file">
                            </div>

                            <div class="form-group">
                                <label for="multimedia_2">Multimedia (optional)</label>
                                <input type="file" name="multimedia[]" id="multimedia_2" class="form-control-file">
                            </div>

                            <div class="form-group">
                                <label for="multimedia_3">Multimedia (optional)</label>
                                <input type="file" name="multimedia[]" id="multimedia_3" class="form-control-file">
                            </div>
                            <button type="submit" class="btn btn-success">Create Section</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-info mt-3">
                    You have reached the maximum number of main sections ({{ $story->branch_count }}) for this story.
                </div>
            @endif
        @endif
    </div>

    @include('frontend.partials.footer')

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
