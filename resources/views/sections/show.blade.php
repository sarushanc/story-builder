<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Section Details for: ') . $story->title }}
        </h2>
    </x-slot>

    <!-- Breadcrumb -->
    <div class="py-4 px-6 bg-gray-200 dark:bg-gray-700">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('stories.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-white">
                        {{ __('Stories') }}
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 011 1v5h3a1 1 0 110 2H7v5a1 1 0 11-2 0V4a1 1 0 011-1zm4.707 7.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L14 11.586V4a1 1 0 10-2 0v7.586l-1.293-1.293z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('stories.branches', $story->id) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-white">
                            {{ $story->title }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.894.553l6 12A1 1 0 0116 17H4a1 1 0 01-.894-1.447l6-12A1 1 0 0110 3zM9.293 13.293a1 1 0 001.414 1.414l3-3a1 1 0 00-1.414-1.414L11 11.586V6a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Section Details') }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Section Details -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">{{ $section->content }}</h3>
                    <p>{{ $section->multimedia }}</p>
                </div>
            </div>

            <!-- Display Child Sections -->
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">{{ __('Child Sections') }}</h3>
                    @if($section->children->isEmpty())
                        <p>{{ __('No child sections available for this section.') }}</p>
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
                                @foreach($section->children as $childSection)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                            {{ $childSection->section_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ Str::limit($childSection->content, 50) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $childSection->branch_level }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-4">
                                                <!-- View Button -->
                                                <a href="{{ route('sections.show', ['story' => $story->id, 'section' => $childSection->id]) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                    {{ __('View') }}
                                                </a>
                                                <!-- Edit Button -->
                                                <a href="{{ route('sections.edit', ['story' => $story->id, 'section' => $childSection->id]) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    {{ __('Edit') }}
                                                </a>
                                                <!-- Delete Button -->
                                                <form action="{{ route('sections.destroy', ['story' => $story->id, 'section' => $childSection->id]) }}" method="POST" class="delete-section-form">
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

            <!-- Create Child Section Form -->
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">{{ __('Create Child Section') }}</h3>
                    <form action="{{ route('sections.store', $story->id) }}" method="POST">
                        @csrf

                        <!-- Hidden Parent Section Field -->
                        <input type="hidden" name="parent_id" value="{{ $section->id }}">

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
        </div>
    </div>
</x-app-layout>
