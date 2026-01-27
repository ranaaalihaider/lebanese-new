@extends('layouts.app')

@section('content')
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Manage Product Types</h1>
            <a href="{{ route('admin.dashboard') }}" class="text-blue-500 hover:text-blue-700">&larr; Dashboard</a>
        </div>

        <!-- Add New Type -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Add New Type</h2>
            <form action="{{ route('admin.product-types.store') }}" method="POST" class="flex gap-4">
                @csrf
                <input type="text" name="name"
                    class="shadow appearance-none border rounded flex-grow py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="e.g., Fruits, Vegetables, Dairy" required>
                <button type="submit"
                    class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">Add</button>
            </form>
        </div>

        <!-- List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Name
                        </th>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($types as $type)
                        <tr x-data="{ editing: false, name: '{{ $type->name }}' }">
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <div x-show="!editing">
                                    <p class="text-gray-900 whitespace-no-wrap font-bold">{{ $type->name }}</p>
                                </div>
                                <div x-show="editing">
                                    <input type="text" x-model="name"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <div class="flex gap-2">
                                    <!-- Edit/Save Button -->
                                    <div x-show="!editing">
                                        <button @click="editing = true" class="text-blue-500 hover:text-blue-700 font-bold">
                                            Edit
                                        </button>
                                    </div>
                                    <div x-show="editing">
                                        <form action="{{ route('admin.product-types.update', $type) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="name" :value="name">
                                            <button type="submit" class="text-green-500 hover:text-green-700 font-bold">
                                                Save
                                            </button>
                                        </form>
                                        <button @click="editing = false; name = '{{ $type->name }}'"
                                            class="text-gray-500 hover:text-gray-700 font-bold ml-2">
                                            Cancel
                                        </button>
                                    </div>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.product-types.destroy', $type) }}" method="POST"
                                        onsubmit="return confirm('Delete this type?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection