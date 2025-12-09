<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('予定登録') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Alpine.js で状態管理: isWebMeeting --}}
                    <form method="POST" action="{{ route('schedules.store') }}" x-data="{
                        isWebMeeting: {{ old('is_web_meeting') ? 'true' : 'false' }},
                        isVehicle: {{ old('is_vehicle_reservation') ? 'true' : 'false' }}
                    }">
                        @csrf

                        {{-- (既存) 日付 --}}
                        <div class="mb-4">
                            <x-input-label for="schedule_date" :value="__('日付')" />
                            <x-text-input id="schedule_date" class="block mt-1 w-full" type="date"
                                name="schedule_date" :value="old('schedule_date', request('date'))" required />
                            <x-input-error :messages="$errors->get('schedule_date')" class="mt-2" />
                        </div>

                        {{-- (既存) 時間 --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">

                            {{-- 開始時間 --}}
                            <div x-data="{
                                formatTime(e) {
                                    let val = e.target.value.replace(/[^0-9]/g, ''); // 数字以外を除去
                                    if (val.length === 3) val = '0' + val; // 3桁なら0埋め (930 -> 0930)
                                    if (val.length === 4) {
                                        let h = val.substr(0, 2);
                                        let m = val.substr(2, 2);
                                        // 簡易バリデーション (24時間、60分以内)
                                        if (h < 24 && m < 60) {
                                            e.target.value = h + ':' + m;
                                        }
                                    }
                                }
                            }">
                                <x-input-label for="start_time" :value="__('開始時間')" />

                                {{-- type="time" を type="text" に変更し、placeholderとx-on:blurを追加 --}}
                                <x-text-input id="start_time" class="block mt-1 w-full" type="text" name="start_time"
                                    :value="old('start_time')" placeholder="0900" maxlength="5" required
                                    @blur="formatTime($event)" />
                                <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                                <p class="text-xs text-gray-500 mt-1">※数字4桁で入力 (例: 0930)</p>
                            </div>

                            {{-- 終了時間 --}}
                            <div x-data="{
                                formatTime(e) {
                                    let val = e.target.value.replace(/[^0-9]/g, '');
                                    if (val.length === 3) val = '0' + val;
                                    if (val.length === 4) {
                                        let h = val.substr(0, 2);
                                        let m = val.substr(2, 2);
                                        if (h < 24 && m < 60) {
                                            e.target.value = h + ':' + m;
                                        }
                                    }
                                }
                            }">
                                <x-input-label for="end_time" :value="__('終了時間')" />

                                {{-- ここも同様に変更 --}}
                                <x-text-input id="end_time" class="block mt-1 w-full" type="text" name="end_time"
                                    :value="old('end_time')" placeholder="1030" maxlength="5" @blur="formatTime($event)" />
                                <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                            </div>
                        </div>

                        {{-- (既存) 行先・件名 --}}
                        <div class="mb-6">
                            <x-input-label for="destination" :value="__('行先・件名')" />
                            <x-text-input id="destination" class="block mt-1 w-full" type="text" name="destination"
                                :value="old('destination')" required placeholder="例: A社訪問、週次定例MTG など" />
                            <x-input-error :messages="$errors->get('destination')" class="mt-2" />
                        </div>

                        <hr class="my-6 border-gray-200">

                        {{--  ここから追加エリア  --}}
                        <h3 class="text-lg font-medium text-gray-900 mb-4">追加設定</h3>

                        {{-- Web会議チェックボックス --}}
                        <div class="mb-4">
                            <label for="is_web_meeting" class="inline-flex items-center cursor-pointer">
                                <input id="is_web_meeting" type="checkbox" name="is_web_meeting" value="1"
                                    x-model="isWebMeeting"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ms-2 text-gray-700 font-bold">Web会議利用</span>
                            </label>
                        </div>

                        {{-- Web会議用入力エリア (スライド表示) --}}
                        <div x-show="isWebMeeting" x-transition.duration.300ms
                            class="bg-blue-50 p-4 rounded-lg mb-6 space-y-4 border border-blue-100">

                            {{-- 会議ツール --}}
                            <div>
                                <x-input-label for="meeting_type" :value="__('会議ツール')" />
                                <select id="meeting_type" name="meeting_type"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">選択してください</option>
                                    <option value="Zoom" {{ old('meeting_type') == 'Zoom' ? 'selected' : '' }}>Zoom
                                    </option>
                                    <option value="Google Meet"
                                        {{ old('meeting_type') == 'Google Meet' ? 'selected' : '' }}>Google Meet
                                    </option>
                                    <option value="その他" {{ old('meeting_type') == 'その他' ? 'selected' : '' }}>その他
                                    </option>
                                </select>
                                <x-input-error :messages="$errors->get('meeting_type')" class="mt-2" />
                            </div>

                            {{-- 会議URL --}}
                            <div>
                                <x-input-label for="meeting_url" :value="__('会議URL')" />
                                <x-text-input id="meeting_url" class="block mt-1 w-full" type="url"
                                    name="meeting_url" :value="old('meeting_url')" placeholder="https://..." />
                                <x-input-error :messages="$errors->get('meeting_url')" class="mt-2" />
                            </div>

                            {{-- 参加者メモ --}}
                            <div>
                                <x-input-label for="participants_memo" :value="__('参加者・メモ')" />
                                <textarea id="participants_memo" name="participants_memo"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    rows="2">{{ old('participants_memo') }}</textarea>
                                <x-input-error :messages="$errors->get('participants_memo')" class="mt-2" />
                            </div>
                        </div>

                        {{-- 社用車チェックボックス --}}
                        <div class="mb-4 mt-6">
                            <input type="hidden" name="is_vehicle_reservation" value="0">
                            <label for="is_vehicle_reservation" class="inline-flex items-center cursor-pointer">
                                <input id="is_vehicle_reservation" type="checkbox" name="is_vehicle_reservation"
                                    value="1" x-model="isVehicle"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ms-2 text-gray-700 font-bold">社用車を利用する</span>
                            </label>
                        </div>

                        {{-- 社用車選択エリア (スライド表示) --}}
                        <div x-show="isVehicle" x-transition.duration.300ms
                            class="bg-green-50 p-4 rounded-lg mb-6 space-y-4 border border-green-100">
                            <div>
                                <x-input-label for="vehicle_id" :value="__('車両選択')" />
                                <select id="vehicle_id" name="vehicle_id"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">選択してください</option>
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}" {{-- createの場合 --}}
                                            {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                            {{ $vehicle->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('vehicle_id')" class="mt-2" />
                                <p class="text-xs text-gray-500 mt-2">※日時はスケジュールの時間が自動的に適用されます。</p>
                            </div>
                        </div>

                        {{-- 将来ここに「会議室予約」「社用車予約」のチェックボックスが増えます --}}

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('schedules.index') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                                キャンセル
                            </a>
                            <x-primary-button class="ms-3">
                                {{ __('登録する') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
