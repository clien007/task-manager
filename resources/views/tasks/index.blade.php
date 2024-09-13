@extends('layouts.app')

@section('title', __('messages.dashboard'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center my-6">
    <h1 class="text-2xl font-bold text-gray-800">{{ __('messages.task') }}</h1>
    <a href="{{ route('tasks.create', ['locale' => app()->getLocale()]) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
        {{ __('messages.add_new_task') }}
    </a>
</div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8 my-6" role="alert">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">    
                <strong class="font-bold">{{ session('success') }}</strong>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" @click="show = false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z"/></svg>
                </span>
            </div>
        </div>
    @endif

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    @include('tasks.filter', ['statuses' => $statuses, 'categories' => $categories])

    <div class="bg-white shadow-md rounded-lg overflow-hidden mt-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.title') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.description') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.status') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.category') }}</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.actions') }}</th>
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
                    <td class="px-6 py-4 text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-4">
                            @if($task->status_id !== 4)
                            <form action="{{ route('tasks.update_status', ['task' => $task->id, 'locale' => app()->getLocale()]) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_status_update') }}');" class="flex items-center">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="next_status" value="{{ $task->next_status }}">
                                <button type="submit" class="text-indigo-600 hover:text-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    {{ __('messages.update_status') }}
                                </button>
                            </form>
                            @endif

                            <a href="{{ route('tasks.edit', ['task' => $task->id, 'locale' => app()->getLocale()]) }}" class="text-green-600 hover:text-green-800 focus:outline-none focus:ring-2 focus:ring-green-500">
                                {{ __('messages.edit') }}
                            </a>

                            <form action="{{ route('tasks.destroy', ['task' => $task->id, 'locale' => app()->getLocale()]) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete') }}');" class="flex items-center">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    {{ __('messages.delete') }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $tasks->appends(request()->query())->links('pagination::tailwind') }}
    </div>
</div>
@endsection
