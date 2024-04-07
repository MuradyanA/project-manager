<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement($this->createView());
    }

    private function createView(): string
    {
       DB::statement($this->dropView());
       return <<<SQL
                CREATE VIEW `sprintsView` AS
                select
                `sprints`.`id` AS `id`,
                `sprints`.`sprint` AS `sprint`,
                `sprints`.`start` AS `start`,
                `sprints`.`end` AS `end`,
                (case
                when (`sprints`.`id` = `t`.`LastSprId`) then 'Active'
                else 'Closed'
                end) AS `Status`,
                `t`.`taskCount` AS `taskCount`
                from
                (`sprints`
                left join (
                select
                `tasks`.`sprintId` AS `sprintId`,
                count(0) AS `taskCount`,
                max(`tasks`.`sprintId`) AS `LastSprId`
                from
                `tasks`
                group by
                `tasks`.`sprintId`) `t` on
                ((`sprints`.`id` = `t`.`sprintId`)));
            SQL;
    }

    private function dropView(): string
    {
        return <<<SQL
            DROP VIEW IF EXISTS `sprintsView`;
            SQL;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement($this->dropView());
    }
};
