<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Purchase Intents
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

                <div class="overflow-x-auto">

                    <table class="min-w-full border border-gray-300">

                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="border px-4 py-2">ID</th>
                                <th class="border px-4 py-2">Item Code</th>
                                <th class="border px-4 py-2">Description</th>
                                <th class="border px-4 py-2">Required Qty</th>
                                <th class="border px-4 py-2">Available Qty</th>
                                <th class="border px-4 py-2">Shortfall Qty</th>
                                <th class="border px-4 py-2">Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($intents as $intent)

                                <tr class="text-center">

                                    <td class="border px-4 py-2">
                                        {{ $intent->id }}
                                    </td>

                                    <td class="border px-4 py-2">
                                        {{ $intent->item_code }}
                                    </td>

                                    <td class="border px-4 py-2">
                                        {{ $intent->description }}
                                    </td>

                                    <td class="border px-4 py-2">
                                        {{ $intent->required_qty }}
                                    </td>

                                    <td class="border px-4 py-2">
                                        {{ $intent->available_qty }}
                                    </td>

                                    <td class="border px-4 py-2">
                                        {{ $intent->shortfall_qty }}
                                    </td>

                                    <td class="border px-4 py-2">

                                        @if($intent->status == 'Pending')
                                            <span class="bg-yellow-500 text-white px-2 py-1 rounded">
                                                Pending
                                            </span>
                                        @elseif($intent->status == 'Acknowledged')
                                            <span class="bg-blue-500 text-white px-2 py-1 rounded">
                                                Acknowledged
                                            </span>
                                        @else
                                            <span class="bg-green-500 text-white px-2 py-1 rounded">
                                                PO Raised
                                            </span>
                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        No Purchase Intents Found
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>