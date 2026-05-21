<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-2xl">
            BOM Details
        </h2>
    </x-slot>

    <div class="py-10">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-xl p-6">

                <h2 class="text-xl font-bold mb-6">
                    {{ $bom->bom_reference }}
                </h2>

                <div class="overflow-x-auto">

                    <table class="w-full border-collapse border">

                        <thead>

                            <tr class="bg-gray-200">

                                <th class="border p-3">Part Number</th>

                                <th class="border p-3">Description</th>

                                <th class="border p-3">Qty</th>

                                <th class="border p-3">Unit</th>

                                <th class="border p-3">Specification</th>

                                <th class="border p-3">Inventory Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($items as $item)

                                <tr>

                                    <td class="border p-3">
                                        {{ $item->part_number }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $item->description }}
                                    </td>

                                    <td class="border p-3 text-center">
                                        {{ $item->required_qty }}
                                    </td>

                                    <td class="border p-3 text-center">
                                        {{ $item->unit }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $item->specifications }}
                                    </td>

                                    <td class="border p-3 text-center">
                                        @if($item->inventory_status == 'IN STOCK')

                                            <span class="bg-green-500 text-white px-3 py-1 rounded">
                                                IN STOCK
                                            </span>

                                        @elseif($item->inventory_status == 'PARTIAL STOCK')

                                            <span class="bg-yellow-500 text-white px-3 py-1 rounded">
                                                PARTIAL
                                            </span>

                                        @else

                                            <span class="bg-red-500 text-white px-3 py-1 rounded">
                                                OUT OF STOCK
                                            </span>

                                        @endif
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