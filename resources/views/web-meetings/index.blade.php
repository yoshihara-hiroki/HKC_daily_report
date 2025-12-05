<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Web会議予定') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" action="{{ route('web-meetings.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">日付</label>
                        <input type="date" name="date" id="date" value="{{ $date ?? now()->format('Y-m-d') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700">社員</label>
                        <select name="user_id" id="user_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">全ての社員</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        検索
                    </button>
                    
                    <a href="{{ route('web-meetings.index') }}" class="text-gray-600 hover:text-gray-900 py-2 px-2">
                        クリア
                    </a>

                    <div class="ml-auto">
                        <a href="{{ route('web-meetings.create', ['date' => $date ?? now()->format('Y-m-d')]) }}" 
                           class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            + 新規登録
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($webMeetings->isEmpty())
                        <p class="text-center text-gray-500 py-4">予定はありません。</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">日付/時間</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ツール</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">会議名/URL</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">登録者</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($webMeetings as $meeting)
                                        <tr>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900">
                                                    {{ $meeting->meeting_date->format('Y/m/d') }} ({{ $meeting->meeting_date->isoFormat('ddd') }})
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $meeting->start_time->format('H:i') }}
                                                    @if($meeting->end_time)
                                                        - {{ $meeting->end_time->format('H:i') }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $meeting->meeting_type === 'zoom' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $meeting->meeting_type === 'zoom' ? 'Zoom' : 'Google Meet' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $meeting->title }}</div>
                                                @if($meeting->meeting_url)
                                                    <a href="{{ $meeting->meeting_url }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-900 flex items-center mt-1">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                        会議に参加
                                                    </a>
                                                @endif
                                                @if($meeting->participants_memo)
                                                    <div class="text-xs text-gray-500 mt-1 whitespace-pre-wrap">{{ $meeting->participants_memo }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $meeting->user->name }}</div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                                @can('update', $meeting)
                                                    <a href="{{ route('web-meetings.edit', $meeting) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">編集</a>
                                                @endcan
                                                @can('delete', $meeting)
                                                    <form action="{{ route('web-meetings.destroy', $meeting) }}" method="POST" class="inline-block" onsubmit="return confirm('本当に削除しますか？');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">削除</button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>