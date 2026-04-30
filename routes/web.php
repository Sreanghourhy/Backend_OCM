<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
// use Inertia\Inertia;

// Route::get('/me', function(){
// 	echo phpinfo();
// });


// Route::get('dashboard', [ DashboardController::class , 'index' ]);

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// require __DIR__.'/settings.php';
// require __DIR__.'/auth.php';

// Route::get('sosl',function(){
//     $officers = \App\Models\Officer\Officer::whereIn('position_id',[7,8,9,10])->orderby('position_id')->get()->map(function($officer){
//         return
//             '<tr>'
//                 . '<td>' . $officer->code . '</td>'
//                 . '<td>' . ($officer->people != null ? $officer->people->lastname : ''). '</td>'
//                 . '<td>' . ($officer->people != null ? $officer->people->firstname : ''). '</td>'
//                 . '<td>' . ($officer->people != null ? $officer->people->enlastname : ''). '</td>'
//                 . '<td>' . ($officer->people != null ? $officer->people->enfirstname : ''). '</td>'
//                 . '<td>' . ($officer->position != null ? $officer->position->name : ''). '</td>'
//             .'</tr>';
//     });
//     echo '
//     <table >
//         <thead>
//             <tr>
//                 <th>លេខមន្ត្រី</th>
//                 <th>គោត្តនាម</th>
//                 <th>នាមខ្លួន</th>
//                 <th>គោត្តនាម (ឡាតាំង)</th>
//                 <th>នាមខ្លួន (ឡាតាំង)</th>
//                 <th>តួនាទី</th>
//             </tr>
//         </thead>
//         <tbody>'.implode('',$officers->toArray()).'</tbody>
//         <tfooter>
//             <tr>
//                 <td colspan="4" >សរុប</td>
//                 <td>'.count( $officers ).'</td>
//             </tr>
//         </tfooter>
//     </table>
//     ';
// });
// Route::get('dldsosl',function(){
//     $officers = \App\Models\Officer\Officer::whereIn('position_id',[7,8,9,10])->orderby('position_id')->get()->map(function($officer){
//         return implode(',' ,
//                 [
//                     $officer->code ,
//                     ($officer->people != null ? $officer->people->lastname : '') ,
//                     ($officer->people != null ? $officer->people->firstname : '') ,
//                     ($officer->people != null ? $officer->people->enlastname : '') ,
//                     ($officer->people != null ? $officer->people->enfirstname : '') ,
//                     ($officer->position != null ? $officer->position->name : '') ,
//                 ]
//         ). PHP_EOL;
//     });
//     $csv = 'លេខមន្ត្រី,គោត្តនាម,នាមខ្លួន,គោត្តនាម (ឡាតាំង),នាមខ្លួន (ឡាតាំង),តួនាទី' . PHP_EOL . implode( '' , $officers->toArray() ) ;

//     $file_name = 'បញ្ជីមន្ត្រីនយោបាយ.csv'; // The download filename
//     $encoding = 'UTF-8'; // Specify the encoding (e.g., UTF-8, ISO-8859-1, etc.)

//     header('Content-Description: File Transfer');
//     header('Content-Type: text/plain; charset=' . $encoding); // Specify charset
//     header('Expires: 0');
//     header('Cache-Control: must-revalidate');
//     header('Pragma: public');
//     header('Content-Length: ' . strlen($csv));
//     header('Content-Disposition: attachment; filename="' . $file_name . '"');

//     echo $csv;

// });
