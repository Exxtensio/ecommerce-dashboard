<?php

namespace Exxtensio\EcommerceDashboard\Traits;

trait Storable
{
    public ?string $disk = null;
    public $file = null;
    public array $files = [];
    public ?string $storage = null;
    public string $storagePath = '/';

    public function disk($disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    public function path($path): static
    {
        $this->storagePath = $path;

        return $this;
    }

    public function getStorageDisk()
    {
        return $this->disk ?: config('dashboard.storage_disk', 'public');
    }

    public function getStorageDir(): string
    {
        return $this->storagePath;
    }
}
