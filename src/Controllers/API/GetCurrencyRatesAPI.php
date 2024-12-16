<?php
use App\Models\DatabaseCRUD\DatabaseCRUDModel;
use App\Services\CurrencyRateLocalAPI\CurrencyRateRequestService;
use App\Services\CurrencyRateLocalAPI\ValidatorService;
use App\Services\CurrencyRateLocalAPI\RequestCleanerService;
use App\Services\CurrencyRateLocalAPI\Interfaces\CurrencyRateRequestInterface;
use App\Services\CurrencyRateLocalAPI\Interfaces\ValidatorInterface;
use App\Models\DatabaseCRUD\DatabaseCRUDInterface;
use App\Services\CurrencyRateLocalAPI\Interfaces\RequestCleanerInterface;

header('Content-Type: application/json');

class GetCurrencyRatesAPI
{
    private CurrencyRateRequestInterface $request;
    private ValidatorInterface $validator;
    private DatabaseCRUDInterface $databaseCRUDService;
    private RequestCleanerInterface $requestCleanerService;

    public function __construct(
        CurrencyRateRequestInterface $request,
        ValidatorInterface $validator,
        DatabaseCRUDInterface $databaseCRUDService,
        RequestCleanerInterface $requestCleanerService
    ) {
        $this->request = $request;
        $this->validator = $validator;
        $this->databaseCRUDService = $databaseCRUDService;
        $this->requestCleanerService = $requestCleanerService;
    }

    public function handleApiRequest()
    {
        try {
            $fromCurrency = $this->request->getParam('from');
            $toCurrency = $this->request->getParam('to');

            if (!$fromCurrency || !$toCurrency) {
                throw new Exception('Missing required parameters: from, to');
            }

            $fromCurrency = $this->requestCleanerService->clean($fromCurrency);
            $toCurrency = $this->requestCleanerService->clean($toCurrency);


            if (!$this->validator->validateCurrencyCode($fromCurrency) || !$this->validator->validateCurrencyCode($toCurrency)) {
                throw new Exception('Invalid currency format. Use three-letter codes like "USD".');
            }

            $rate = $this->databaseCRUDService->getCurrencyRates($fromCurrency, $toCurrency);

            if (!$rate) {
                throw new Exception("Exchange rate not found for $fromCurrency to $toCurrency.");
            }

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'inputData' => [
                    'from' => $fromCurrency,
                    'to' => $toCurrency
                ],
                'outputData' => $rate
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

$databaseCRUDModel = new DatabaseCRUDModel();
$currencyRateRequestService = new CurrencyRateRequestService();
$validatorService = new ValidatorService();
$requestCleanerService = new RequestCleanerService();

$getCurrencyRatesHistAPI = new GetCurrencyRatesAPI(
    $currencyRateRequestService,
    $validatorService,
    $databaseCRUDModel,
    $requestCleanerService
);

$getCurrencyRatesHistAPI->handleApiRequest();
