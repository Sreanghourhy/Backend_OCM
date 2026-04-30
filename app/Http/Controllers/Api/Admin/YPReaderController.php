<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class YPReaderController extends Controller
{
    
    /**
     * Listing categories
     */
    public static function byCategories(){
        $categories = [] ;
        for( $i = 1 ; $i <=12 ; $i++ ) {
            $xpathResult = static::readPageWithCurl( "https://ypkhmer.com/category/subcategory/all?page=".$i , "//div[@class='col-md-6 col-12']/a[@class='section-b']" );
            if( $xpathResult == false ) continue;
            foreach($xpathResult as $dlsIndex => $dl ) {
                $categories[] = $dl ;
                print_ln( $dl->name );
            //     if( $dl === null ) continue ;
            //     // $category = [
            //     //     'name' => str_replace([" ","\n","•"],[""],$dl->childNodes[1]->textContent ) ,
            //     //     'url' => $dl->attributes[0]->value ,
            //     //     'childs' => []
            //     // ];
                // $categoryObj = \App\Models\YPCategory::where('name', str_replace([" ","\n","•"],[""],$dl->childNodes[1]->textContent ) )->first() ;
                // if( $categoryObj === null ){
                //     $categoryObj = \App\Models\YPCategory::create([
                //         'name' => str_replace([" ","\n","•"],[""],$dl->childNodes[1]->textContent )
                //     ]);
                // }

            //     /**
            //      * Read the items within each categories
            //      */
                // $dataItems = static::readPageWithCurl( $dl->attributes[0]->value , "//ul[@class='pagination']/li[@class='page-item']" );
                // if( $dataItems == false ) continue;
                // $totalPage = $dataItems[ $dataItems->length - 2 ] == null ? 1 : $dataItems[ $dataItems->length - 2 ]->textContent ;
                // for($i=1; $i<=$totalPage;$i++){
                //     $items = static::readPageWithCurl( $dl->attributes[0]->value . "?page=" . $i , "//div[@class='card-body p-0 pl-1 h-100 min-h-150']" );
                //     if( $items == false ) continue;
                //     foreach( $items AS $itemIndex => $item ){
                //         // $category['childs'][] = [
                //         //     'name' => str_replace([" ","\n","•"],[""], $item->childNodes[1]->textContent ) ,
                //         //     'category' => str_replace([" ","\n","•"],[""], $item->childNodes[5]->textContent ) ,
                //         //     'phone' => explode('/',str_replace([" ","\n","•"],[""], $item->childNodes[7]->textContent ) ) ,
                //         //     'address' => explode(',',str_replace([" ","\n","•"],[""], $item->childNodes[9]->textContent ) )
                //         // ];
                //         $ypProduct = \App\Models\YPProduct::create([
                //             'name' => str_replace([" ","\n","•"],[""], $item->childNodes[1]->textContent ) ,
                //             'category' => $categoryObj->id ,
                //             'phone' => str_replace([" ","\n","•"],[""], $item->childNodes[7]->textContent ) ,
                //             'address' => str_replace([" ","\n","•"],[""], $item->childNodes[9]->textContent )
                //         ]);
                //     }
                // }
            }
        }
        return $categories;
    }

    /**
     * Listing categories items
     */
    public static function byCategoriesItems(Request $request){
        
        return response()->json( [] , 200);
    }
    
    public static function readPageWithCurl($url, $query){

        libxml_use_internal_errors(true);
        $data = null;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_URL, $url );
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
        $data = curl_exec($ch);
        curl_close($ch);
        if( empty($data) ) return false ;
        $doc = new \DOMDocument;
        // We don't want to bother with white spaces
        $doc->preserveWhiteSpace = false;
        $doc->loadHTML($data);
        $xpath = new \DOMXPath($doc);
        return $xpath->query($query);
      }

      public static function savingDistrictToDatabase(){

        foreach( \App\Models\YPProvince::whereraw('LENGTH(code)=4')->get() AS $index => $district ){
            // dd( str_replace([' '],"",( strtolower( $province->name_en ) ) ) );
            
            // \App\Models\YPProduct::whereRaw("address LIKE \"%". str_replace([' '],"",( strtolower( $district->name_en ) ) ) ."%\" ")
            // ->update([ 'province' => $district->id ]);

            \App\Models\YPProduct::whereRaw("address LIKE \"%". str_replace([' '],"",( strtolower( 'TakhmaoCity' ) ) ) ."%\" ")
            ->update([ 'district' => 8323 ]);

        }
        echo "Done";
      }
      public static function businessesByCategoryOfProvince(Request $request){
        $tableBody = $tableHead = [] ;
        // Get businesses in each category of each province
        $businessesByCategoryInEachProvince = [] ;
        $htmlHead[] = "No" ;
        $htmlHead[] = "Province" ;
        \App\Models\YPProvince::whereRaw('length(code) = 2')->get()->map(function($p) use( &$businessesByCategoryInEachProvince , &$tableBody, &$tableHead ) {
            $tableHead[$p->name][] = $p->name_en ;
            dd( $tableHead );
            $indexControlCategory = 0 ;
            $businessesByCategoryInEachProvince[$p->name_en] = $p->productOfProvince()->select('category', \DB::raw('count(*) as total ') )->groupby('category')->get()->map(function($product) use( $indexControlCategory, &$tableBody, &$tableHead ) {
                $indexControlCategory++;
                if( $indexControlCategory == 1 ) $tableHead[] = \App\Models\YPCategory::find($product->category)->name;
                $tableBody[$product->name_en][] = $product->total ;
                return [
                    'id' => $product->category ,
                    'name' => \App\Models\YPCategory::find($product->category)->name ,
                    'total' => $product->total
                ] ;
            });
        });
        return view('yp_businesses_by_category_of_province',[ 'tableHeader' => $tableHead , 'tableBody' => $tableBody ]);
      }
}
