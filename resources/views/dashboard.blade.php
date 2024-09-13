@extends('layouts.app')

@section('title', __('messages.dashboard'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">{{ __('messages.dashboard') }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Task Statistics by Status Card -->
        <div class="bg-blue-50 shadow-lg overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 bg-blue-100 sm:px-6">
                <h3 class="text-lg font-medium leading-6 text-blue-900">{{ __('messages.task_statistics_by_status') }}</h3>
            </div>
            <div class="border-t border-blue-200">
                <dl>
                    @foreach ($taskCountsByStatus as $status => $count)
                        <div class="bg-blue-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-blue-600">{{ ucfirst($status) }}</dt>
                            <dd class="mt-1 text-sm text-blue-900 sm:col-span-2 sm:mt-0 text-right">{{ $count }} {{ __('messages.tasks') }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>

        <!-- Task Statistics by Category Card -->
        <div class="bg-indigo-50 shadow-lg overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 bg-indigo-100 sm:px-6">
                <h3 class="text-lg font-medium leading-6 text-indigo-900">{{ __('messages.task_statistics_by_recent_category') }}</h3>
            </div>
            <div class="border-t border-indigo-200">
                <dl>
                    @foreach ($taskCountsByCategory as $category => $count)
                        <div class="bg-indigo-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-indigo-600">{{ ucfirst($category) }}</dt>
                            <dd class="mt-1 text-sm text-indigo-900 sm:col-span-2 sm:mt-0 text-right">{{ $count }} {{ __('messages.tasks') }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>

        <!-- Recent Tasks Card -->
        <div class="bg-white shadow-lg overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 bg-gray-100 sm:px-6">
                <h3 class="text-lg font-medium leading-6 text-gray-800">{{ __('messages.recent_tasks') }}</h3>
            </div>
            <div class="border-t border-gray-200">
                <ul role="list" class="divide-y divide-gray-200">
                    @foreach ($recentTasks as $task)
                        <li class="py-3 px-3 flex items-center justify-between text-sm">
                            <div class="flex w-0 flex-1 items-center">
                                <span class="ml-2 w-0 flex-1 truncate">{{ $task->title }}</span>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $task->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Archived Tasks Card -->
        <div class="bg-yellow-50 shadow-lg overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 bg-yellow-100 sm:px-6">
                <h3 class="text-lg font-medium leading-6 text-yellow-900">{{ __('messages.archived_tasks') }}</h3>
            </div>
            <div class="border-t border-yellow-200">
                <div class="bg-yellow-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-yellow-600">{{ __('messages.archive') }}</dt>
                    <dd class="mt-1 text-sm text-yellow-900 sm:col-span-2 sm:mt-0 text-right">{{ $archivedTasks }} {{ __('messages.tasks') }}</dd>
                </div>
            </div>
        </div>

        <!-- Near Archive Tasks Card -->
        <div class="bg-red-50 shadow-lg overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 bg-red-100 sm:px-6">
                <h3 class="text-lg font-medium leading-6 text-red-900">{{ __('messages.near_archive_tasks') }}</h3>
            </div>
            <div class="border-t border-red-200">
                <div class="bg-red-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-red-600">{{ __('messages.near_archive') }}</dt>
                    <dd class="mt-1 text-sm text-red-900 sm:col-span-2 sm:mt-0 text-right">{{ $nearArchivedTasks }} {{ __('messages.tasks') }}</dd>
                </div>
            </div>
        </div>

    </div> <!-- End of Grid -->
</div>
@endsection
