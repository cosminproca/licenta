<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

trait TeamOwned {
    protected static function bootTeamOwned()
    {
        static::addGlobalScope('team', function (Builder $builder) {
            $team_id = static::teamGuard();

            $builder->where($builder->getQuery()->from.'.team_id', $team_id);
        });

        static::saving(function (Model $model) {
            if (! isset($model->team_id)) {
                $team_id = static::teamGuard();

                $model->team_id = $team_id;
            }
        });
    }

    public function scopeAllTeams(Builder $query)
    {
        return $query->withoutGlobalScope('team');
    }

    public function team()
    {
        return $this->belongsTo(Config::get('teamwork.team_model'));
    }

    /**
     * @throws Exception
     */
    protected static function teamGuard()
    {
        $team_id = getTeamKey();

        if (auth()->guest() || !checkIfUserBelongsToTeam($team_id)) {
            throw new Exception('The authenticated user does not belong to that team.');
        }

        return $team_id;
    }
}
