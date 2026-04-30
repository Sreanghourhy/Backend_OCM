<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

use PDF;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(AttendantCheckTimesTableSeeder::class);
        $this->call(AttendantsTableSeeder::class);
        $this->call(BirthCertificatesTableSeeder::class);
        $this->call(CardsTableSeeder::class);
        $this->call(CertificateGroupsTableSeeder::class);
        $this->call(CertificatesTableSeeder::class);

        $this->call(CommunesTableSeeder::class);
        $this->call(CountesiesTableSeeder::class);
        $this->call(DistrictsTableSeeder::class);
        $this->call(FoldersTableSeeder::class);

        $this->call(LegalDraftsTableSeeder::class);


        $this->call(MeetingAttendantsTableSeeder::class);
        $this->call(MeetingMembersTableSeeder::class);
        $this->call(MeetingOrganizationsTableSeeder::class);
        $this->call(MeetingRegulatorsTableSeeder::class);
        $this->call(MeetingRoomsTableSeeder::class);
        $this->call(MeetingsTableSeeder::class);
        $this->call(NationalityCardsTableSeeder::class);
        $this->call(OfficerJobBackgroundsTableSeeder::class);
        $this->call(OfficerJobsTableSeeder::class);
        $this->call(OfficerMedalHistoriesTableSeeder::class);
        $this->call(OfficerPenaltyHistoriesTableSeeder::class);
        $this->call(OfficerRankByCertificatesTableSeeder::class);
        $this->call(OfficerRankByWorkingsTableSeeder::class);
        $this->call(OfficerWorkPendingsTableSeeder::class);
        $this->call(OfficersTableSeeder::class);
        $this->call(OrganizationStructurePositionsTableSeeder::class);
        $this->call(OrganizationStructuresTableSeeder::class);
        $this->call(OrganizationsTableSeeder::class);

        $this->call(PassportsTableSeeder::class);
        $this->call(PeopleTableSeeder::class);
        $this->call(PeopleLanguagesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PositionsTableSeeder::class);
        $this->call(ProvincesTableSeeder::class);
        $this->call(RanksTableSeeder::class);
        $this->call(RegulatorFolderTableSeeder::class);
        $this->call(RegulatorsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(RoomsTableSeeder::class);

        $this->call(SignaturesTableSeeder::class);
        $this->call(TaskAssignmentsTableSeeder::class);
        $this->call(TasksTableSeeder::class);
        $this->call(TimeslotsTableSeeder::class);
        $this->call(UserRoleTableSeeder::class);
        $this->call(UserTimeslotsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(VillagesTableSeeder::class);
        $this->call(WeddingCertificatesTableSeeder::class);
        $this->call(DocumentTransactionsTableSeeder::class);
        $this->call(DocumentsTableSeeder::class);
        $this->call(DocumentBriefingsTableSeeder::class);
        $this->call(DocumentSignaturesTableSeeder::class);
        $this->call(DocumentShortSignaturesTableSeeder::class);
        $this->call(DocumentTransactionReceiversTableSeeder::class);
        $this->call(OrganizationStructurePositionPermissionsTableSeeder::class);
        $this->call(DocumentOrganizationFocalPeopleTableSeeder::class);
        $this->call(IndustriesTableSeeder::class);

        // Run with arguments
        Artisan::call('pgsql:reindexing'
            // , [
            //     'argument' => 'value',
            //     '--option' => 'value'
            //     '--quiet' => true
            //     '--force' => true,
            //     '--seed' => false
            // ]
        );
        
        // Get output
        $output = Artisan::output();
        
        $this->command->info('Command \'pgsql:reindexing\' executed: ' . PHP_EOL . $output . PHP_EOL );
    }

}
