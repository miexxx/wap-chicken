<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AticleInfo extends Model
{
    protected $fillable = ['title','author','digest','url','thumb_url'];

    const NORMAL = 1;
    const ACTIVE = 2;
    const NOTICE = 3;

    protected $statusMap = [
        self::NORMAL => 'normal',
        self::ACTIVE => 'active',
        self::NOTICE => 'notice',
    ];

    protected $displayScope = [
        self::NORMAL => '鸡笼百科',
        self::ACTIVE => '公司活动',
        self::NOTICE => '最新公告',
    ];

    /**
     * @return mixed
     */
    public function getScopeTextAttribute()
    {
        return $this->displayScope[$this->scope];
    }

    /**
     * @return mixed
     */
    public function getScopeInfoAttribute()
    {
        return $this->statusMap[$this->scope];
    }

    /**
     * @param Builder $builder
     * @param $scope
     * @return mixed
     */
    public function scopeFilterScope(Builder $builder, $scope)
    {
        if (array_key_exists($scope, $this->statusMap)) {
            return $builder->where('scope', $scope);
        }

        $value = array_flip($this->statusMap)[$scope];
        return $builder->where('scope', $value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo(Article::class,'article_id','id');
    }
}
