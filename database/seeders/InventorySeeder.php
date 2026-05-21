<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Inventory::create([
            'item_code' => 'P25.267.TSM',
            'description' => 'TUBE SHEET FOR MACHING',
            'available_qty' => 10,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.TPH',
            'description' => 'TOP PLATE HEADER',
            'available_qty' => 20,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.EPH',
            'description' => 'END PLATE FOR HEADER',
            'available_qty' => 15,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.PSM',
            'description' => 'PLUG SHEET FOR MACHING',
            'available_qty' => 10,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.BPH',
            'description' => 'BOTTOM PLATE FOR HEADER',
            'available_qty' => 12,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.EFTT',
            'description' => 'FINNED TUBE',
            'available_qty' => 500,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.TS',
            'description' => 'TUBE SUPPORT',
            'available_qty' => 2000,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.SW',
            'description' => 'SPACER WAHSER',
            'available_qty' => 100,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.FW',
            'description' => 'FAB, WASHER',
            'available_qty' => 100,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.PG',
            'description' => 'PTFE GASKET',
            'available_qty' => 50,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.CP',
            'description' => 'COVER PLATE',
            'available_qty' => 30,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.STP. T&B',
            'description' => 'STIFFENER PLATE FOR TOP & BOTTOM PLATE',
            'available_qty' => 100,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.SLF&S',
            'description' => 'SUPPORT LUG PLATE ST.FT.HEADER',
            'available_qty' => 50,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.GLPF&S',
            'description' => 'GUARD LUG PLATE ST.FT.HEADER',
            'available_qty' => 50,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.SPH',
            'description' => 'SLIDING PLATE FOR HEADER',
            'available_qty' => 20,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.TFS',
            'description' => 'BONDED TEFLON',
            'available_qty' => 20,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.LLH',
            'description' => 'LIFTING LUG',
            'available_qty' => 20,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.GLP',
            'description' => 'GROUNDING LUG',
            'available_qty' => 20,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.266.HPH',
            'description' => 'HEX. PLUG',
            'available_qty' => 1000,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.MNPB',
            'description' => 'MFG. NAME PLATE BRACKET',
            'available_qty' => 10,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.ANPB',
            'description' => 'ASME NAME PLATE BRACKET',
            'available_qty' => 10,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.CTBF',
            'description' => 'C-TYPE BUNDLE FRAME PLATE',
            'available_qty' => 10,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.SC&CP',
            'description' => 'SUPPORT CLEAT PLATES & CLOSURE PLATE',
            'available_qty' => 50,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.TKP',
            'description' => 'TUBE KEEPER PLATE',
            'available_qty' => 20,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.LSP',
            'description' => 'LOUVERS SUPPORT ANGLE',
            'available_qty' => 20,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.HSPSF',
            'description' => 'HEADER SUPPORT PLATE FOR SIDE FRAME',
            'available_qty' => 50,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.SGP',
            'description' => 'SPACER GUIDE PLATE',
            'available_qty' => 50,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.LLSF',
            'description' => 'LIFTING LUG FOR SIDE FRAME',
            'available_qty' => 20,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.FGR',
            'description' => 'FAN GUARD RING (AIR PLENUM)',
            'available_qty' => 10,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.FR',
            'description' => 'FAM RING SHELL PLATE (AIR PLENUM)',
            'available_qty' => 10,
            'unit' => 'NOS',
        ]);

        Inventory::create([
            'item_code' => 'P25.267.CF',
            'description' => 'CIRCULAR FLANGE (AIR PLENUM)',
            'available_qty' => 10,
            'unit' => 'NOS',
        ]);
    }
}
