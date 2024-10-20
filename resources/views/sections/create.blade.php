<x-app-layout>
    <!-- Header with Breadcrumb -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Story: ') . $story->title }}
            @if($parentSection ?? false)
                <!-- Displaying Hierarchy -->
                @foreach($parentSection->ancestors as $ancestor)
                    > {{ $ancestor->branch_level }}.{{ $ancestor->section_number }}
                @endforeach
                > {{ $parentSection->branch_level }}.{{ $parentSection->section_number }}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">{{ __('Story Details') }}</h3>

                    <!-- Story Title and Multimedia -->
                    <div class="mb-4">
                        <p><strong>{{ __('Title:') }}</strong> {{ $story->title }}</p>
                        @if($story->multimedia)
                            <p><strong>{{ __('Multimedia:') }}</strong>
                                <a href="{{ $story->multimedia }}" target="_blank" class="text-blue-600 hover:text-blue-900">{{ __('View Multimedia') }}</a>
                            </p>
                        @endif
                    </div>

                    <!-- Form to Create Section -->
                    <form action="{{ route('sections.store', $story->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $parent_id ?? '' }}">

                        <!-- Content Input -->
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Content') }}</label>
                            <textarea name="content" id="content" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>{{ old('content') }}</textarea>
                            @error('content')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Multimedia Input -->
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

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">{{ __('Create Section') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Display Sections with Hierarchy -->
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">{{ __('Sections') }}</h3>
                    @if($story->sections->isEmpty())
                        <p>{{ __('No sections available for this story.') }}</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mt-4">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Section Number') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Content') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Branch Level') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($story->sections->where('parent_id', null) as $section)
                                    @include('sections.partials.section_row', ['section' => $section, 'level' => 0])
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert Delete Confirmation -->
    <script>
        document.querySelectorAll('.delete-section').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                let form = this.closest('.delete-section-form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>
