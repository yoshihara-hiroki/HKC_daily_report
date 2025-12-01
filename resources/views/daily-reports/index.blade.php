<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('日報詳細') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- 成功メッセージ -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- 検索フォーム -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('daily-reports.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- 日付選択 -->
                            <div>
                                <label for="report_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    日付
                                </label>
                                <input type="date" name="report_date" id="report_date" value="{{ $selectedDate }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- ユーザー選択 -->
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    ユーザー
                                </label>
                                <select name="user_id" id="user_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- 検索ボタン -->
                            <div class="flex items-end">
                                <button type="submit"
                                    class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    検索
                                </button>
                            </div>
                        </div>

                        <!-- 前日・翌日ボタン -->
                        <div class="flex justify-center space-x-4">

                            <button type="submit" name="report_date"
                                value="{{ \Carbon\Carbon::parse($selectedDate)->subDay()->format('Y-m-d') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                ← 前日
                            </button>

                            <button type="submit" name="report_date" value="{{ now()->format('Y-m-d') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                今日
                            </button>

                            <button type="submit" name="report_date"
                                value="{{ \Carbon\Carbon::parse($selectedDate)->addDay()->format('Y-m-d') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                翌日 →
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($dailyReport)
                <!-- 日報詳細 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <!-- ヘッダー -->
                        <div class="mb-6 pb-4 border-b border-gray-200">
                            <h3 class="text-2xl font-bold text-gray-900">
                                {{ $dailyReport->user->name }}
                            </h3>
                            <p class="text-lg text-gray-600 mt-1">
                                {{ $dailyReport->report_date->format('Y年m月d日 (D)') }}
                            </p>
                        </div>

                        <!-- 業務内容 -->
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                業務内容
                            </h4>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $dailyReport->business_content }}</p>
                            </div>
                        </div>

                        <!-- 作業予定 -->
                        @if($dailyReport->work_plan)
                            <div class="mb-6">
                                <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                        </path>
                                    </svg>
                                    作業予定
                                </h4>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <p class="text-gray-700 whitespace-pre-wrap">{{ $dailyReport->work_plan }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- 備忘欄 -->
                        @if($dailyReport->memo)
                            <div class="mb-6">
                                <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    備忘欄
                                </h4>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <p class="text-gray-700 whitespace-pre-wrap">{{ $dailyReport->memo }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- アクションボタン -->
                        <div class="flex flex-wrap gap-2 mb-6 pb-6 border-b border-gray-200">
                            @can('update', $dailyReport)
                                <a href="{{ route('daily-reports.edit', $dailyReport) }}"
                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    編集
                                </a>
                            @endcan

                            @can('delete', $dailyReport)
                                <form method="POST" action="{{ route('daily-reports.destroy', $dailyReport) }}"
                                    onsubmit="return confirm('本当に削除しますか？')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        削除
                                    </button>
                                </form>
                            @endcan
                        </div>

                        <!-- コメント欄 -->
                        <div class="mt-8 border-t border-gray-200 pt-8">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                    </path>
                                </svg>
                                コメント ({{ $dailyReport->comments->count() }})
                            </h4>

                            <div class="space-y-4 mb-8">
                                @forelse($dailyReport->comments as $comment)
                                    <div x-data="{ editing: false }" class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                        <div x-show="!editing">
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex items-center">
                                                    <span class="font-bold text-gray-800 mr-2">{{ $comment->user->name }}</span>
                                                    <span
                                                        class="text-xs text-gray-500">{{ $comment->created_at->format('Y/m/d H:i') }}</span>
                                                    @if($comment->created_at != $comment->updated_at)
                                                        <span class="text-xs text-gray-400 ml-2">（編集済）</span>
                                                    @endif
                                                </div>

                                                @can('update', $comment)
                                                    <div class="flex space-x-2">
                                                        <button @click="editing = true"
                                                            class="text-sm text-blue-600 hover:text-blue-800">
                                                            編集
                                                        </button>
                                                        <form method="POST"
                                                            action="{{ route('daily-reports.comments.destroy', [$dailyReport, $comment]) }}"
                                                            onsubmit="return confirm('コメントを削除しますか？');" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                                                                削除
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endcan
                                            </div>
                                            <p class="text-gray-700 whitespace-pre-wrap">{{ $comment->comment }}</p>
                                        </div>

                                        @can('update', $comment)
                                            <div x-show="editing" x-cloak>
                                                <form method="POST"
                                                    action="{{ route('daily-reports.comments.update', [$dailyReport, $comment]) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <textarea name="comment" rows="3"
                                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                        required>{{ old('comment', $comment->comment) }}</textarea>
                                                    <div class="mt-2 flex justify-end space-x-2">
                                                        <button type="button" @click="editing = false"
                                                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-sm">
                                                            キャンセル
                                                        </button>
                                                        <button type="submit"
                                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                            更新
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endcan
                                    </div>
                                @empty
                                    <p class="text-gray-500 italic">コメントはまだありません。</p>
                                @endforelse
                            </div>

                            @if(!$dailyReport->comments->contains('user_id', auth()->id()))
                                <div class="bg-white border border-gray-300 rounded-lg p-4">
                                    <h5 class="font-semibold text-gray-700 mb-2">コメント追加</h5>
                                    <form method="POST" action="{{ route('daily-reports.comments.store', $dailyReport) }}">
                                        @csrf
                                        <div class="mb-3">
                                            <textarea name="comment" rows="3"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('comment') border-red-500 @enderror"
                                                placeholder="ここに入力..." required>{{ old('comment') }}</textarea>
                                            @error('comment')
                                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="submit"
                                                class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                                登録
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="text-center text-gray-500 text-sm bg-gray-50 p-2 rounded border border-gray-200">
                                    ※ コメント済みです。修正する場合は自身のコメントの「編集」ボタンを押してください。
                                </div>
                            @endif
                        </div>

                        <!-- 作成・更新日時 -->
                        <div class="mt-6 pt-6 border-t border-gray-200 text-sm text-gray-500 space-y-1">
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                作成日時: {{ $dailyReport->created_at->format('Y年m月d日 H:i') }}
                            </p>
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                更新日時: {{ $dailyReport->updated_at->format('Y年m月d日 H:i') }}
                            </p>
                        </div>
                    </div>
                </div>

            @else
                <!-- 日報が存在しない場合 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 bg-white border-b border-gray-200 text-center">
                        @if($selectedUserId == auth()->id())
                            <a href="{{ route('daily-reports.create', ['date' => $selectedDate]) }}"
                                class="inline-flex items-center bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                    </path>
                                </svg>
                                日報を作成する
                            </a>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>