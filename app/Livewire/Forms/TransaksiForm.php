<?php

namespace App\Livewire\Forms;

use App\Models\StatusLog;
use App\Models\Transaksi;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TransaksiForm extends Form
{
    public $customer_id;
    public $description;
    public $items;
    public $price;
    public $status;
    public $created_at;
    public ?Transaksi $transaksi;
    
    public function setTransaksi(Transaksi $transaksi)
    {
        $this->transaksi = $transaksi;

        $this->customer_id = $transaksi->customer_id;
        $this->description = $transaksi->description;
        $this->items = $transaksi->items;
        $this->price = $transaksi->price;
        $this->status = $transaksi->status;
        $this->created_at = $transaksi->created_at;
    }
    public function store ()
    {
        $validate = $this->validate([
            'customer_id' => '',
            'description' => 'required',
            'items' => 'required',
            'price' => 'required',

        ]);
        $validate['items'] = json_encode($validate['items']);

        $transaksi = Transaksi::create($validate);
        StatusLog::create([
            'transaksi_id' => $transaksi->id, // ID dari transaksi yang baru dibuat
            'status' => 'dibayar', // status awal
            'changed_at' => now(),
        ]);

        $this->reset();
    }
    public function update ()
    {
        $validate = $this->validate([
            'customer_id' => 'required',
            'description' => 'required',
            'items' => 'required',
            'price' => 'required',
            'status' => 'required',
        ]);

        $this->transaksi->update($validate);
        StatusLog::create([
            'transaksi_id' => $this->transaksi->id,
            'status' => $this->status,
            'changed_at' => now(),
        ]);

        $this->reset();
    }
}
