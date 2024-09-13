@extends('layouts.app')

@section('title', __('messages.archived_tasks'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center my-6">
    <h1 class="text-2xl font-bold text-gray-800">{{ __('messages.archived_tasks') }}</h1>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Task Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden mt-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.title') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.description') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.status') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.category') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.archive_completion_date') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.archive_date') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($tasks as $task)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $task->title }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        <div class="truncate max-w-xs">{{ $task->description }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ ucfirst($task->status->name) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $task->category->name ?? 'None' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ date('F j, Y', strtotime($task->completion_date)) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ date('F j, Y', strtotime($task->created_at)) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-4">
        {{ $tasks->appends(request()->query())->links('pagination::tailwind') }}
    </div>
</div>
@endsection
