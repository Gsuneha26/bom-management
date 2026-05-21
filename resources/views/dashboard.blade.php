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
                        
                        <button type="submit" class="mt-4 px-4 py-2 rounded-md">Upload BOM</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
