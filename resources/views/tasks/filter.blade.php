<form method="GET" action="{{ route('tasks.index', ['locale' => app()->getLocale()]) }}" class="mb-4 flex items-end space-x-4">
    <!-- Status Field -->
    <div class="flex-1">
        <label for="status" class="block text-sm font-medium text-gray-700">{{ __('messages.status') }}</label>
        <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">{{ __('messages.all_statuses') }}</option>
            @foreach ($statuses as $status)
                <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>
                    {{ ucfirst($status->name) }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Category Field -->
    <div class="flex-1">
        <label for="category" class="block text-sm font-medium text-gray-700">{{ __('messages.category') }}</label>
        <select id="category" name="category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">{{ __('messages.all_categories') }}</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Filter Button -->
    <div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">{{ __('messages.filter') }}</button>
    </div>
</form>
