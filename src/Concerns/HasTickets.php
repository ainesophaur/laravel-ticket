<?php

namespace Coderflex\LaravelTicket\Concerns;

use Coderflex\LaravelTicket\LaravelTicketServiceProvider;
use Coderflex\LaravelTicket\Models\Message;
use Coderflex\LaravelTicket\Models\Ticket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin Model
 */
trait HasTickets
{
    /**
     * Get User tickets relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(LaravelTicketServiceProvider::determineTicketModel(), $this->getForeignKey());
    }

    /**
     * Get User tickets relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(LaravelTicketServiceProvider::determineMessageModel(), $this->getForeignKey());
    }
}
