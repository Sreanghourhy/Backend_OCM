<?php
namespace App\Http\Controllers\Api\Admin\Microsoft;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class WordController extends Controller
{
    public function read(Request $request){
        // $wordFile = storage_path('data/profile/1670300022.docx');
        $wordFile = storage_path('data/profile/1770700173.docx');
        $document = \PhpOffice\PhpWord\IOFactory::load( $wordFile );
        // $document = \PhpOffice\PhpWord\IOFactory::createReader('Word2007');
        // $document->load( $wordFile );
        $sections = $document->getSections();
        $result = [];
        $resultWithLine = [] ;
        foreach ($sections as $section) {
            $elements = $section->getElements();
            foreach ($elements as $first => $element) {
                // TextRun
                if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    $resultWithLine[] = $result[$first] = $element->getText() ;
                }
                // Handle Tables
                elseif ($element instanceof \PhpOffice\PhpWord\Element\Table ) {
                    // dd( $element->getRows() ); 
                    foreach ($element->getRows() as $second => $row) {
                        foreach ($row->getCells() as $third => $cell) {
                            // Extract text from cell elements (e.g., TextRun)
                            $cellElements = $cell->getElements();
                            foreach ($cellElements as $fourth => $cellElement) {
                                if ($cellElement instanceof \PhpOffice\PhpWord\Element\TextRun) {
                                    $resultWithLine[] = $result[$first][$second][$third][$fourth] = $cellElement->getText() ;
                                }
                            }
                        }
                    }
                }
                // Handle ListItems (bulleted/numbered lists)
                elseif ($element instanceof \PhpOffice\PhpWord\Element\ListItem) {
                    // Extract text from list item elements (e.g., TextRun)
                    $listItemElements = $element->getElements();
                    foreach ($listItemElements as $second => $listItemElement) {
                        if ($listItemElement instanceof TextRun) {
                            $resultWithLine[] = $result[$first][$second] = $listItemElement->getText();
                        }
                    }
                }
                elseif( $element instanceof \PhpOffice\PhpWord\Element\TextBreak ){

                }
            }
        }
        // dd( $result );
        dd( $resultWithLine );
    }
}