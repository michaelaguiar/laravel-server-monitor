<?php

namespace Spatie\ServerMonitor\Models\Presenters;

use Spatie\ServerMonitor\Helpers\Emoji;
use Spatie\ServerMonitor\Models\Enums\CheckStatus;

trait CheckPresenter
{
    public function getStatusAsEmojiAttribute(): string
    {
        if ($this->status === CheckStatus::SUCCESS) {
            return Emoji::ok();
        }

        if ($this->status === CheckStatus::FAILED) {
            return Emoji::notOk();
        }

        if ($this->status === CheckStatus::WARNING) {
            return Emoji::warning();
        }

        if ($this->status === CheckStatus::NOT_YET_CHECKED) {
            return Emoji::unknown();
        }

        if (is_null($this->status)) {
            return Emoji::unknown();
        }

        return '';
    }

    public function getSummaryAttribute(): string
    {
        return "{$this->status_as_emoji}  {$this->type}: {$this->last_run_message}";
    }

    public function getLatestRunDiffAttribute(): string
    {
        if (! $this->last_ran_at) {
            return 'Did not run yet';
        }

        return $this->last_ran_at->diffForHumans();
    }

    public function getNextRunDiffAttribute(): string
    {
        if (! $this->next_run_in_minutes) {
            return 'As soon as possible';
        }

        if (! $this->last_ran_at) {
            return 'As soon as possible';
        }

        $nextRun = $this->last_ran_at->addMinutes($this->next_run_in_minutes);

        if ($nextRun->isPast()) {
            return 'As soon as possible';
        }

        return $this->last_ran_at->addMinutes($this->next_run_in_minutes)->diffForHumans();
    }
}
