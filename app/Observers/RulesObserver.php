<?php

namespace App\Observers;

use App\Models\Log\Rules;

class RulesObserver
{
    /**
     * Handle the rules "created" event.
     *
     * @param Rules $rules
     * @return void
     */
    public function created(Rules $rules)
    {
        //
    }

    /**
     * Handle the rules "updated" event.
     *
     * @param Rules $rules
     * @return void
     */
    public function updated(Rules $rules)
    {
        //
    }

    /**
     * Handle the rules "deleted" event.
     *
     * @param Rules $rules
     * @return void
     */
    public function deleted(Rules $rules)
    {
        //
    }

    /**
     * Handle the rules "restored" event.
     *
     * @param Rules $rules
     * @return void
     */
    public function restored(Rules $rules)
    {
        //
    }

    /**
     * Handle the rules "force deleted" event.
     *
     * @param Rules $rules
     * @return void
     */
    public function forceDeleted(Rules $rules)
    {
        //
    }
}
