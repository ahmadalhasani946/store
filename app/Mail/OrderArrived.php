<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderArrived extends Mailable
{
    use Queueable, SerializesModels;

    public $descriptions, $id, $quantities;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($descriptions, $quantities , $id)
    {
        $this->descriptions = $descriptions;
        $this->id = $id;
        $this->quantities = $quantities;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to('a.alhasani@imtilak.net')->subject('A New Order Arrived')->markdown('emails.OrderArrived');
    }
}
