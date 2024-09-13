@extends('layouts.app')

@section('title', __('messages.dashboard'))

@section('content')
<div x-data="taskForm()">
    <!-- Check for success message from session -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" class="max-w-7xl mx-auto py-4 my-6" role="alert">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">    
                <strong class="font-bold">{{ session('success') }}</strong>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" @click="show = false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z"/></svg>
                </span>
            </div>
        </div>
    @endif

    <form action="{{ isset($task->id) ? route('tasks.update', ['locale' => app()->getLocale(), 'task' => $task->id] ) : route('tasks.store', ['locale' => app()->getLocale()] ) }}" method="POST" @submit.prevent="validateForm()">
    @csrf
        @if(isset($task->id))
            @method('PUT')
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-6">
            <h1 class="text-2xl font-bold text-gray-800">
                {{ isset($task->id) ? __('messages.update_task', ['title' => ucfirst($task->title)]) : __('messages.add_new_task_title') }}
            </h1>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8 bg-white shadow-md rounded-lg">
            <!-- Title Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">{{ __('messages.title') }}</label>
                <input type="text" name="title" x-model="form.title" value="{{ old('title', $task->title ?? '') }}" minlength="3" maxlength="255" required
                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                <span x-show="errors.title" class="text-red-600 text-sm" x-text="errors.title"></span>
            </div>

            <!-- Description Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">{{ __('messages.description') }}</label>
                <textarea name="description" x-model="form.description" required minlength="3" maxlength="255"
                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">{{ old('description', $task->description ?? '') }}</textarea>
                @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                <span x-show="errors.description" class="text-red-600 text-sm" x-text="errors.description"></span>
            </div>

            <!-- Category Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">{{ __('messages.category') }}</label>
                <select name="category_id" x-model="form.category_id" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">{{ __('messages.select_category') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @if (old('category_id', $task->category_id ?? '') == $category->id) selected @endif>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                <span x-show="errors.category_id" class="text-red-600 text-sm" x-text="errors.category_id"></span>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-between mt-6">
                <a href="{{ route('tasks.index', ['locale' => app()->getLocale()]) }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-red-500">
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    {{ isset($task->id) ? __('messages.update') : __('messages.save') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
    function taskForm() {
        return {
            form: {
                title: '{{ old('title', $task->title ?? '') }}',
                description: '{{ old('description', $task->description ?? '') }}',
                category_id: '{{ old('category_id', $task->category_id ?? '') }}',
            },
            errors: {},
            validateForm() {
                this.errors = {};

                if (!this.form.title.trim()) {
                    this.errors.title = '{{ __("messages.title_required") }}';
                } else if (this.form.title.length < 3 || this.form.title.length > 255) {
                    this.errors.title = '{{ __("messages.title_length") }}';
                }

                if (!this.form.description.trim()) {
                    this.errors.description = '{{ __("messages.description_required") }}';
                } else if (this.form.description.length < 3 || this.form.description.length > 255) {
                    this.errors.description = '{{ __("messages.description_length") }}';
                }

                if (!this.form.category_id) {
                    this.errors.category_id = '{{ __("messages.category_required") }}';
                }

                if (Object.keys(this.errors).length > 0) {
                    return;
                }

                this.$el.submit();
            }
        }
    }
</script>
@endsection
