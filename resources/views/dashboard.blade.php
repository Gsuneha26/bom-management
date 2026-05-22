<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('bom.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" id="bom-file" class="border border-gray-300 rounded-md p-2 w-full">

                        @error('file')
                            <p class="text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                        
                        <button type="submit" class="mt-4 px-4 py-2 rounded-md bg-blue-500 hover:bg-blue-600 text-white">
                            Upload BOM
                        </button>
                    </form>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">
                    <h3 class="text-xl font-bold mb-4">
                        Uploaded BOMs
                    </h3>

                    <div class="overflow-x-auto">

                    <table class="w-full border-collapse">

                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700 text-left">
                                <th class="p-3">ID</th>
                                <th class="p-3">Reference</th>
                                <th class="p-3">File Name</th>
                                <th class="p-3">Items</th>
                                <th class="p-3">Created</th>
                                <th class="p-3">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($boms as $bom)

                                <tr class="border-b dark:border-gray-700 text-left">

                                    <td class="p-3">
                                        {{ $bom->id }}
                                    </td>

                                    <td class="p-3">
                                        {{ $bom->bom_reference }}
                                    </td>

                                    <td class="p-3">
                                        {{ $bom->file_name }}
                                    </td>

                                    <td class="p-3">
                                        {{ $bom->items_count }}
                                    </td>

                                    <td class="p-3">
                                        {{ $bom->created_at->format('d M Y') }}
                                    </td>

                                    <td class="p-3">
                                        <a
                                            href="{{ route('bom.show', $bom->id) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg"
                                        >
                                            View
                                        </a>
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="p-4 text-center text-gray-500">
                                        No BOM uploaded yet.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>
            </div>
        </div>
    </div>
</x-app-layout>
