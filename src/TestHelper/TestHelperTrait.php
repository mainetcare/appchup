<?php

namespace Mainetcare\Appchup\TestHelper;


use Illuminate\Support\Facades\DB;

/**
 * Trait CreatesUsers
 * @package Tests
 */
trait TestHelperTrait {


    /**
     * we need this only if we have the mysql switch on
     */
    public function setForeignKeyChecksFalse() {
        DB::statement( 'SET FOREIGN_KEY_CHECKS=0;' );
    }

    public function setForeignKeyChecksTrue() {
        DB::statement( 'SET FOREIGN_KEY_CHECKS=1;' );
    }

    /**
     * Asserts that it has given fields in Table and these fields are mass fillable (not guarded by model)
     *
     * @param $classname
     * @param $data
     */
    public function assertFieldsAndFillable( $classname, $data ) {
        $model = app( $classname )->create( $data );
        $model->fresh();
        $arr2 = collect( $model->getAttributes() )->only( array_keys( $data ) )->toArray();
        $this->assertEquals( $data, $arr2 );
    }


}
