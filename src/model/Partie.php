<?php

namespace player\geoquizz\model;

class Partie extends \Illuminate\Database\Eloquent\Model { //Definition de la classe

  protected $table = 'partie';
  protected $primaryKey = 'id';
  public $incrementing = false;
  public $keyType='string';

}

