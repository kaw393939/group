<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Kaw393939\Group\Models\Permission;
use Kaw393939\Group\Models\Role;

/**
 * Class LaratrustSetupTables
 */
class LaratrustSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
        // Create table for storing roles
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for storing permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating roles to users and teams (Many To Many Polymorphic)
        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->string('user_type');
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('group_id');

            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('group_id')->references('id')->on('groups')
                ->onUpdate('cascade')->onDelete('cascade');


            // Create a unique key
            $table->unique(['user_id', 'role_id', 'user_type', 'group_id']);

        });

        // Create table for associating permissions to users (Many To Many Polymorphic)
        Schema::create('permission_user', function (Blueprint $table) {
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('user_id');
            $table->string('user_type');

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');

            // Add team_id column
            $table->unsignedInteger('group_id')->nullable();

            $table->foreign('group_id')->references('id')->on('groups')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unique(['user_id', 'permission_id', 'user_type', 'group_id']);
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('permission_role', function (Blueprint $table) {
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('role_id');

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        DB::table('roles')->insert(
            array(
                'name' => 'owner',
                'display_name' => 'Owner',
                'description' => 'User is the owner of a given group',
                "created_at" => \Carbon\Carbon::now(), # \Datetime()
                "updated_at" => \Carbon\Carbon::now()  # \Datetime()
            )

        );
        DB::table('roles')->insert(
            array(
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'User is the Admin of a given group',
                "created_at" => \Carbon\Carbon::now(), # \Datetime()
                "updated_at" => \Carbon\Carbon::now()  # \Datetime()
            )

        );
        DB::table('roles')->insert(
            array(
                'name' => 'member',
                'display_name' => 'Member',
                'description' => 'User is a member of a given group',
                "created_at" => \Carbon\Carbon::now(), # \Datetime()
                "updated_at" => \Carbon\Carbon::now()  # \Datetime()
            )
        );
        DB::table('permissions')->insert(
            array(
                'name' => 'store-group',
                'display_name' => 'Create GroupResource',
                'description' => 'Create new GroupResource',
                "created_at" => \Carbon\Carbon::now(), # \Datetime()
                "updated_at" => \Carbon\Carbon::now()  # \Datetime()
            )

        );
        DB::table('permissions')->insert(
            array(
                'name' => 'update-group',
                'display_name' => 'Update GroupResource',
                'description' => 'Update GroupResource',
                "created_at" => \Carbon\Carbon::now(), # \Datetime()
                "updated_at" => \Carbon\Carbon::now()  # \Datetime()
            )
        );
        DB::table('permissions')->insert(
            array(
                'name' => 'destroy-group',
                'display_name' => 'Delete GroupResource',
                'description' => 'Delete GroupResource',
                "created_at" => \Carbon\Carbon::now(), # \Datetime()
                "updated_at" => \Carbon\Carbon::now()  # \Datetime()
            )
        );
        DB::table('permissions')->insert(
            array(
                'name' => 'index-group',
                'display_name' => 'View Index GroupResource',
                'description' => 'View Index GroupResource',
                "created_at" => \Carbon\Carbon::now(), # \Datetime()
                "updated_at" => \Carbon\Carbon::now()  # \Datetime()
            )
        );
        DB::table('permissions')->insert(
            array(
                'name' => 'show-group',
                'display_name' => 'Show GroupResource',
                'description' => 'Show GroupResource',
                "created_at" => \Carbon\Carbon::now(), # \Datetime()
                "updated_at" => \Carbon\Carbon::now()  # \Datetime()
            )
        );
        DB::table('permissions')->insert(
            array(
                'name' => 'store-group-users',
                'display_name' => 'Add Group Users',
                'description' => 'Add Group Users Description',
                "created_at" => \Carbon\Carbon::now(), # \Datetime()
                "updated_at" => \Carbon\Carbon::now()  # \Datetime()
            )
        );
        DB::table('permissions')->insert(
            array(
                'name' => 'update-group-users',
                'display_name' => 'Update Group Users',
                'description' => 'Update Group Users Description',
                "created_at" => \Carbon\Carbon::now(), # \Datetime()
                "updated_at" => \Carbon\Carbon::now()  # \Datetime()
            )
        );
        DB::table('permissions')->insert(
            array(
                'name' => 'destroy-group-users',
                'display_name' => 'Remove Group Users',
                'description' => 'Remove Group Users Description',
                "created_at" => \Carbon\Carbon::now(), # \Datetime()
                "updated_at" => \Carbon\Carbon::now()  # \Datetime()
            )
        );

        DB::table('permissions')->insert(
            array(
                'name' => 'index-group-users',
                'display_name' => 'Index Group Users',
                'description' => 'Index Group Users Description',
                "created_at" => \Carbon\Carbon::now(), # \Datetime()
                "updated_at" => \Carbon\Carbon::now()  # \Datetime()
            )
        );
        DB::table('permissions')->insert(
            array(
                'name' => 'show-group-users',
                'display_name' => 'Show Group Users',
                'description' => 'Show Group Users Description',
                "created_at" => \Carbon\Carbon::now(), # \Datetime()
                "updated_at" => \Carbon\Carbon::now()  # \Datetime()
            )
        );


        $ownerRole = Role::where('name', 'owner')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $memberRole = Role::where('name', 'member')->first();


        $storeGroupPermission = Permission::where('name', 'store-group')->first();
        $updateGroupPermission = Permission::where('name', 'update-group')->first();
        $destroyGroupPermission = Permission::where('name', 'destroy-group')->first();
        $indexGroupPermission = Permission::where('name', 'index-group')->first();
        $showGroupPermission = Permission::where('name', 'show-group')->first();

        $storeGroupUsersPermission = Permission::where('name', 'store-group-users')->first();
        $updateGroupUsersPermission = Permission::where('name', 'update-group-users')->first();
        $destroyGroupUsersPermission = Permission::where('name', 'destroy-group-users')->first();
        $indexGroupUsersPermission = Permission::where('name', 'index-group-users')->first();
        $showGroupUsersPermission = Permission::where('name', 'show-group-users')->first();

        $adminRole->attachPermission($storeGroupPermission);
        $adminRole->attachPermission($updateGroupPermission);
        $adminRole->attachPermission($indexGroupPermission);
        $adminRole->attachPermission($showGroupPermission);

        $adminRole->attachPermission($storeGroupUsersPermission);
        $adminRole->attachPermission($destroyGroupUsersPermission);
        $adminRole->attachPermission($indexGroupUsersPermission);
        $adminRole->attachPermission($showGroupUsersPermission);

        $ownerRole->attachPermission($storeGroupPermission);
        $ownerRole->attachPermission($updateGroupPermission);
        $ownerRole->attachPermission($destroyGroupPermission);
        $ownerRole->attachPermission($indexGroupPermission);
        $ownerRole->attachPermission($showGroupPermission);

        $ownerRole->attachPermission($storeGroupUsersPermission);
        $ownerRole->attachPermission($updateGroupUsersPermission);
        $ownerRole->attachPermission($destroyGroupUsersPermission);
        $ownerRole->attachPermission($indexGroupUsersPermission);
        $ownerRole->attachPermission($showGroupUsersPermission);

        $memberRole->attachPermission($indexGroupPermission);
        $memberRole->attachPermission($showGroupPermission);

        $memberRole->attachPermission($indexGroupUsersPermission);
        $memberRole->attachPermission($showGroupUsersPermission);

        DB::table('groups')->insert(
            array(
                'name' => 'system',
                'display_name' => 'System Users',
                'description' => 'System Users',
                "created_at" => \Carbon\Carbon::now(), # \Datetime()
                "updated_at" => \Carbon\Carbon::now()  # \Datetime()
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('permission_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('groups');
    }
}
