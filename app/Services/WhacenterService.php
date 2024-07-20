<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhacenterService
{

    protected ?string $to;
    protected array $lines;
    protected string $baseUrl = '';
    protected ?string $deviceId = '';
    protected $message = '';


    /**
     * constructor.
     * @param array $lines
     */
    public function __construct($lines = [])
    {
        $this->lines = $lines;
        $this->baseUrl = 'https://app.whacenter.com/api';
        $this->deviceId = settings('wha_device_id');
    }

    public function requestDeviceStatus()
    {
        return Http::withoutVerifying()->get($this->baseUrl . '/statusDevice?device_id=' . $this->deviceId);
    }

    public function getDeviceStatus(): bool
    {
        $response = $this->requestDeviceStatus();
        $response->onError(function ($q) {
            return false;
        });
        $responseBody = json_decode($response->body());
        return $responseBody->status;
    }

    public function line($line = ''): self
    {
        $this->lines[] = $line;
        return $this;
    }

    public function to($to): self
    {
        $this->to = $to;
        return $this;
    }

    public function send(): bool
    {
        if ($this->to != '') {
            $params = 'device_id=' . $this->deviceId . 'number=' . $this->to . '&message=' . urlencode(implode("\n", $this->lines));
            try {
                Http::get($this->baseUrl . '/send?', $params);
                $this->message = 'OK';
                return true;
            } catch (\Throwable $th) {
                $this->message = $th->getMessage();
                return false;
            }
        }
    }

    public function getMessage()
    {
        return $this->message;
    }
}
