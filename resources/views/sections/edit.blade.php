<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Section for: ') . $story->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Edit Section Form -->
                    <form action="{{ route('sections.update', $section->id) }}" method="POST"> <!-- Ensure the story ID is part of the route -->
                        @csrf
                        @method('PUT')

                        <!-- Parent Section (Optional) -->
                        <div class="mb-4">
                            <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Parent Section') }}</label>
                            <select name="parent_id" id="parent_id" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">
                                <option value="">{{ __('No Parent (Direct Branch)') }}</option> <!-- Allow direct branch selection -->
                                @foreach($story->sections as $parent)
                                    <!-- Exclude the current section and its children to avoid circular references -->
                                    @if ($parent->id !== $section->id && $parent->parent_id !== $section->id)
                                        <option value="{{ $parent->id }}" {{ old('parent_id', $section->parent_id) == $parent->id ? 'selected' : '' }}>
                                            {{ __('Branch: ') . $parent->content }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('parent_id')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Content') }}</label>
                            <textarea name="content" id="content" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>{{ old('content', $section->content) }}</textarea>
                            @error('content')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Multimedia (Optional) -->
                        <div class="mb-4">
                            <label for="multimedia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Multimedia') }}</label>
                            <input type="text" name="multimedia" id="multimedia" value="{{ old('multimedia', $section->multimedia) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">
                            @error('multimedia')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">{{ __('Update Section') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
