<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Story') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Story creation form --}}
                    <form action="{{ route('stories.store') }}" method="POST">
                        @csrf

                        <!-- User Selection -->
                        <div class="mb-4">
                            <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Select User') }}</label>
                            <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                                <option value="" disabled selected>{{ __('Choose a user') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Title Input -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title') }}</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('title')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description Input -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
                            <textarea name="description" id="description" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Branch Count Input -->
                        <div class="mb-4">
                            <label for="branch_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Branch Count') }}</label>
                            <input type="number" name="branch_count" id="branch_count" value="{{ old('branch_count') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('branch_count')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Section Count Input -->
                        <div class="mb-4">
                            <label for="section_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Section Count') }}</label>
                            <input type="number" name="section_count" id="section_count" value="{{ old('section_count') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('section_count')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Multimedia Input -->
                        <div class="mb-4">
                            <label for="multimedia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Multimedia') }}</label>
                            <input type="text" name="multimedia" id="multimedia" value="{{ old('multimedia') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">
                            @error('multimedia')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
