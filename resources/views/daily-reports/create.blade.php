<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('日報作成') }}
            </h2>
            <a href="{{ route('daily-reports.index', ['report_date' => $defaultDate, 'user_id' => auth()->id()]) }}" 
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                一覧に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('daily-reports.store') }}">
                        @csrf

                        <!-- 日付 -->
                        <div class="mb-4">
                            <label for="report_date" class="block text-sm font-medium text-gray-700">
                                日付 <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="report_date" id="report_date"
                                value="{{ old('report_date', $defaultDate) }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('report_date') border-red-500 @enderror">
                            @error('report_date')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 業務内容 -->
                        <div class="mb-4">
                            <label for="business_content" class="block text-sm font-medium text-gray-700">
                                業務内容 <span class="text-red-500">*</span>
                            </label>
                            <textarea name="business_content" id="business_content" rows="8"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('business_content') border-red-500 @enderror">{{ old('business_content') }}</textarea>
                            @error('business_content')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 作業予定 -->
                        <div class="mb-4">
                            <label for="work_plan" class="block text-sm font-medium text-gray-700">
                                作業予定
                            </label>
                            <textarea name="work_plan" id="work_plan" rows="5"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('work_plan') border-red-500 @enderror">{{  old('work_plan') }}</textarea>
                            @error('work_plan')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 備忘欄 -->
                        <div class="mb-4">
                            <label for="memo" class="block text-sm font-medium text-gray-700">
                                備忘欄
                            </label>
                            <textarea name="memo" id="memo" rows="5"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('memo') border-red-500 @enderror">{{ old('memo') }}</textarea>
                        </div>

                        <!-- ボタン -->
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('daily-reports.index', ['report_date' => $defaultDate, 'user_id' => auth()->id()]) }}" 
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                キャンセル
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                作成する
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>