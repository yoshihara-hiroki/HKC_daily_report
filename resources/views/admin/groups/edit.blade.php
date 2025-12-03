<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('部署編集・社員登録') }} : {{ $group->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.groups.update', $group) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">部署名</label>
                            <input type="text" name="name" value="{{ old('name', $group->name) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">説明</label>
                            <input type="text" name="description" value="{{ old('description', $group->description) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <hr class="my-6">

                        <div class="mb-6">
                            <label class="block text-gray-700 text-lg font-bold mb-4">所属社員を選択</label>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($users as $user)
                                    <div class="flex items-center p-2 border rounded hover:bg-gray-50">
                                        <input id="user_{{ $user->id }}" 
                                               name="users[]" 
                                               type="checkbox" 
                                               value="{{ $user->id }}"
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                               @checked($group->users->contains($user->id))>
                                        <label for="user_{{ $user->id }}" class="ml-2 text-sm text-gray-800 cursor-pointer w-full">
                                            {{ $user->name }}
                                            <span class="text-xs text-gray-500 block">{{ $user->email }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.groups.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                キャンセル
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                保存する
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>