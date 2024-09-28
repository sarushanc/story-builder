<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Edit User Form -->
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('name')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('email')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('New Password') }}</label>
                            <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">
                            @error('password')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                            <p class="text-sm text-gray-500 dark:text-gray-400">Leave blank if you don't want to change the password.</p>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Confirm New Password') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">
                        </div>

                        <div class="mb-4">
                            <label for="isAdmin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Admin') }}</label>
                            <input type="checkbox" name="isAdmin" id="isAdmin" value="1" {{ old('isAdmin', $user->isAdmin) ? 'checked' : '' }} class="mt-1 block dark:bg-gray-700 dark:text-gray-300">
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Check this box if the user is an admin') }}</span>
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
