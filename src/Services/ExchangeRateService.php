<?php

namespace Exxtensio\EcommerceDashboard\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Exxtensio\EcommerceDashboard\Models\Currency;

class ExchangeRateService
{
    private ?string $apiKey;
    private string $endpoint;

    public function __construct()
    {
        $this->apiKey = config('ecommerce.exchangerateApiKey');
        $this->endpoint = "//v6.exchangerate-api.com/v6/$this->apiKey/latest/USD";
    }

    /**
     * @throws Exception
     */
    public function update(): void
    {
        try {
            $response = Http::get($this->endpoint);
            if ($response->ok()) {
                $responseJson = $response->json();
                if (isset($responseJson['conversion_rates'])) {
                    collect($responseJson['conversion_rates'])->map(function ($v, $k) {
                        $oldModel = Currency::where('code', $k)->first();
                        if($oldModel) {
                            $oldModel->update(['rate' => $v]);
                            activity()
                                ->performedOn($oldModel)
                                ->withProperties([
                                    'old' => ['rate' => $oldModel->rate],
                                    'attributes' => ['rate' => $v]
                                ])
                                ->event('updated');
                        }
                    });
                }
            }
        } catch (\Exception $e) {}
    }
}
