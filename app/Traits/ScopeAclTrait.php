<?php

namespace App\Traits;

use App\Helpers\Classes\AuthHelper;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait ScopeInstituteAclTrait
 *
 * @method static Builder|$this acl()
 */
trait ScopeAclTrait
{
    public function scopeAcl(Builder $query, string $alias = null): Builder
    {
        if (empty($alias)) {
            $alias = $this->getTable().'.';
        }

        if (AuthHelper::checkAuthUser()) {
            $authUser = AuthHelper::getAuthUser();
        }

        return $query;
    }
}
