<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('行先予定一覧') }}
            </h2>
            <a href="{{ route('schedules.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                予定を登録
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($schedules->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            日付</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            時間</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            行先・目的</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            操作</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($schedules as $schedule)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $schedule->schedule_date->format('Y/m/d') }}
                                                ({{ ['日', '月', '火', '水', '木', '金', '土'][$schedule->schedule_date->dayOfWeek] }})
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                                @if ($schedule->end_time)
                                                    - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $schedule->destination }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('schedules.edit', $schedule) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 mr-3">編集</a>
                                                <form action="{{ route('schedules.destroy', $schedule) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('本当に削除しますか？');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900">削除</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $schedules->links() }}
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-4">登録された予定はありません。</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
