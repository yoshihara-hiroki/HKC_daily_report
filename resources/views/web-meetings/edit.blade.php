<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Web会議予定 編集') }}
            </h2>
            {{-- 一覧に戻る際、その会議の日付を保持 --}}
            <a href="{{ route('web-meetings.index', ['date' => $webMeeting->meeting_date->format('Y-m-d')]) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                一覧に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form method="POST" action="{{ route('web-meetings.update', $webMeeting) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="meeting_date" class="block text-sm font-medium text-gray-700">日付 <span class="text-red-500">*</span></label>
                            <input type="date" name="meeting_date" id="meeting_date" value="{{ old('meeting_date', $webMeeting->meeting_date->format('Y-m-d')) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <x-input-error :messages="$errors->get('meeting_date')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700">開始時間 <span class="text-red-500">*</span></label>
                                <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $webMeeting->start_time->format('H:i')) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                            </div>

                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700">終了時間</label>
                                <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $webMeeting->end_time ? $webMeeting->end_time->format('H:i') : '') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">ツール <span class="text-red-500">*</span></label>
                            <div class="flex space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="meeting_type" value="zoom" class="form-radio text-indigo-600" {{ old('meeting_type', $webMeeting->meeting_type) == 'zoom' ? 'checked' : '' }} required>
                                    <span class="ml-2">Zoom</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="meeting_type" value="google_meet" class="form-radio text-indigo-600" {{ old('meeting_type', $webMeeting->meeting_type) == 'google_meet' ? 'checked' : '' }}>
                                    <span class="ml-2">Google Meet</span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('meeting_type')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">会議名 <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title', $webMeeting->title) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="meeting_url" class="block text-sm font-medium text-gray-700">会議URL</label>
                            <input type="url" name="meeting_url" id="meeting_url" value="{{ old('meeting_url', $webMeeting->meeting_url) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <x-input-error :messages="$errors->get('meeting_url')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <label for="participants_memo" class="block text-sm font-medium text-gray-700">参加者・メモ</label>
                            <textarea name="participants_memo" id="participants_memo" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('participants_memo', $webMeeting->participants_memo) }}</textarea>
                            <x-input-error :messages="$errors->get('participants_memo')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                更新する
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>