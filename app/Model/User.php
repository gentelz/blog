<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //1.关联的数据表
    public $table = 'blog_user';

    //2.主键
    public $primaryKey = 'user_id';

    //3.允许批量操作的字段
//    public $fillable = ['user_name','user_pass','email','phone'];
       public $guarded = [];  //不允许批量操作的字段，其他的全都允许

    //4.是否维护created_at 和 updated_at 字段
       public $timestamps = false;
}
