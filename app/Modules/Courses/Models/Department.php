<?php
namespace App\Modules\Courses\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'department_head_id'];

    public function departmentHead()
    {
        return $this->belongsTo(User::class, 'department_head_id');
    }
}