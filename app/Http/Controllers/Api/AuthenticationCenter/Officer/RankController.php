<?php

namespace App\Http\Controllers\Api\AuthenticationCenter\Officer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CrudController;
use App\Models\Officer\Officer;
use App\Models\Officer\OfficerRankByWorking;
use App\Models\Officer\Rank as RecordModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankController extends Controller
{
    private $selectFields = [
        'id',
        'ank',
        'krobkhan',
        'krobkhan_name',
        'rank',
        'thnak',
        'name',
        'desp',
        'tpid',
        'pid',
        'cids',
        'image',
        'record_index',
        'active',
        'prefix',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];

    public function index(Request $request)
    {
        $search = isset($request->search) && $request->serach !== "" ? $request->search : false;
        $perPage = isset($request->perPage) && $request->perPage !== "" ? $request->perPage : 10;
        $page = isset($request->page) && $request->page !== "" ? $request->page : 1;

        $queryString = [
            "where" => [
                'default' => [
                    [
                        'field' => 'ank' ,
                        'value' => 'រដ្ឋបាលទូទៅ'
                    ]
                ]
            ],
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search,
                'fields' => [
                    'ank',
                    'krobkhan',
                    'rank',
                    'name',
                    'desp',
                ]
            ],
            "order" => [
                'field' => 'id',
                'by' => 'asc'
            ],
        ];

        $request->merge($queryString);

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields);
        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true;

        return response()->json($responseData, 200);
    }

    public function create(Request $request)
    {
        return $this->saveOfficerRank($request, true);
    }

    public function update(Request $request)
    {
        return $this->saveOfficerRank($request, false);
    }

    private function saveOfficerRank(Request $request, bool $isCreate)
    {
        $validated = $request->validate([
            'officer_id' => ['required', 'integer', 'exists:officers,id'],
            'rank_id' => ['nullable', 'integer', 'exists:ranks,id'],
            'ank' => ['nullable', 'string'],
            'krobkhan' => ['nullable', 'string'],
            'rank' => ['nullable', 'string'],
            'thnak' => ['nullable', 'string'],
            'salary_rank' => ['nullable', 'string'],
            'officer_type' => ['nullable', 'string'],
            'official_date' => ['nullable', 'date'],
            'unofficial_date' => ['nullable', 'date'],
        ]);

        $rankRecord = $this->resolveRankRecord($request);
        if ($rankRecord == null) {
            return response()->json([
                'ok' => false,
                'message' => 'Rank record not found.'
            ], 422);
        }

        $officer = Officer::find($validated['officer_id']);
        if ($officer == null) {
            return response()->json([
                'ok' => false,
                'message' => 'Officer not found.'
            ], 404);
        }

        $workingHistories = $this->extractWorkingHistories($request);
        $userId = optional(\Auth::user())->id ?? 0;

        DB::transaction(function () use ($request, $officer, $rankRecord, $workingHistories, $userId) {
            $officer->rank_id = $rankRecord->id;
            $officer->officer_type = $request->officer_type ?? $request->krobkhan ?? $rankRecord->krobkhan;
            $officer->salary_rank = $request->salary_rank ?? ($rankRecord->krobkhan . '.' . $rankRecord->rank . '.' . $rankRecord->thnak);
            $officer->official_date = $this->normalizeDate($request->official_date);
            $officer->unofficial_date = $this->normalizeDate($request->unofficial_date);
            $officer->updated_by = $userId;
            $officer->updated_at = Carbon::now()->format('Y-m-d H:i:s');
            $officer->save();

            if ($workingHistories !== null) {
                $this->upsertWorkingHistories($officer, $workingHistories, $userId, $rankRecord->id);
            }
        });

        $officer->refresh();
        $officer->rank;
        $officer->ranking_by_workings = $officer->rankingByWorkings()->orderBy('date', 'desc')->get()->map(function ($rank) {
            $rank->rank;
            $rank->previousRank;
            return $rank;
        });

        return response()->json([
            'ok' => true,
            'message' => $isCreate ? 'Officer rank saved successfully.' : 'Officer rank updated successfully.',
            'record' => [
                'officer_id' => $officer->id,
                'rank_id' => $officer->rank_id,
                'ank' => optional($officer->rank)->ank,
                'krobkhan' => optional($officer->rank)->krobkhan,
                'rank' => optional($officer->rank)->rank,
                'thnak' => optional($officer->rank)->thnak,
                'prefix' => optional($officer->rank)->prefix,
                'current_rank_ank' => optional($officer->rank)->ank,
                'current_salary_rank' => optional($officer->rank)->prefix,
                'officer_type' => $officer->officer_type,
                'salary_rank' => $officer->salary_rank,
                'unofficial_date' => $officer->unofficial_date,
                'official_date' => $officer->official_date,
                'ranking_by_workings' => $officer->ranking_by_workings,
            ]
        ], 200);
    }

    private function resolveRankRecord(Request $request): ?RecordModel
    {
        $rankId = intval($request->rank_id ?? 0);
        if ($rankId > 0) {
            return RecordModel::find($rankId);
        }

        $ank = trim((string) ($request->ank ?? ''));
        $krobkhan = trim((string) ($request->krobkhan ?? ''));
        $rank = trim((string) ($request->rank ?? ''));
        $thnak = trim((string) ($request->thnak ?? ''));

        if ($ank === '' || $krobkhan === '' || $rank === '' || $thnak === '') {
            return null;
        }

        return RecordModel::where('ank', $ank)
            ->where('krobkhan', $krobkhan)
            ->where('rank', $rank)
            ->where('thnak', $thnak)
            ->first();
    }

    private function extractWorkingHistories(Request $request): ?array
    {
        $workingHistories = $request->input('ranking_by_workings', $request->input('rankingByWorkings'));

        if ($workingHistories === null) {
            return null;
        }

        if (is_string($workingHistories)) {
            $decoded = json_decode($workingHistories, true);
            return is_array($decoded) ? $decoded : null;
        }

        return is_array($workingHistories) ? $workingHistories : null;
    }

    private function upsertWorkingHistories(Officer $officer, array $workingHistories, int $userId, int $fallbackRankId): void
    {
        foreach ($workingHistories as $history) {
            if (!is_array($history)) {
                continue;
            }

            $recordId = intval($history['id'] ?? 0);
            $shouldDelete = filter_var($history['_delete'] ?? $history['deleted'] ?? false, FILTER_VALIDATE_BOOLEAN);

            if ($shouldDelete) {
                if ($recordId > 0) {
                    $record = OfficerRankByWorking::where('officer_id', $officer->id)->find($recordId);
                    if ($record != null) {
                        $record->deleted_by = $userId;
                        $record->save();
                        $record->delete();
                    }
                }
                continue;
            }

            $rankId = $this->resolveNestedRankId($history, ['rank_id'], ['rank']) ?: $fallbackRankId;
            $previousRankId = $this->resolveNestedRankId($history, ['previous_rank_id'], ['previous_rank', 'previousRank']) ?: 0;

            $payload = [
                'officer_id' => $officer->id,
                'organization' => $history['organization'] ?? '',
                'sub_organization' => $history['sub_organization'] ?? '',
                'sub_sub_organization' => $history['sub_sub_organization'] ?? '',
                'date' => $this->normalizeRequiredDate($history['date'] ?? null),
                'rank_id' => $rankId,
                'previous_rank_id' => $previousRankId,
                'changing_type' => isset($history['changing_type']) ? (string) $history['changing_type'] : null,
                'desp' => $history['desp'] ?? null,
                'updated_by' => $userId,
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];

            if ($recordId > 0) {
                $record = OfficerRankByWorking::where('officer_id', $officer->id)->find($recordId);
                if ($record != null) {
                    $record->update($payload);
                    continue;
                }
            }

            $payload['created_by'] = $userId;
            $payload['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
            $payload['pdf'] = $history['pdf'] ?? '';
            OfficerRankByWorking::create($payload);
        }
    }

    private function resolveNestedRankId(array $history, array $idKeys, array $objectKeys): ?int
    {
        foreach ($idKeys as $idKey) {
            $rankId = intval($history[$idKey] ?? 0);
            if ($rankId > 0) {
                return RecordModel::where('id', $rankId)->exists() ? $rankId : null;
            }
        }

        $rankObject = null;
        foreach ($objectKeys as $objectKey) {
            if (isset($history[$objectKey]) && is_array($history[$objectKey])) {
                $rankObject = $history[$objectKey];
                break;
            }
        }
        if (!is_array($rankObject)) {
            return null;
        }

        $ank = trim((string) ($rankObject['ank'] ?? ''));
        $krobkhan = trim((string) ($rankObject['krobkhan'] ?? ''));
        $rank = trim((string) ($rankObject['rank'] ?? ''));
        $thnak = trim((string) ($rankObject['thnak'] ?? ''));

        if ($ank === '' || $krobkhan === '' || $rank === '' || $thnak === '') {
            return null;
        }

        return optional(
            RecordModel::where('ank', $ank)
                ->where('krobkhan', $krobkhan)
                ->where('rank', $rank)
                ->where('thnak', $thnak)
                ->first()
        )->id;
    }

    private function normalizeDate($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $date = trim((string) $value);
        if ($date === '') {
            return null;
        }

        return Carbon::parse($date)->format('Y-m-d');
    }

    private function normalizeRequiredDate($value): string
    {
        return $this->normalizeDate($value) ?? '';
    }

    public function structure(Request $request)
    {
        return response()->json([
            'ok' => true,
            'message' => 'Success.',
            'records' => RecordModel::select('ank')->groupBy(['ank'])->orderBy('ank')->get()->map(function ($record) {
                return [
                    'ank' => $record->ank,
                    'krobkhans' => RecordModel::select('ank', 'krobkhan', 'krobkhan_name')
                        ->where('ank', $record->ank)
                        ->groupBy(['ank', 'krobkhan', 'krobkhan_name'])
                        ->orderBy('krobkhan')
                        ->get()
                        ->map(function ($record) {
                            return [
                                'krobkhan' => $record->krobkhan,
                                'krobkhan_name' => $record->krobkhan_name,
                                'ranks' => RecordModel::select('rank', 'krobkhan', 'ank', 'krobkhan_name', 'name')
                                    ->where([
                                        'ank' => $record->ank,
                                        'krobkhan' => $record->krobkhan,
                                        'krobkhan_name' => $record->krobkhan_name,
                                    ])
                                    ->groupBy(['ank', 'krobkhan', 'krobkhan_name', 'rank', 'name'])
                                    ->orderBy('rank')
                                    ->get()
                                    ->map(function ($record) {
                                        return [
                                            'rank' => $record->rank,
                                            'krobkhan' => $record->krobkhan,
                                            'krobkhan_name' => $record->krobkhan_name,
                                            'name' => $record->name,
                                            'thnaks' => RecordModel::select('thnak')
                                                ->where([
                                                    'ank' => $record->ank,
                                                    'krobkhan' => $record->krobkhan,
                                                    'krobkhan_name' => $record->krobkhan_name,
                                                    'rank' => $record->rank
                                                ])
                                                ->orderBy('thnak')
                                                ->get()
                                        ];
                                    })
                            ];
                        }),
                ];
            })
        ], 200);
    }
}
