<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;

class ProductSuccessfullyUpdated extends Mailable
{
    use Queueable, SerializesModels;

    protected $product;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = env('MAIL_FROM_ADDRESS');
        return $this->from($email)->view('emails.updated_product')->with([
            'nome' => $this->product->nome,
            'valor' => $this->product->valor,
        ])->subject("O produto ".ucfirst($this->product->nome)." foi atualizado na sua loja!");
    }
}
