<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Section for: ') . $story->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Create Section Form -->
                    <form action="{{ route('sections.store', $story->id) }}" method="POST">
                        @csrf

                        <!-- Hidden Parent Section Field -->
                        <input type="hidden" name="parent_id" value="{{ $parent_id ?? '' }}">

                        <!-- Content -->
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Content') }}</label>
                            <textarea name="content" id="content" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>{{ old('content') }}</textarea>
                            @error('content')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Multimedia (Optional) -->
                        <div class="mb-4">
                            <label for="multimedia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Multimedia') }}</label>
                            <input type="text" name="multimedia" id="multimedia" value="{{ old('multimedia') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">
                            @error('multimedia')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">{{ __('Create Section') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Display All Sections -->
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
                                @foreach($story->sections as $section)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                            {{ $section->section_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ Str::limit($section->content, 50) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $section->branch_level }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-4">
                                                <!-- View Button -->
                                                <a href="{{ route('sections.show', ['story' => $story->id, 'section' => $section->id]) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                    {{ __('View') }}
                                                </a>
                                                <!-- Show Sections Button -->
                                                <a href="{{ route('sections.branches', ['story' => $story->id, 'section' => $section->id]) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                    {{ __('Show Sections') }}
                                                </a>
                                                <!-- Edit Button -->
                                                <a href="{{ route('sections.edit', ['story' => $story->id, 'section' => $section->id]) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    {{ __('Edit') }}
                                                </a>
                                                <!-- Delete Button -->
                                                <form action="{{ route('sections.destroy', ['story' => $story->id, 'section' => $section->id]) }}" method="POST" class="delete-section-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 delete-section">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
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
