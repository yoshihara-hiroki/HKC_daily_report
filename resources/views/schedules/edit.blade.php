<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('予定編集') }}
            </h2>
            <a href="{{ route('schedules.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                一覧に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('schedules.update', $schedule) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="schedule_date" class="block text-sm font-medium text-gray-700">日付 <span
                                        class="text-red-500">*</span></label>
                                <input type="date" name="schedule_date" id="schedule_date"
                                    value="{{ old('schedule_date', $schedule->schedule_date->format('Y-m-d')) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('schedule_date')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="destination" class="block text-sm font-medium text-gray-700">行先・目的 <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="destination" id="destination"
                                    value="{{ old('destination', $schedule->destination) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('destination')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700">開始時刻 <span
                                        class="text-red-500">*</span></label>
                                <input type="time" name="start_time" id="start_time"
                                    value="{{ old('start_time', \Carbon\Carbon::parse($schedule->start_time)->format('H:i')) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('start_time')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700">終了時刻</label>
                                <input type="time" name="end_time" id="end_time"
                                    value="{{ old('end_time', $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('end_time')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                更新する
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
