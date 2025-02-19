<?php

namespace Exxtensio\EcommerceDashboard;

use Closure;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Model;
use Exxtensio\EcommerceDashboard\Exceptions\CouldNotLogActivity;
use Throwable;

class CauserResolver
{
    protected AuthManager $authManager;

    protected string|null $authDriver;

    protected Closure|null $resolverOverride = null;

    protected Model|null $causerOverride = null;

    public function __construct(Repository $config, AuthManager $authManager)
    {
        $this->authManager = $authManager;

        $this->authDriver = null;
    }

    /** @throws CouldNotLogActivity|Throwable */
    public function resolve(Model|int|string|null $subject = null): ?Model
    {
        if ($this->causerOverride !== null)
            return $this->causerOverride;

        if ($this->resolverOverride !== null) {
            $resultCauser = ($this->resolverOverride)($subject);

            if (!$this->isResolvable($resultCauser))
                throw CouldNotLogActivity::couldNotDetermineUser($resultCauser);

            return $resultCauser;
        }

        return $this->getCauser($subject);
    }

    /** @throws Throwable */
    protected function resolveUsingId(int|string $subject): Model
    {
        $guard = $this->authManager->guard($this->authDriver);

        $provider = method_exists($guard, 'getProvider') ? $guard->getProvider() : null;
        $model = method_exists($provider, 'retrieveById') ? $provider->retrieveById($subject) : null;

        throw_unless($model instanceof Model, CouldNotLogActivity::couldNotDetermineUser($subject));

        return $model;
    }

    /** @throws Throwable */
    protected function getCauser(Model|int|string|null $subject = null): ?Model
    {
        if ($subject instanceof Model)
            return $subject;

        if (is_null($subject))
            return $this->getDefaultCauser();

        return $this->resolveUsingId($subject);
    }

    public function resolveUsing(Closure $callback): static
    {
        $this->resolverOverride = $callback;

        return $this;
    }

    public function setCauser(?Model $causer): static
    {
        $this->causerOverride = $causer;

        return $this;
    }

    protected function isResolvable(mixed $model): bool
    {
        return $model instanceof Model || is_null($model);
    }

    protected function getDefaultCauser(): ?Model
    {
        return $this->authManager->guard($this->authDriver)->user();
    }
}
