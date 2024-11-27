<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function student(){
        return $this->belongsTo(Student::class);
    }
    public function assessor(){
        return $this->belongsTo(Assessor::class);
    }
    public function element(){
        return $this->belongsTo(CompetencyElement::class, 'element_id');
    }

    public function competency_standars()
    {
        return $this->belongsTo(CompentencyStandard::class, 'standar_id');

    }

}
