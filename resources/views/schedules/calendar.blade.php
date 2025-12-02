<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('行先予定カレンダー') }}
            </h2>

            <form method="GET" action="{{ route('schedules.calendar') }}"
                class="flex flex-col sm:flex-row items-center gap-4">
                <input type="hidden" name="date" value="{{ $currentDate->format('Y-m-d') }}">

                <select name="group_id" onchange="this.form.submit()"
                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="">全ての部署</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}" {{ $selectedGroupId == $group->id ? 'selected' : '' }}>
                            {{ $group->name }}
                        </option>
                    @endforeach
                </select>

                <div class="flex items-center space-x-2">
                    <a href="{{ route('schedules.calendar', ['date' => $currentDate->copy()->subMonth()->format('Y-m-d'), 'group_id' => $selectedGroupId]) }}"
                        class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-3 border border-gray-400 rounded shadow text-sm">
                        &lt;
                    </a>

                    <span class="text-lg font-bold text-gray-700 whitespace-nowrap min-w-[100px] text-center">
                        {{ $currentDate->format('Y年n月') }}
                    </span>

                    <a href="{{ route('schedules.calendar', ['date' => $currentDate->copy()->addMonth()->format('Y-m-d'), 'group_id' => $selectedGroupId]) }}"
                        class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-3 border border-gray-400 rounded shadow text-sm">
                        &gt;
                    </a>
                </div>
            </form>
        </div>
    </x-slot>

    <div class="py-6" x-data="calendarApp({{ json_encode($schedulesByDate) }}, '{{ now()->format('Y-m-d') }}')">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4 text-right">
                <a :href="'{{ route('schedules.create') }}?date=' + selectedDate"
                    class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                    <span x-text="formatDate(selectedDate) + ' の予定を登録'"></span>
                </a>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">

                <div class="lg:w-2/3 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 bg-white border-b border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse border border-gray-200 table-fixed">
                                <thead>
                                    <tr>
                                        @foreach (['日', '月', '火', '水', '木', '金', '土'] as $dayOfWeek)
                                            <th
                                                class="border border-gray-300 bg-gray-100 py-2 text-center text-sm
                                                {{ $dayOfWeek === '日' ? 'text-red-600' : ($dayOfWeek === '土' ? 'text-blue-600' : 'text-gray-700') }}">
                                                {{ $dayOfWeek }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $dayCursor = $startDate->copy(); @endphp
                                    @while ($dayCursor <= $endDate)
                                        <tr>
                                            @for ($i = 0; $i < 7; $i++)
                                                @php
                                                    $dateStr = $dayCursor->format('Y-m-d');
                                                    $isCurrentMonth = $dayCursor->month === $currentDate->month;
                                                    $isToday = $dateStr === now()->format('Y-m-d');
                                                    // その日の予定数
                                                    $count = isset($schedulesByDate[$dateStr])
                                                        ? count($schedulesByDate[$dateStr])
                                                        : 0;
                                                @endphp

                                                <td @click="selectDate('{{ $dateStr }}')"
                                                    class="border border-gray-300 align-top h-16 sm:h-24 p-1 cursor-pointer transition hover:bg-blue-50
                                                    {{ $isCurrentMonth ? 'bg-white' : 'bg-gray-100 text-gray-400' }}
                                                    "
                                                    :class="{ 'ring-2 ring-inset ring-blue-500': selectedDate === '{{ $dateStr }}' }">

                                                    <div class="flex flex-col h-full justify-between">
                                                        <div class="text-right">
                                                            <span
                                                                class="text-sm font-semibold p-1 rounded-full w-7 h-7 inline-flex items-center justify-center
                                                                {{ $isToday ? 'bg-blue-500 text-white' : '' }}">
                                                                {{ $dayCursor->day }}
                                                            </span>
                                                        </div>

                                                        @if ($count > 0)
                                                            <div class="mt-1 text-center">
                                                                <span
                                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                                    <svg class="mr-1 w-3 h-3" fill="currentColor"
                                                                        viewBox="0 0 20 20">
                                                                        <path
                                                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z">
                                                                        </path>
                                                                    </svg>
                                                                    {{ $count }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                @php $dayCursor->addDay(); @endphp
                                            @endfor
                                        </tr>
                                    @endwhile
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-right sm:hidden">
                            <a href="{{ route('schedules.create') }}"
                                class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm w-full text-center">
                                予定を登録
                            </a>
                        </div>
                    </div>
                </div>

                <div
                    class="lg:w-1/3 bg-white overflow-hidden shadow-sm sm:rounded-lg h-auto lg:h-screen lg:sticky lg:top-6">
                    <div class="p-4 bg-white border-b border-gray-200 h-full flex flex-col">

                        <div class="mb-4 pb-2 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                                <span x-text="formatDate(selectedDate)"></span>
                                <span class="ml-2 text-sm font-normal text-gray-500">の予定</span>
                            </h3>
                        </div>

                        <div class="flex-1 overflow-y-auto">
                            <template x-if="currentSchedules.length > 0">
                                <div class="space-y-3">
                                    <template x-for="schedule in currentSchedules" :key="schedule.id">
                                        <div class="p-3 rounded-lg border border-gray-200 hover:shadow-sm transition"
                                            :class="schedule.user_id === {{ auth()->id() }} ? 'bg-blue-50 border-blue-200' :
                                                'bg-white'">

                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="text-xs text-gray-500 mb-1"
                                                        x-text="formatTime(schedule.start_time) + (schedule.end_time ? ' - ' + formatTime(schedule.end_time) : '')">
                                                    </p>
                                                    <p class="font-bold text-gray-900" x-text="schedule.user.name"></p>
                                                </div>

                                                <template x-if="schedule.user_id === {{ auth()->id() }}">
                                                    <div class="flex space-x-1">
                                                        <a :href="'/schedules/' + schedule.id + '/edit'"
                                                            class="text-indigo-600 hover:text-indigo-900 p-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </template>
                                            </div>

                                            <div class="mt-2 text-sm text-gray-700">
                                                <span class="font-semibold mr-1">行先:</span>
                                                <span x-text="schedule.destination"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <template x-if="currentSchedules.length === 0">
                                <div class="text-center py-10 text-gray-400">
                                    <svg class="mx-auto h-12 w-12 mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p>予定はありません</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function calendarApp(schedulesData, todayDate) {
            return {
                schedules: schedulesData,
                selectedDate: todayDate,

                selectDate(date) {
                    this.selectedDate = date;
                },

                get currentSchedules() {
                    return this.schedules[this.selectedDate] || [];
                },

                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const date = new Date(dateStr);
                    const days = ['日', '月', '火', '水', '木', '金', '土'];
                    return `${date.getMonth() + 1}/${date.getDate()} (${days[date.getDay()]})`;
                },

                formatTime(timeStr) {
                    if (!timeStr) return '';
                    return timeStr.substring(0, 5);
                }
            }
        }
    </script>
</x-app-layout>
