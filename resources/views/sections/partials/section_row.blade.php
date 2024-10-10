<tr>
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200" style="padding-left: {{ $level * 20 }}px;">
        {{ $section->section_number }}.{{ $section->branch_level }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
        {{ Str::limit($section->content, 50) }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
        {{ $section->branch_level }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        <div class="flex space-x-4">
            <a href="{{ route('sections.show', ['story' => $section->story_id, 'section' => $section->id]) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                {{ __('View') }}
            </a>
            <a href="{{ route('sections.edit', ['story' => $section->story_id, 'section' => $section->id]) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                {{ __('Edit') }}
            </a>
            <form action="{{ route('sections.destroy', ['story' => $section->story_id, 'section' => $section->id]) }}" method="POST" class="delete-section-form">
                @csrf
                @method('DELETE')
                <button type="button" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 delete-section">
                    {{ __('Delete') }}
                </button>
            </form>
        </div>
    </td>
</tr>

<!-- Render child sections recursively -->
@foreach($section->branches as $childSection)
    @include('sections.partials.section_row', ['section' => $childSection, 'level' => $level + 1])
@endforeach
