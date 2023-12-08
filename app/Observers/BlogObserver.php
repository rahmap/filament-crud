<?php

namespace App\Observers;

use App\Models\Blog;
use Illuminate\Support\Facades\Storage;

class BlogObserver
{
    /**
     * Handle the Blog "created" event.
     */
    public function created(Blog $blog): void
    {
        //
    }

    /**
     * Handle the Blog "updating" event.
     */
    public function updating(Blog $blog): void
    {
	    if ($blog->isDirty('image') && ($blog->getOriginal('image') !== null)
		    && Storage::disk('public')->exists($blog->getOriginal('image'))) {
		    Storage::disk('public')->delete($blog->getOriginal('image'));
	    }
    }
	
	/**
     * Handle the Blog "updated" event.
     */
    public function updated(Blog $blog): void
    {
        //
    }

    /**
     * Handle the Blog "deleting" event.
     */
    public function deleting(Blog $blog): void
    {
		//
    }
	
	/**
     * Handle the Blog "deleted" event.
     */
    public function deleted(Blog $blog): void
    {
	    if (!is_null($blog->image) && Storage::disk('public')->exists($blog->image)) {
		    Storage::disk('public')->delete($blog->image);
	    }
    }

    /**
     * Handle the Blog "restored" event.
     */
    public function restored(Blog $blog): void
    {
        //
    }

    /**
     * Handle the Blog "force deleted" event.
     */
    public function forceDeleted(Blog $blog): void
    {
        //
    }
}
