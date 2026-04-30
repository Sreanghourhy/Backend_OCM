<?php

namespace App\Models\Document;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Officer\OfficerJob;

class OrganizationFocalPeople extends Model
{
    use SoftDeletes;

    protected $table = 'document_organization_focal_people';

    protected $fillable = [
        'organization_structure_id',
        'organization_structure_position_id',
        'is_default',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    /**
     * គណនីទីនេះគឺត្រូវតែមានភ្ជាប់ជាមួយមន្ត្រី។
     * ដោយសារក្នុងនេះភ្ជាប់ user នៅទីនេះដោយសារ user គឺជាអ្នកចូលប្រើប្រព័ន្ធ
     * តែ officer ជាមន្ត្រីក្នុងស្ថាប័នដែលត្រូវទទួលការងារជាអ្នកទទួលឯកសារ។
     */

    /**
     * Organization Structure
     */
    public function organizationStructure()
    {
        return $this->belongsTo(
            \App\Models\Organization\OrganizationStructure::class,
            'organization_structure_id',
            'id'
        );
    }

    /**
     * Organization Structure Position
     */
    public function organizationStructurePosition()
    {
        return $this->belongsTo(
            \App\Models\Organization\OrganizationStructurePosition::class,
            'organization_structure_position_id',
            'id'
        );
    }

    /**
     * Resolve real officers dynamically via OfficerJob
     */
    public function officerJobs()
    {
        return $this->hasMany(
            OfficerJob::class,
            'organization_structure_position_id',
            'organization_structure_position_id'
        )->whereHas(
            'organizationStructurePosition.organizationStructure',
            function ($q) {
                $q->where('id', $this->organization_structure_id);
            }
        );
    }

    /**
     * Creator (User who configures focal people)
     */
    public function creator()
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'created_by',
            'id'
        );
    }

    /**
     * Editor
     */
    public function editor()
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'updated_by',
            'id'
        );
    }

    /**
     * Destroyer
     */
    public function destroyer()
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'deleted_by',
            'id'
        );
    }
}
