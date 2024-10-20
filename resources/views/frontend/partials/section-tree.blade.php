<li>
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

            @if($section->multimedias->isNotEmpty())
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
                        </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Add Branch Option -->
            @if($section->branches()->count() < $story->branch_count && $story->section_count > $section->section_number)
                <div class="add-branch mt-2">
                    @if($section->section_number == $story->section_count - 1)
                        <div class="alert alert-info mt-2">
                            You have reached the end of the sections for this story! Consider adding a conclusion.
                        </div>
                    @endif
                    <button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#add-branch-form-{{ $section->id }}">
                        Add Branch
                    </button>

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
                    <p>You have already liked this section.</p>
                @endif

                <!-- Display like count -->
                <p>{{ $section->likes->count() }} people liked this story branch.</p>
            @endif
        </div>
    </div>

    <!-- Recursive child sections -->
    @if($section->branches->isNotEmpty())
        <ul class="section-tree">
            @foreach($section->branches as $child)
                @include('frontend.partials.section-tree', ['section' => $child])
            @endforeach
        </ul>
    @endif
</li>
