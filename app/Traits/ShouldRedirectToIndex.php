<?php 

namespace App\Traits;

trait ShouldRedirectToIndex {
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}