<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Material Allocations
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

                <div class="overflow-x-auto">

                    <table class="table table-bordered w-full border-collapse border-gray-300">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th>Item Code</th>
                                <th>Description</th>
                                <th>Allocated Qty</th>
                                <th>Allocated To</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody class="text-center">
                            @foreach($allocations as $allocation)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="border px-4 py-2">{{ $allocation->item_code }}</td>
                                    <td class="border px-4 py-2">{{ $allocation->description }}</td>
                                    <td class="border px-4 py-2">{{ $allocation->allocated_qty }}</td>
                                    <td class="border px-4 py-2">{{ $allocation->allocated_to }}</td>
                                    <td class="border px-4 py-2">{{ $allocation->allocated_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $allocations->links() }}

                </div>

            </div>

        </div>

    </div>
</x-app-layout>

