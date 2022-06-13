<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateViewFeaturePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement($this->createViewFeaturePermission());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->dropViewFeaturePermission());
    }

    public function createViewFeaturePermission()
    {
        return "CREATE OR REPLACE VIEW v_feature_permission AS SELECT 
            GROUP_CONCAT(fp.role_id) AS role_id, 
            fp.feature_id AS feature_id, 
            GROUP_CONCAT(r.name) AS role_name,
            GROUP_CONCAT(r.slug) AS role_slug, 
            f.name AS feature_name, 
            f.slug AS feature_slug 
        FROM (
            feature_permission fp 
            JOIN feature f ON (fp.feature_id = f.id)
            JOIN role r ON (fp.role_id = r.id)
        ) 
        GROUP BY feature_id";
    }

    public function dropViewFeaturePermission()
    {
        return "DROP VIEW IF EXISTS `view_user_data`";
    }
}
