<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('日報詳細') }}
            </h2>
            <div class="flex space-x-2">
                @can('update', $dailyReport)
                    <a href="{{ route('daily-reports.edit', $dailyReport) }}"
                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        編集
                    </a>
                @endcan
                @can('delete', $dailyReport)
                    <form method="POST" action="{{ route('daily-reports.destroy', $dailyReport) }}"
                        onsubmit="return confirm('本当に削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            削除
                        </button>
                    </form>
                @endcan
                <a href="{{ route('daily-reports.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    一覧に戻る
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 成功メッセージ -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <!-- 日報内容 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <!-- ユーザー名と日付 -->
                    <div class="mb-6 pb-4 border-b">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">{{ $dailyReport->user->name }}</h3>
                                <p class="text-gray-600 mt-1">
                                    {{ $dailyReport->report_date->format('Y年m月d日') }}（{{ ['日', '月', '火', '水', '木', '金', '土'][$dailyReport->report_date->dayOfWeek] }}）
                                </p>
                            </div>
                            <div class="text-sm text-gray-500">
                                <p>作成: {{ $dailyReport->created_at->format('Y/m/d H:i') }}</p>
                                @if ($dailyReport->updated_at != $dailyReport->created_at)
                                    <p>更新: {{ $dailyReport->updated_at->format('Y/m/d H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 業務内容 -->
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-700 mb-2">業務内容</h4>
                        <div class="bg-gray-50 rounded p-4">
                            <p class="whitespace-pre-wrap text-gray-800">{{ $dailyReport->business_content }}</p>
                        </div>
                    </div>

                    <!-- 作業予定 -->
                    @if ($dailyReport->work_plan)
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-700 mb-2">作業予定</h4>
                            <div class="bg-gray-50 rounded p-4">
                                <p class="whitespace-pre-wrap text-gray-800">{{ $dailyReport->work_plan }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- 備忘欄 -->
                    @if ($dailyReport->memo)
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-700 mb-2">備忘欄</h4>
                            <div class="bg-gray-50 rounded p-4">
                                <p class="whitespace-pre-wrap text-gray-800">{{ $dailyReport->memo }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- コメント欄（まだ実装しないのでプレースホルダー） -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-700 mb-4">コメント</h4>

                    @if ($dailyReport->comments->count() > 0)
                        <div class="space-y-4 mb-6">
                            @foreach ($dailyReport->comments as $comment)
                                <div class="border-l-4 border-blue-500 bg-gray-50 p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="font-semibold text-gray-800">{{ $comment->user->name }}</span>
                                        <span
                                            class="text-sm text-gray-500">{{ $comment->created_at->format('Y/m/d H:i') }}</span>
                                    </div>
                                    <p class="whitespace-pre-wrap text-gray-700">{{ $comment->comment }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 mb-6">コメントはまだありません。</p>
                    @endif

                    <!-- コメント投稿フォーム（未実装） -->
                    <div class="border-t pt-4">
                        <p class="text-gray-500 text-sm">※ コメント機能は未実装</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>