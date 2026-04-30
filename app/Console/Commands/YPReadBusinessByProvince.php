<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Api\Admin\YPReaderController;

class YPReadBusinessByProvince extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ypreadprovince';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read registered business by provinces in YPKhmer.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // echo 'Start reading' . PHP_EOL ;
        // $provinceCounter = 0 ;
        // $totalChilds = 0 ;
        // $provinces = [
        //     // [
        //     //     'id' => 1 ,
        //     //     'name' => 'Banteay Meanchey' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/A7C80BAD-2451-49A6-85B1-DAA2F39752E1'
        //     // ],
        //     // [
        //     //     'id' => 2 ,
        //     //     'name' => 'Battambang' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/236948A0-31EA-4B5F-B66D-2E844FF7F024'
        //     // ],
        //     // [
        //     //     'id' => 3 ,
        //     //     'name' => 'Kampong Cham' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/2414C563-07C2-465E-B9BC-586D97318689'
        //     // ],
        //     // [
        //     //     'id' => 4 ,
        //     //     'name' => 'Kampong Chhnang' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/71CBA1B1-762E-4D62-812B-C2AB7EC22C0B'
        //     // ],
        //     // [
        //     //     'id' => 5 ,
        //     //     'name' => 'Kampong Speu' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/56E21497-91DB-4E55-974F-13379361E9E3'
        //     // ],
        //     // [
        //     //     'id' => 6 ,
        //     //     'name' => 'Kampong Thom' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/D989DE2E-ABF4-4F1F-905D-419D50B469F2'
        //     // ],
        //     // [
        //     //     'id' => 7 ,
        //     //     'name' => 'Kampot' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/FBA711C4-C14D-4A62-9623-C2872533EDCD'
        //     // ],
        //     // [
        //     //     'id' => 8 ,
        //     //     'name' => 'Kandal' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/A698634D-6776-44FC-A2D0-0745BC1C4E1A'
        //     // ],
        //     // [
        //     //     'id' => 9 ,
        //     //     'name' => 'Koh Kong' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/BC0378E6-17AB-4CE1-AEFA-848F1DBE4981'
        //     // ],
        //     // [
        //     //     'id' => 10 ,
        //     //     'name' => 'Kratie' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/3A667713-B37C-4903-8B85-9ED649A800CE'
        //     // ],
        //     // [
        //     //     'id' => 11 ,
        //     //     'name' => 'Mondul Kiri' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/53094F73-CBD8-444A-BA42-ECD76B996BE8'
        //     // ],
        //     // // Only Phnom penh that does not process yet.
        //     [
        //         'id' => 12 ,
        //         'name' => 'Phnom Penh' ,
        //         'url' => 'https://ypkhmer.com/category/location/01834EE1-FA95-48DE-831A-3D3C05DD3AFC'
        //     ],
        //     // [
        //     //     'id' => 13 ,
        //     //     'name' => 'Preah Vihear' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/DFC9C043-7E28-4467-8FC4-4382E259CC7D'
        //     // ],
        //     // [
        //     //     'id' => 14 ,
        //     //     'name' => 'Prey Veng' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/C93C77CF-04BA-458F-8CEE-891657EBB8C2'
        //     // ],
        //     // [
        //     //     'id' => 15 ,
        //     //     'name' => 'Pursat' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/17A63A3F-C1C5-4173-973A-259189173534'
        //     // ],
        //     // [
        //     //     'id' => 16 ,
        //     //     'name' => 'Ratanak Kiri' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/455AA2AA-91FB-4B11-B695-FA746042B0AC'
        //     // ],
        //     // [
        //     //     'id' => 17 ,
        //     //     'name' => 'Siemreap' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/D3BF02FC-6A2B-4242-AB85-69EACA166220'
        //     // ],
        //     // [
        //     //     'id' => 18 ,
        //     //     'name' => 'Preah Sihanouk' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/2F4C0ADF-ADB8-4F71-A392-45BA9B2567AD'
        //     // ],
        //     // [
        //     //     'id' => 19 ,
        //     //     'name' => 'Stung Treng' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/4E96A7D0-462A-4340-ABFF-A5083F1ED2F5'
        //     // ],
        //     // [
        //     //     'id' => 20 ,
        //     //     'name' => 'Svay Rieng' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/8743E8EC-85A2-4E91-B354-E8492C9CD3A1'
        //     // ],
        //     // [
        //     //     'id' => 21 ,
        //     //     'name' => 'Takeo' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/A48AE11E-A04F-482B-B6B1-8A8A9CEC6C48'
        //     // ],
        //     // [
        //     //     'id' => 22 ,
        //     //     'name' => 'Oddar Meanchey' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/77101301-3143-4D7E-836A-BD9C2519ED6C'
        //     // ],
        //     // [
        //     //     'id' => 23 ,
        //     //     'name' => 'Kep' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/F6FC961A-8028-43E3-9588-D4C05A2F2816'
        //     // ],
        //     // [
        //     //     'id' => 24 ,
        //     //     'name' => 'Pailin' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/A7F50C31-D0A5-4B9E-B0FB-0EE0F4E6DFF8'
        //     // ],
        //     // [
        //     //     'id' => 25 ,
        //     //     'name' => 'Tboung Khmum' ,
        //     //     'url' => 'https://ypkhmer.com/category/location/4F5DB7C4-8C83-11E3-B7F4-123140001E04'
        //     // ]
        // ] ;
        // foreach( $provinces AS $index => $province ) {
        //     /**
        //      * Read the items within each categories
        //      */
        //     $dataItems = YPReaderController::readPageWithCurl( $province['url'] , "//ul[@class='pagination']/li[@class='page-item']" );
        //     if( $dataItems == false ) continue;
        //     $totalPage = $dataItems[ $dataItems->length - 2 ] == null ? 1 : $dataItems[ $dataItems->length - 2 ]->textContent ;
        //     $childCounter = 0 ;
        //     // stat from page 1278
        //     for($page=1278; $page<=$totalPage;$page++){                
        //         $items = YPReaderController::readPageWithCurl( $province['url'] . "?page=" . $page , "//div[@class='card-body p-0 pl-1 h-100 min-h-150']" );
        //         if( $items == false ) continue;
        //         $businessNames = [] ;
        //         foreach( $items AS $itemIndex => $item ){


        //             $categoryObj = \App\Models\YPCategory::where('name', trim( str_replace(["\n","•"],[""], $item->childNodes[5]->childNodes[1]->textContent ) ) )->first() ;
        //             if( $categoryObj === null && ( trim( str_replace(["\n","•"],[""], $item->childNodes[5]->childNodes[1]->textContent ) ) != "" ) ){
        //                 $categoryObj = \App\Models\YPCategory::create([
        //                     'name' => trim( str_replace(["\n","•"],[""], $item->childNodes[5]->childNodes[1]->textContent ) )
        //                 ]);
        //                 echo( 'Category : ' . $categoryObj->name . ' has been saved.' . PHP_EOL ) ;
        //             }
        //             if( $categoryObj == null && ( trim( str_replace(["\n","•"],[""], $item->childNodes[5]->childNodes[1]->textContent ) ) == "" ) ) continue;
        //             $childCounter++;
        //             $totalChilds++;
        //             $ypProduct = \App\Models\YPProduct::create([
        //                 'name' => trim( str_replace(["\n","•"],[""], $item->childNodes[1]->childNodes[1]->childNodes[1]->textContent ) ),
        //                 'category' => $categoryObj->id ,
        //                 'phone' => str_replace([" ","\n","•"],[""], $item->childNodes[7] == null ? "" : $item->childNodes[7]->textContent ) ,
        //                 'address' => str_replace([" ","\n","•"],[""], $item->childNodes[9] == null ? "" : $item->childNodes[9]->textContent ) ,
        //                 'province' => $province['id']
        //             ]);
        //             echo( "Province : " . $province['name'] . " , No : " . $totalChilds . ', Page : ' . $page . ', Child : ' . $childCounter . ' => ' .trim( str_replace(["\n","•"],[""], $item->childNodes[1]->childNodes[1]->childNodes[1]->textContent ) ) . PHP_EOL ) ;
        //         }
        //     }
        // }
        
        // $areaKeys = [] ;
        // \App\Models\YPProduct::get()->map(function($p) use (&$areaKeys ) {
        //     array_map( function($area) use( &$areaKeys ) {
        //         if( !in_array( $area , $areaKeys ) ) $areaKeys[] = $area ;
        //     } , array_slice( explode(',',$p->address) , 3 , 2 ) );
        //     echo count( $areaKeys ) . PHP_EOL;
        // });

        


        echo 'Finish reading' . PHP_EOL ;
        return Command::SUCCESS;       
    }
    
}
