<?php

namespace App\Jobs;

use Exception;
use App\Livewire\DaftarPenjualan;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWaJob implements ShouldQueue
{
    use Queueable;
    protected $penjualanId;

    /**
     * Create a new job instance.
     */
    public function __construct($penjualanId)
    {
        $this->penjualanId = $penjualanId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            // Call the sendWa method
            (new DaftarPenjualan)->sendWa($this->penjualanId);
        } catch (Exception $e) {
            // Log the error if sending fails
            Log::error('Failed to send WhatsApp message: ' . $e->getMessage());
        }
    }
}
