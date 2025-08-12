<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductoCreadoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $producto;
    protected $usuario;

    public function __construct($producto, $usuario)
    {
        $this->producto = $producto;
        $this->usuario = $usuario;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // También puedes guardar en DB
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🆕 Nuevo producto registrado: ' . $this->producto->nombre)
            ->greeting('¡Nuevo producto!')
            ->line('Se ha registrado un nuevo producto en el sistema:')
            ->line('Nombre: ' . $this->producto->nombre)
            ->line('Código: ' . $this->producto->codigo)
            ->line('Precio: $' . number_format($this->producto->precio, 2))
            ->action('Ver producto', url('/productos/' . $this->producto->id))
            ->line('Registrado por: ' . $this->usuario->name)
            ->salutation('Saludos, Sistema de Inventario');
    }

    public function toArray($notifiable)
    {
        return [
            'producto_id' => $this->producto->id,
            'mensaje' => 'Nuevo producto: ' . $this->producto->nombre,
            'usuario' => $this->usuario->name
        ];
    }
}