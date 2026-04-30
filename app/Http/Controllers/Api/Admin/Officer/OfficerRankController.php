<?php

namespace App\Http\Controllers\Api\Admin\Officer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CrudController;
use App\Models\Officer\Officer as OfficerModel;
use App\Models\Officer\OfficerRank as RecordModel;
use App\Models\Officer\Rank as RankModel;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfficerRankController extends Controller
{
    private $selectFields = [
        'id',
        'officer_id',
        'rank_id',
        'start',
        'end',
        'countesy_id',
        'organization_structure_position_id',
        'changing_type',
        'education_center',
        'location',
        'certificate',
        'desp',
        'pdf',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];

    public function index(Request $request)
    {
        $search = isset($request->search) && $request->search !== '' ? $request->search : false;
        $perPage = isset($request->perPage) && $request->perPage !== '' ? $request->perPage : 10;
        $page = isset($request->page) && $request->page !== '' ? $request->page : 1;

        $officer = intval($request->officer_id) > 0
            ? OfficerModel::find(intval($request->officer_id))
            : null;

        $queryString = [
            'where' => [
                'default' => [
                    $officer == null
                        ? []
                        : [
                            'field' => 'officer_id',
                            'value' => $officer->id
                        ]
                ]
            ],
            'pagination' => [
                'perPage' => $perPage,
                'page' => $page
            ],
            'search' => $search === false ? [] : [
                'value' => $search,
                'fields' => [
                    'start',
                    'end',
                    'changing_type'
                ]
            ],
            'order' => [
                'field' => 'start',
                'by' => 'desc'
            ],
        ];

        $request->merge($queryString);

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields, [
            'pdf' => function ($record) {
                $record->pdf = strlen($record->pdf) > 0 && Storage::disk('certificate')->exists($record->pdf)
                    ? true
                    : false;
                return $record->pdf;
            }
        ]);

        $crud->setRelationshipFunctions([
            'officer' => [
                'code',
                'official_date',
                'unofficial_date',
                'public_key',
                'user_id',
                'people_id',
                'email',
                'phone',
                'countesy_id',
                'organization_id',
                'position_id',
                'rank_id',
                'leader',
                'image',
                'pdf',
                'passport',
                'people' => ['id', 'firstname', 'lastname', 'enfirstname', 'enlastname']
            ],
            'rank' => [
                'id',
                'name',
                'ank',
                'krobkhan_name',
                'krobkhan',
                'rank',
                'thnak'
            ],
            'countesy' => [
                'id',
                'name',
                'name_en'
            ]
        ]);

        $builder = $crud->getListBuilder();
        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __('crud.read.success');
        $responseData['ok'] = true;
        return response()->json($responseData, 200);
    }

    public function pdf(Request $request)
    {
        $record = RecordModel::findOrFail($request->id);
        if ($record) {
            $pathPdf = storage_path('data') . '/certificates/' . $record->pdf;
            $ext = pathinfo($pathPdf);
            $filename = $record->id . '-' . $record->officer_id . '.' . ($ext['extension'] ?? 'pdf');

            if (file_exists($pathPdf) && is_file($pathPdf)) {
                $pdfBase64 = base64_encode(file_get_contents($pathPdf));
                return response([
                    'serial' => $record->pdf,
                    'pdf' => 'data:application/pdf;base64,' . $pdfBase64,
                    'filename' => $filename,
                    'ok' => true
                ], 200);
            }
            return response([
                'message' => 'មានបញ្ហាក្នុងការអានឯកសារ !',
                'path' => $pathPdf
            ], 500);
        }
    }

    public function upload(Request $request)
    {
        $user = \Auth::user();
        if (!$user) {
            return response([
                'message' => 'សូមចូលប្រព័ន្ធជាមុនសិន។'
            ], 403);
        }

        $phpFileUploadErrors = [
            0 => 'មិនមានបញ្ហាជាមួយឯកសារឡើយ។',
            1 => 'ទំហំឯកសារធំហួសកំណត់ ' . ini_get('upload_max_filesize'),
            2 => 'ទំហំឯកសារធំហួសកំណត់នៃទំរង់បញ្ចូលទិន្នន័យ ' . ini_get('post_max_size'),
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        ];
        if (isset($_FILES['file']) && $_FILES['file']['error'] > 0) {
            return response()->json([
                'ok' => false,
                'message' => $phpFileUploadErrors[$_FILES['file']['error']]
            ], 403);
        }

        $record = RecordModel::find($request->id);
        if ($record == null) {
            return response([
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់របស់ឯកសារយោង។'
            ], 403);
        }

        $uniqeName = Storage::disk('certificate')->putFile('', new File($_FILES['file']['tmp_name']));
        $record->pdf = $uniqeName;
        $record->save();
        if (Storage::disk('certificate')->exists($record->pdf)) {
            $record->pdf = Storage::disk('certificate')->url($record->pdf);
            return response([
                'record' => $record,
                'message' => 'ជោគជ័យក្នុងការបញ្ចូលឯកសារយោង។'
            ], 200);
        }
        return response([
            'record' => $record,
            'message' => 'មិនមានឯកសារយោងដែលស្វែងរកឡើយ។'
        ], 403);
    }

    public function create(Request $request)
    {
        $officer = intval($request->officer_id) > 0 ? OfficerModel::find(intval($request->officer_id)) : null;
        if ($officer == null) {
            return response()->json([
                'ok' => false,
                'message' => 'សូមបញ្ជាក់ម្ចាស់ឯកសារ'
            ], 500);
        }

        $rank = $this->resolveRank($request);
        if ($rank == null) {
            return response()->json([
                'ok' => false,
                'message' => 'សូមបញ្ជាក់ឋានៈ ក្របខ័ណ្ឌ ឋានន្តរស័ក្តិ និងថ្នាក់អោយត្រឹមត្រូវ'
            ], 422);
        }

        $record = RecordModel::create([
            'officer_id' => $officer->id,
            'rank_id' => intval($rank->id),
            'start' => $request->start ?? '',
            'end' => $request->end ?? '',
            'countesy_id' => intval($request->countesy_id ?? 0) > 0 ? intval($request->countesy_id) : null,
            'organization_structure_position_id' => intval($request->organization_structure_position_id ?? 0) > 0 ? intval($request->organization_structure_position_id) : null,
            'changing_type' => $request->changing_type ?? null,
            'education_center' => $request->education_center ?? null,
            'location' => $request->location ?? null,
            'certificate' => $request->certificate ?? null,
            'desp' => $request->desp ?? null,
            'pdf' => '',
            'created_by' => \Auth::user()->id,
            'updated_by' => \Auth::user()->id,
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d'),
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d')
        ]);

        $record->rank;
        $record->countesy;

        $responseData['message'] = __('crud.read.success');
        $responseData['ok'] = true;
        $responseData['record'] = $record;
        return response()->json($responseData, 200);
    }

    public function update(Request $request)
    {
        if (!isset($request->id) || $request->id <= 0) {
            return response()->json([
                'message' => 'សូមបញ្ជាក់លេខសម្គាល់ឯកសារ។'
            ], 403);
        }

        $record = RecordModel::find($request->id);
        if ($record == null) {
            return response()->json([
                'message' => 'ឯកសារមិនមានក្នុងប្រព័ន្ធ។'
            ], 403);
        }

        $officer = intval($request->officer_id) > 0 ? OfficerModel::find(intval($request->officer_id)) : null;
        if ($officer == null) {
            return response()->json([
                'ok' => false,
                'message' => 'សូមបញ្ជាក់ម្ចាស់ឯកសារ'
            ], 500);
        }

        $rank = $this->resolveRank($request);
        if ($rank == null) {
            return response()->json([
                'ok' => false,
                'message' => 'សូមបញ្ជាក់ឋានៈ ក្របខ័ណ្ឌ ឋានន្តរស័ក្តិ និងថ្នាក់អោយត្រឹមត្រូវ'
            ], 422);
        }

        if ($record->update([
            'officer_id' => $officer->id,
            'rank_id' => intval($rank->id),
            'start' => $request->start ?? '',
            'end' => $request->end ?? '',
            'countesy_id' => intval($request->countesy_id ?? 0) > 0 ? intval($request->countesy_id) : null,
            'organization_structure_position_id' => intval($request->organization_structure_position_id ?? 0) > 0 ? intval($request->organization_structure_position_id) : null,
            'changing_type' => $request->changing_type ?? null,
            'education_center' => $request->education_center ?? null,
            'location' => $request->location ?? null,
            'certificate' => $request->certificate ?? null,
            'desp' => $request->desp ?? null,
            'updated_by' => \Auth::user()->id,
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d')
        ])) {
            $record->rank;
            $record->countesy;
            $responseData['message'] = __('crud.read.success');
            $responseData['ok'] = true;
            $responseData['record'] = $record;
            return response()->json($responseData, 200);
        }

        return response()->json([
            'message' => 'មានបញ្ហាក្នុងការរក្សារព័ត៌មានឯកសារ។'
        ], 403);
    }

    public function read(Request $request)
    {
        if (!isset($request->id) || $request->id < 0) {
            return response()->json([
                'ok' => false,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ឯកសារ។'
            ], 201);
        }

        $record = RecordModel::find($request->id);
        if ($record == null) {
            return response()->json([
                'ok' => false,
                'message' => 'ឯកសារដែលអ្នកត្រូវការមិនមានឡើយ។'
            ], 201);
        }

        $record->officer;
        $record->rank;
        $record->countesy;
        return response()->json([
            'record' => $record,
            'ok' => true,
            'message' => 'រួចរាល់។'
        ], 200);
    }

    public function destroy(Request $request)
    {
        if (!isset($request->id) || $request->id < 0) {
            return response()->json([
                'ok' => false,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ឯកសារ។'
            ], 201);
        }

        $record = RecordModel::find($request->id);
        if ($record == null) {
            return response()->json([
                'ok' => false,
                'message' => 'ឯកសារស្វែករកបានជោគជ័យ។'
            ], 201);
        }

        $record->officer;
        $record->rank;
        $record->countesy;
        $tempRecord = $record;
        if ($record->delete()) {
            return response()->json([
                'record' => $tempRecord,
                'ok' => true,
                'message' => 'លុបទិន្នបានជោគជ័យ។'
            ], 200);
        }
        return response()->json([
            'record' => $tempRecord,
            'ok' => false,
            'message' => 'មានបញ្ហាក្នុងការលុបទិន្ន័យ។'
        ], 201);
    }

    private function resolveRank(Request $request)
    {
        $rankId = intval($request->rank_id ?? 0);
        if ($rankId > 0) {
            $rank = RankModel::find($rankId);
            if ($rank != null) {
                return $rank;
            }
        }

        $ank = trim(strval($request->ank ?? ''));
        $krobkhan = trim(strval($request->krobkhan ?? ''));
        $rankText = trim(strval($request->rank ?? ''));
        $thnak = trim(strval($request->thnak ?? ''));

        if ($ank === '' && $krobkhan === '' && $rankText === '' && $thnak === '') {
            return null;
        }

        $query = RankModel::query();
        if ($ank !== '') {
            $query->where('ank', $ank);
        }
        if ($krobkhan !== '') {
            $query->where('krobkhan', $krobkhan);
        }
        if ($rankText !== '') {
            $query->where('rank', $rankText);
        }
        if ($thnak !== '') {
            $query->where('thnak', $thnak);
        }
        $matched = $query->first();
        if ($matched != null) {
            return $matched;
        }

        if ($rankText !== '') {
            $fallback = RankModel::query()->where('rank', $rankText);
            if ($krobkhan !== '') {
                $fallback->where('krobkhan', $krobkhan);
            }
            if ($thnak !== '') {
                $fallback->where('thnak', $thnak);
            }
            $matched = $fallback->first();
            if ($matched != null) {
                return $matched;
            }
        }

        return null;
    }
}
