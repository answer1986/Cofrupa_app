<?php

namespace App\Mail;

use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransportShipmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $shipment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Shipment $shipment)
    {
        $this->shipment = $shipment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Instrucciones de Despacho - ' . $this->shipment->shipment_number)
                    ->view('emails.transport_shipment')
                    ->with([
                        'shipment' => $this->shipment,
                    ]);
    }
}
