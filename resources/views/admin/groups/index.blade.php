<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('部署（グループ）管理') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">部署名</th>
                                <th class="px-4 py-2 text-left">説明</th>
                                <th class="px-4 py-2 text-left">所属人数</th>
                                <th class="px-4 py-2 text-left">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groups as $group)
                                <tr class="border-b">
                                    <td class="px-4 py-3 font-bold">{{ $group->name }}</td>
                                    <td class="px-4 py-3">{{ $group->description }}</td>
                                    <td class="px-4 py-3">{{ $group->users_count }}名</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.groups.edit', $group) }}" class="text-blue-600 hover:underline">
                                            編集・社員管理
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>