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

            <!-- Add Branch Option -->
            @if($section->branch_level < $story->branch_count && $story->section_count > $section->section_number)
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
                        <p>You have already liked this section.</p>
                    @endif

                    <!-- Display like count -->
                    <p>{{ $section->likes->count() }} people liked this story branch.</p>
                @endif

            <!-- Multimedia toggle -->
            @if($section->multimedia)
                <div class="toggle-btn mt-2" data-toggle="collapse" data-target="#multimedia-{{ $section->id }}">
                    View Multimedia
                </div>
                <div id="multimedia-{{ $section->id }}" class="collapse multimedia">
                    {!! $section->multimedia !!}
                </div>
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
