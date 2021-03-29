<?php

namespace Mainetcare\Appchup\SeederHelper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;


trait SeedFromSqlfileTrait {

    public function seedFromSqlfile( $filename = null ) {

        $table = $this->getTable();

        if ( ! $filename ) {
            $filename = $table . '.sql';
        }

        $path = env( 'MNC_FOLDER_STORAGE_SEEDING_FILES' ) . '/' . $filename;

        if ( ! Storage::disk( 'local' )->exists( $path ) ) {
            throw new FileNotFoundException( $path );
        }

        $this->importFromPath( $path );
        $this->setTimestamps( $table );

        $this->command->info( $table . ' imported' );

    }

    protected function getTable() {
        if ( ! $this->table ) {
            $this->table = strtolower( str_replace( 'Seeder', '', __CLASS__ ) ) . 's';
        }

        return $this->table;
    }

    protected function importFromPath( $path ) {
        try {
            $file = Storage::disk( 'local' )->get( $path );
            if ( $file ) {
                DB::unprepared( $file );
            }
        } catch ( \Exception $e ) {
            $error = $e->getMessage();
            // dont show insert commands
            if ( strlen( $error ) <= 10000 ) {
                $this->command->info( $error );
            }
            $dbhost = config( 'database.connections.mysql.host' );
            $dbuser = config( 'database.connections.mysql.username' );
            $dbpass = config( 'database.connections.mysql.password' );
            $db     = config( 'database.connections.mysql.database' );
            $file   = Storage::disk( 'local' )->path( $path );
            $cmd    = 'mysql -h ' . $dbhost . ' --user=' . $dbuser . ' --password=' . $dbpass . ' ' . $db . ' < "' . $file . '"';
            // @todo find out why does the following not work but manually in terminal it works:
            // shell_exec( '"' . $cmd . '"');
            $this->command->warn( 'file is too large for pdo execution. try following command in terminal:' );
            $this->command->info( $cmd );
        }
    }

    protected function setTimestamps( $table ) {
        if ( Schema::hasColumn( $table, 'updated_at' ) ) {
            DB::connection()->table( $table )->whereNull( 'updated_at' )->update( [ 'updated_at' => DB::raw( 'now()' ) ] );
        }
        if ( Schema::hasColumn( $table, 'created_at' ) ) {
            DB::connection()->table( $table )->whereNull( 'created_at' )->update( [ 'created_at' => DB::raw( 'now()' ) ] );
        }
    }


}
