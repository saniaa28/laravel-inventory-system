<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::create(['supplier_name' => 'PT Tech Supply', 'contact_person' => 'Andi', 'email' => 'sales@techsupply.example', 'phone' => '081200000001', 'address' => 'Jakarta']);
        Supplier::create(['supplier_name' => 'CV Office Nusantara', 'contact_person' => 'Sari', 'email' => 'hello@officenusantara.example', 'phone' => '081200000002', 'address' => 'Medan']);
        Supplier::create(['supplier_name' => 'Digital Partner', 'contact_person' => 'Budi', 'email' => 'support@digitalpartner.example', 'phone' => '081200000003', 'address' => 'Bandung']);
    }
}
