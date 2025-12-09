<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $currentDate->format('Y年n月') }} の予定
            </h2>

            {{-- 部署絞り込みフォーム --}}
            <form method="GET" action="{{ route('schedules.calendar') }}" class="flex items-center">
                <input type="hidden" name="date" value="{{ $currentDate->format('Y-m-d') }}">
                <select name="group_id" onchange="this.form.submit()"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                    <option value="">全ての部署</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}" {{ $selectedGroupId == $group->id ? 'selected' : '' }}>
                            {{ $group->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- 月移動ボタン --}}
        <div class="flex justify-between mt-4">
            <a href="{{ route('schedules.calendar', ['date' => $currentDate->copy()->subMonth()->format('Y-m-d'), 'group_id' => $selectedGroupId]) }}"
                class="text-gray-600 hover:text-gray-900">
                &laquo; 前月
            </a>
            <a href="{{ route('schedules.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                自分の予定リストへ
            </a>
            <a href="{{ route('schedules.calendar', ['date' => $currentDate->copy()->addMonth()->format('Y-m-d'), 'group_id' => $selectedGroupId]) }}"
                class="text-gray-600 hover:text-gray-900">
                翌月 &raquo;
            </a>
        </div>
    </x-slot>

    {{-- Alpine.js コンポーネント --}}
    {{-- ★修正点: $schedulesByDate を $events に変更し、初期データとして渡す --}}
    <div class="py-6" x-data="calendarApp({{ json_encode($events) }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6">

                {{-- カレンダー本体 --}}
                <div class="lg:w-2/3 bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <div class="grid grid-cols-7 border-b border-gray-200 mb-2">
                        @foreach (['日', '月', '火', '水', '木', '金', '土'] as $dayOfWeek)
                            <div class="text-center font-bold text-gray-500 text-sm py-2">{{ $dayOfWeek }}</div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-7 gap-1">
                        {{-- 空白セル（月の開始曜日まで） --}}
                        @for ($i = 0; $i < $startDate->dayOfWeek; $i++)
                            <div class="h-24 bg-gray-50 rounded"></div>
                        @endfor

                        {{-- 日付セル --}}
                        @for ($day = $startDate->copy(); $day->lte($endDate); $day->addDay())
                            @php
                                $dateStr = $day->format('Y-m-d');
                                $isToday = $day->isToday();
                                $isCurrentMonth = $day->month === $currentDate->month; // 当月判定を追加
                            @endphp
                            <div @click="selectDate('{{ $dateStr }}')"
                                class="h-16 sm:h-24 border p-1 cursor-pointer transition hover:bg-blue-50 flex flex-col justify-between
                                {{ $isCurrentMonth ? 'bg-white border-gray-300' : 'bg-gray-100 border-gray-200 text-gray-400' }}"
                                :class="{ 'ring-2 ring-inset ring-blue-500': selectedDate === '{{ $dateStr }}' }">
                                <div class="text-right">
                                    <span
                                        class="text-sm font-semibold p-1 rounded-full w-7 h-7 inline-flex items-center justify-center
                                        {{ $isToday ? 'bg-blue-500 text-white' : '' }}">
                                        {{ $day->day }}
                                    </span>
                                </div>

                                {{-- 予定件数表示 (Alpine.jsで制御: 人型アイコン + 件数) --}}
                                <div class="mt-1 text-center" x-show="getEvents('{{ $dateStr }}').length > 0">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        <svg class="mr-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                                        </svg>
                                        <span x-text="getEvents('{{ $dateStr }}').length"></span>
                                    </span>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- 選択日の詳細リスト --}}
                <div class="lg:w-1/3 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900" x-text="formatDate(selectedDate)"></h3>

                        {{-- 新規登録ボタンに日付パラメータを付与 --}}
                        <a :href="`{{ route('schedules.create') }}?date=${selectedDate}`"
                            class="inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            + 予定登録
                        </a>
                    </div>

                    <div class="space-y-4 overflow-y-auto max-h-[600px]">
                        <template x-if="selectedEvents.length === 0">
                            <p class="text-gray-500 text-center py-4">予定はありません</p>
                        </template>

                        <template x-for="event in selectedEvents" :key="event.id">
                            <div class="border-l-4 p-3 bg-gray-50 rounded-r shadow-sm hover:bg-gray-100 transition"
                                :class="event.is_web_meeting ? 'border-blue-500' : 'border-gray-300'">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="text-xs text-gray-500 mb-1">
                                            <span x-text="event.time"></span>
                                            <span x-show="event.end_time"> - <span
                                                    x-text="event.end_time"></span></span>
                                        </div>
                                        <div class="font-bold text-gray-900 mb-1" x-text="event.user_name"></div>
                                        <div class="text-sm text-gray-700">
                                            <span x-text="event.title"></span>

                                            {{-- Web会議バッジ --}}
                                            <template x-if="event.is_web_meeting">
                                                <span
                                                    class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    <span x-text="event.meeting_type || 'Web会議'"></span>
                                                </span>
                                            </template>

                                            {{-- 社用車バッジ --}}
                                            <template x-if="event.vehicle_name">
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    {{-- 車アイコン --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                    </svg>
                                                    <span x-text="event.vehicle_name"></span>
                                                </span>
                                            </template>
                                        </div>
                                    </div>

                                    {{-- 編集リンク (本人の場合のみ表示するなどの制御はPHP側で行うか、JSにIDを渡して判定) --}}
                                    @auth
                                        <template x-if="{{ auth()->id() }} === event.user_id">
                                            <a :href="`/schedules/${event.id}/edit`"
                                                class="text-gray-400 hover:text-indigo-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>
                                        </template>
                                    @endauth
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alpine.js ロジック --}}
    <script>
        function calendarApp(eventsData) {
            return {
                events: eventsData, // コントローラーから渡された整形済みデータ
                selectedDate: '{{ $currentDate->format('Y-m-d') }}', // 初期選択日は現在表示中の月の日付にする

                // 選択された日のイベントリスト（computed property的に使う）
                get selectedEvents() {
                    return this.getEvents(this.selectedDate).sort((a, b) => {
                        return a.time.localeCompare(b.time);
                    });
                },

                // 指定日のイベントを取得する関数（filterを使用）
                getEvents(date) {
                    return this.events.filter(event => event.start === date);
                },

                // 日付を選択する
                selectDate(date) {
                    this.selectedDate = date;
                },

                // 日付フォーマット (YYYY年M月D日)
                formatDate(dateStr) {
                    const d = new Date(dateStr);
                    return `${d.getFullYear()}年${d.getMonth() + 1}月${d.getDate()}日`;
                }
            }
        }
    </script>
</x-app-layout>
