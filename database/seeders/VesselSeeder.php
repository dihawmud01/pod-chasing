<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VesselSeeder extends Seeder
{
    public function run(): void
    {
        $date = '2026-02-21';

        $vessels = [
            ['vessel_name' => 'HORIZON ARCTIC',        'driver' => 'TML HOLLAND',                              'delivery_address' => 'PORT LA NOUVELLE',                    'information' => 'Follow up delivery status'],
            ['vessel_name' => 'TERVEL',                 'driver' => 'TD Elkey sp. z o.o',                       'delivery_address' => 'SKAGEN',                              'information' => 'Follow up delivery status'],
            ['vessel_name' => 'BULK FINLAND',           'driver' => 'Logidix GTM',                              'delivery_address' => 'GIJON',                               'information' => 'Follow up delivery status'],
            ['vessel_name' => 'ALBERTO TOPIC',          'driver' => 'Kelly European Freight',                   'delivery_address' => 'BELFAST',                             'information' => 'Follow up delivery status'],
            ['vessel_name' => 'LUZON SPIRIT',           'driver' => 'LIMANi Export Warehouse Algeciras',        'delivery_address' => 'GIBRALTAR',                           'information' => 'Follow up with Mariano'],
            ['vessel_name' => 'HOEGH TROVE',            'driver' => 'ELK TRANSPORT INTERNATIONAL',             'delivery_address' => 'ANTWERP',                             'information' => 'DELIVERED',               'delivered' => true],
            ['vessel_name' => 'GIANNA',                 'driver' => 'Zargood',                                  'delivery_address' => 'AMSTERDAM',                           'information' => 'Follow up delivery status'],
            ['vessel_name' => 'BANGLAR ARJAN',          'driver' => 'TD Elkey sp. z o.o',                      'delivery_address' => 'SKAGEN',                              'information' => 'Follow up delivery status'],
            ['vessel_name' => 'AOM SVEVA',              'driver' => 'Newflow Logistics Sp. z o.o.',             'delivery_address' => 'HAMBURG',                             'information' => 'DELIVERED',               'delivered' => true],
            ['vessel_name' => 'MANISA CAMILLA',         'driver' => 'TML HOLLAND',                              'delivery_address' => 'SETE',                                'information' => 'DELIVERED and waiting for POD', 'delivered' => true],
            ['vessel_name' => 'SUMINISTROS DE LA NAVE', 'driver' => 'LIMANi Export Warehouse Algeciras',        'delivery_address' => 'Algeciras Logistic Solutions ALS',    'information' => 'Follow up with Mariano'],
            ['vessel_name' => 'HAV Marlin',             'driver' => 'Van der Mark Transport',                   'delivery_address' => 'MOERDIJK',                            'information' => 'Follow up delivery status'],
            ['vessel_name' => 'AURORA ONE',             'driver' => 'Tymczyj Logistics',                        'delivery_address' => 'BAYONNE',                             'information' => 'DELIVERED',               'delivered' => true],
            ['vessel_name' => 'AMBER SPIRIT',           'driver' => 'Chandler Consolidated Services',           'delivery_address' => 'ROTTERDAM',                           'information' => 'DELIVERED',               'delivered' => true],
            ['vessel_name' => 'GENCO AQUITAINE',        'driver' => 'LIMANi Export Warehouse Algeciras',        'delivery_address' => 'GIBRALTAR',                           'information' => 'Follow up with Mariano'],
            ['vessel_name' => 'PASCO BERCEM',           'driver' => 'TML HOLLAND',                              'delivery_address' => 'LAVERA',                              'information' => 'DELIVERED and waiting for POD', 'delivered' => true],
            ['vessel_name' => 'BRASSIANA',              'driver' => 'Chandler Consolidated Services',           'delivery_address' => 'BARENDRECHT',                         'information' => 'DELIVERED',               'delivered' => true],
            ['vessel_name' => 'KIVALLIQ W.',            'driver' => 'Chandler Consolidated Services',           'delivery_address' => 'HOOFDDORP',                           'information' => 'Waiting in customs email'],
            ['vessel_name' => 'BOS TETHYS',             'driver' => 'TIPSA-LOGISTICA COSTA DORADA 2014 SL',    'delivery_address' => 'VINAROS',                             'information' => 'Follow up delivery status'],
            ['vessel_name' => 'COASTALWATER',           'driver' => 'ELK TRANSPORT INTERNATIONAL',             'delivery_address' => 'GHENT',                               'information' => 'DELIVERED',               'delivered' => true],
            ['vessel_name' => 'LEALE',                  'driver' => 'TOUR MENSAJEROS EXPRESS SL',               'delivery_address' => 'ALGECIRAS',                           'information' => 'DELIVERED and waiting for POD', 'delivered' => true],
            ['vessel_name' => 'SEAWAY VENTUS',          'driver' => 'COURIER',                                  'delivery_address' => 'ROZENBURG',                           'information' => 'DELIVERED',               'delivered' => true],
            ['vessel_name' => 'KNUD',                   'driver' => 'TD Elkey sp. z o.o',                      'delivery_address' => 'SKAGEN',                              'information' => 'Follow up delivery status'],
            ['vessel_name' => 'PATAGONMAN',             'driver' => 'Chandler Consolidated Services',           'delivery_address' => 'Spijkenisse',                         'information' => 'DELIVERED',               'delivered' => true],
            ['vessel_name' => 'KAUNAS',                 'driver' => 'LIMANi Export Warehouse Algeciras',        'delivery_address' => 'ALGECIRAS',                           'information' => 'Follow up with Mariano'],
            ['vessel_name' => 'BALTIC JASMINE',         'driver' => 'BALTIC JASMINE',                           'delivery_address' => 'SKAGEN',                              'information' => 'Follow up delivery status'],
            ['vessel_name' => 'DUBAI ATTRACTION',       'driver' => 'Tymczyj Logistics',                        'delivery_address' => 'HUELVA',                              'information' => 'DELIVERED and waiting for POD', 'delivered' => true],
        ];

        foreach ($vessels as $vessel) {
            DB::table('vessels')->insert([
                'vessel_name'      => $vessel['vessel_name'],
                'driver'           => $vessel['driver'],
                'delivery_address' => $vessel['delivery_address'],
                'information'      => $vessel['information'] ?? null,
                'customs_doc'      => false,
                'print_status'     => false,
                'pod_status'       => false,
                'delivered'        => $vessel['delivered'] ?? false,
                'pod_file'         => null,
                'report_date'      => $date,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
    }
}
