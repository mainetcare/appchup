<?php


namespace Mainetcare\Appchup\SeederHelper;


use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;

class DBInsertInto {

    protected array $map = [];
    protected array $array_map;
    protected ?ConnectionInterface $origin_connection = null;
    protected string $table_source;
    protected string $table_dest;
    /**
     * @var ConnectionInterface
     */
    private ConnectionInterface $dest_connection;

    /**
     * DBInsertInto constructor.
     *
     * @param ConnectionInterface $connection
     * @param string $table_source
     * @param string $table_dest
     */
    public function __construct( ConnectionInterface $connection, string $table_source, string $table_dest ) {
        $this->origin_connection = $connection;
        $this->dest_connection   = DB::connection( 'mysql' );
        $this->table_source      = $table_source;
        $this->table_dest        = $table_dest;
    }

    public function run() {
        DB::statement( DB::raw( 'SET sql_mode=""' ) );
        $this->buildMapper( $this->table_source );
        $cols_source = $this->asColString( array_keys( $this->array_map ) );
        $cols_dest   = $this->asColString( array_values( $this->array_map ) );
        $i            = "INSERT INTO %s (%s) SELECT %s FROM %s";
        $parse_insert = sprintf( $i,
            $this->prefixDest( $this->table_dest ),
            $cols_dest,
            $cols_source,
            $this->prefixSource( $this->table_source )
        );
        DB::insert( $parse_insert );
    }

    private function prefixSource( $name ) {
        return $this->origin_connection->getDatabaseName() . '.' . $name;
    }

    private function prefixDest( $name ) {
        return $this->dest_connection->getDatabaseName() . '.' . $name;
    }


    public function ignoreColumns( $arr ): DBInsertInto {
        foreach ( $arr as $columnname ) {
            $this->map[ $columnname ] = false;
        }

        return $this;
    }

    /**
     * @param $map
     * use only the changed columns
     *
     * @return $this
     */
    public function mapColumns( array $map ): DBInsertInto {
        $this->map = array_merge( $this->map, $map );

        return $this;
    }

    protected function buildMapper( $table ) {
        $this->array_map = [];
        $columns         = $this->origin_connection->getSchemaBuilder()->getColumnListing( $table );
        foreach ( $columns as $name ) {
            if ( $mapped_column = $this->getMappedColumn( $name ) ) {
                $this->array_map[ $name ] = $mapped_column;
            }
        }
    }

    protected function getMappedColumn( $origin_column ) {
        if ( ! array_key_exists( $origin_column, $this->map ) ) {
            return $origin_column;
        }

        return $this->map[ $origin_column ];
    }

    private function asColString( array $arr ) {
        return implode( ', ', $arr );
    }

}
