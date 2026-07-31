<?php
class marker_groups_relationModelGmp extends modelGmp
{
  function __construct()
  {
    $this->_setTbl('marker_groups_relation');
  }

  public function getRelationsByMarkerId($id)
  {
    global $wpdb;
    $relations = $wpdb->get_col("SELECT groups_id FROM {$wpdb->prefix}gmp_marker_groups_relation AS gmp_mrgrr WHERE " . $wpdb->prepare('marker_id = %s', $id));
    return $relations;
  }
}
