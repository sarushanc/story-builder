<!DOCTYPE html>
<html>
<head>
    <title>{{ $story->title }} - Ebook</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Georgia', serif;
            margin: 40px;
            line-height: 1.6;
        }

        h1, h2 {
            text-align: center;
            font-family: 'Times New Roman', serif;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 36px;
            margin-top: 20px;
        }

        h2 {
            font-size: 24px;
        }

        /* Section Layout */
        .section {
            margin-top: 40px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 2px 2px 12px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }

        .section-title {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .content {
            margin-top: 20px;
            text-align: justify;
            text-indent: 50px;
        }

        /* Section Meta */
        .section-meta {
            font-style: italic;
            color: #555;
            margin-bottom: 10px;
        }

        /* Multimedia */
        .multimedia {
            margin-top: 20px;
            text-align: center;
        }

        .multimedia img {
            max-width: 100%;
            height: auto;
            border: 2px solid #ddd;
            padding: 5px;
            margin-bottom: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        /* Table of Contents */
        .table-of-contents {
            margin-bottom: 40px;
            padding: 20px;
            background-color: #f1f1f1;
            border-radius: 10px;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
        }

        .toc-item {
            margin: 5px 0;
            font-size: 16px;
        }

        /* Story Description */
        .story-description {
            font-size: 18px;
            margin-top: 30px;
            text-align: justify;
        }
    </style>
</head>
<body>

    <!-- Story Title -->
    <h1>{{ $story->title }}</h1>
    <p class="section-meta text-center">Created by: {{ $story->user->name }}</p>

    <!-- Story Description -->
    <div class="section">
        <h2>Story Description</h2>
        <div class="story-description">
            {{ $story->description }}
        </div>
    </div>

    <!-- Ancestor Sections -->
    @if($ancestorSections->isNotEmpty())
        <div class="section">
            <h2>Ancestor Sections</h2>
            @foreach($ancestorSections as $index => $ancestor)
                <div class="content">
                    <!-- Section Meta -->
                    <p class="section-meta">
                        Section {{ $index + 1 }} by {{ $ancestor->user->name }} (Branch Level: {{ $ancestor->branch_level }})
                    </p>
                    <!-- Section Content -->
                    <p>{{ $ancestor->content }}</p>

                    <!-- Check and Display Multimedia -->
                    @if($ancestor->multimedias->isNotEmpty())
                        @foreach($ancestor->multimedias as $media)
                            @if(strpos($media->file_type, 'image') !== false)
                                <div class="multimedia">
                                    <img src="{{ Storage::disk('s3')->url($media->file_path) }}" alt="Section Image">
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- Most Liked Section -->
    <div class="section">
        <h2>Conclusion</h2>
        <div class="content">
            <!-- Section Meta -->
            <p class="section-meta">
                Section {{ $mostLikedSection->section_number }} by {{ $mostLikedSection->user->name }} (Branch Level: {{ $mostLikedSection->branch_level }})
            </p>
            <!-- Section Content -->
            <p>{{ $mostLikedSection->content }}</p>

            <!-- Check and Display Multimedia -->
            @if($mostLikedSection->multimedias->isNotEmpty())
                @foreach($mostLikedSection->multimedias as $media)
                    @if(strpos($media->file_type, 'image') !== false)
                        <div class="multimedia">
                            <img src="{{ Storage::disk('s3')->url($media->file_path) }}" alt="Section Image">
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>

    @include('frontend.partials.footer')

</body>
</html>
