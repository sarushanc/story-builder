<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Story') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Edit Story Form -->
                    <form action="{{ route('stories.update', $story->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title') }}</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $story->title) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('title')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
                            <textarea name="description" id="description" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">{{ old('description', $story->description) }}</textarea>
                            @error('description')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="branch_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Branch Count') }}</label>
                            <input type="number" name="branch_count" id="branch_count" value="{{ old('branch_count', $story->branch_count) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('branch_count')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="section_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Section Count') }}</label>
                            <input type="number" name="section_count" id="section_count" value="{{ old('section_count', $story->section_count) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('section_count')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="multimedia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Multimedia') }}</label>
                            <input type="text" name="multimedia" id="multimedia" value="{{ old('multimedia', $story->multimedia) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">
                            @error('multimedia')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                            <p class="text-sm text-gray-500 dark:text-gray-400">Leave blank if you don't want to change the multimedia.</p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
