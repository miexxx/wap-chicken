<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    const NORMAL = 1;
    const ACTIVE = 2;
    const NOTICE = 3;

    protected $statusMap = [
        self::NORMAL => 'normal',
        self::ACTIVE => 'active',
        self::NOTICE => 'notice',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articleInfos()
    {
        return $this->hasMany(AticleInfo::class,'article_id','id');
    }
}
