<?php

namespace App\Mail;

use App\Models\ProcessOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class ProcessOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $pdfPath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ProcessOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Generar PDF
        $pdf = Pdf::loadView('processing.orders.pdf', ['order' => $this->order]);
        $pdfContent = $pdf->output();
        
        $fileName = 'Orden_Proceso_' . $this->order->order_number . '.pdf';
        
        return $this->subject('Orden de Proceso - ' . $this->order->order_number)
                    ->view('emails.process_order')
                    ->attachData($pdfContent, $fileName, [
                        'mime' => 'application/pdf',
                    ])
                    ->with([
                        'order' => $this->order,
                    ]);
    }
}
