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

    <!-- Recursive child sections -->
    @if($section->branches->isNotEmpty())
        <ul class="section-tree">
            @foreach($section->branches as $child)
                @include('stories.partials.section-tree', ['section' => $child])
            @endforeach
        </ul>
    @endif
</li>
